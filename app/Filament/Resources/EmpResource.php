<?php

namespace App\Filament\Resources;

use App\Models\Emp;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Exports\EmpsExport;
use Filament\Resources\Resource;
use App\Services\WhatsAppService;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Tables\Actions\BulkAction;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\EmpResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EmpResource\RelationManagers;

class EmpResource extends Resource
{
    protected static ?string $model = Emp::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 0;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('user_id')
                //     ->required()
                //     ->numeric(),
                Forms\Components\TextInput::make('name')
                ->label(__('name'))
                    ->required()
                    ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),

                    Forms\Components\TextInput::make('sheet_api_url')
                    ->label('Google Sheet URL'),
                    Forms\Components\TextInput::make('post_url'),
                // Forms\Components\DateTimePicker::make('email_verified_at'),
                // Forms\Components\TextInput::make('password')
                //     ->password()
                //     ->required()
                //     ->dehydrated(false)
                //     ->maxLength(255),
                Forms\Components\TextInput::make('password')
                // ->required()
                ->dehydrated(true)
                ->password()
                ->label('Password')
                ->dehydrateStateUsing(fn ($state) => \Hash::make($state)),

                Forms\Components\TextInput::make('phone')
                    ->numeric()
                    ->maxLength(15)
                    ->default(null),

                Forms\Components\TextInput::make('number_of_hours_per_day')
                    ->required()
                    ->numeric()
                    ->default(8),
                // Forms\Components\TextInput::make('day_off')
                //     ->required(),
                //add ldj
                Forms\Components\Select::make('day_off')
                ->label('Days Off')
                ->options([
                    'Sunday' => 'Sunday',
                    'Monday' => 'Monday',
                    'Tuesday' => 'Tuesday',
                    'Wednesday' => 'Wednesday',
                    'Thursday' => 'Thursday',
                    'Friday' => 'Friday',
                    'Saturday' => 'Saturday',
                ])
                ->multiple() // لدعم اختيار أيام متعددة
                ->required()
                ->default(['Friday']) // القيمة الافتراضية
                ->dehydrated(true) // تأكد من إرسال الحقل عند الحفظ
                ->afterStateHydrated(function ($component, $state) {
                    if (is_string($state)) {
                        $component->state(json_decode($state, true));
                    }
                }),
                Forms\Components\Select::make('request_status')
    ->label('Request Status')
    ->options([
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ])
    ->default('pending')
    ->required()
    ->hiddenOn('create'),


  Forms\Components\Toggle::make('is_admin')
  ->required(),
  Forms\Components\Toggle::make('can_show')
  ->label('Can view other employees\' tasks')
  ->default(false),
  Forms\Components\Toggle::make('is_active')
  ->label('Active')
  ->default(true),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->numeric()
                    ->sortable(),
                    // Tables\Columns\TextColumn::make('user_id')
                    // ->numeric()
                    // ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                    Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('number_of_hours_per_day')
                    ->numeric()
                    ->sortable(),
                    Tables\Columns\IconColumn::make('is_admin')
                    ->boolean(),
                    Tables\Columns\IconColumn::make('is_active') // عرض حالة التفعيل
                    ->label('Active')
                    ->boolean(),
                    Tables\Columns\BadgeColumn::make('request_status')
                    ->label('Request Status')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'pending' => 'Pending',
                            'approved' => 'Approved',
                            'rejected' => 'Rejected',
                            default => 'Unknown',
                        };
                    })
                    ->colors([
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    ])
                    ->sortable(),

                    Tables\Columns\TextColumn::make('day_off')
                    ->label('Days Off')
                    ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('request_status')
                ->label('Request Status')
                ->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->action(function (Emp $record) {
                      // إنشاء كلمة المرور
            $password = $record->user_id . $record->phone;
            $hashedPassword = bcrypt($password);

            // تحديث حالة الطلب وكلمة المرور
            $record->update([
                'request_status' => 'approved',
                'password' => $hashedPassword,
                'is_active' => true, // لجعل الموظف نشطًا بعد الموافقة
            ]);

            // إرسال رسالة WhatsApp
            $auth = User::find($record->user_id)->w_api_token;
            $profileId = User::find($record->user_id)->w_api_profile_id;
            $phone = $record->phone . '@c.us';
            $loginUrl = url('/app/login');
            // $message = "تمت الموافقة على طلبك. يمكنك تسجيل الدخول عبر الرابط التالي:\n";
            // $message .= "$loginUrl\n";
            // $message .= "البريد الإلكتروني: {$record->email}\n";
            // $message .= "كلمة المرور: $password\n";
            // $message = urlencode($message);
            $userName = User::find($record->user_id)->name;
            $message = "تمت الموافقة على طلبك بواسطة $userName.  \n";
$message .= "رابط تسجيل الدخول: $loginUrl \n";
$message .= "البريد الإلكتروني: $record->email \n";
$message .= "كلمة المرور: $password";

$message = str_replace("\n", "\\n", $message);
// dd($message);
            $responseJson = WhatsAppService::send_with_wapi($auth, $profileId, $phone, $message);
            // dd($responseJson);
            $response = json_decode($responseJson, true);

            // تحقق من حالة الإرسال
            if ($response && $response['status'] === 'done') {
                Notification::make()
                    ->title('Request Approved and Notification Sent')
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Request Approved, but Notification Failed '.$password)
                    ->warning()
                    ->send();
            }
                    // Notification::make()
                    //     ->title('Request Approved')
                    //     ->success()
                    //     ->send();
                })
                ->visible(fn (Emp $record) => $record->request_status === 'pending'),

            Tables\Actions\Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->action(function (Emp $record) {
                    $record->update(['request_status' => 'rejected']);
                    Notification::make()
                        ->title('Request Rejected')
                        ->danger()
                        ->send();
                })
                ->visible(fn (Emp $record) => $record->request_status === 'pending'),
            ])
            ->bulkActions([
                BulkAction::make('export')
                ->label('Export to Excel')
                // ->icon('heroicon-o-document-download')
                ->action(function (Collection $records) {
                    if ($records->isEmpty()) {
                        Notification::make()
                            ->title('No records selected!')
                            ->danger()
                            ->send();
                        return;
                    }

                    // تحويل السجلات المختارة إلى array للتصدير
                    return Excel::download(new EmpsExport($records), 'emps.xlsx');
                }),
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //     ]),
            ])
            ;
    }

    public static function getRelations(): array
    {
        return [

            RelationManagers\ReceivedTasksRelationManager::class,
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmps::route('/'),
            'create' => Pages\CreateEmp::route('/create'),
            'edit' => Pages\EditEmp::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        if(auth()->user()->type=="super admin"){
            return parent::getEloquentQuery();
        }
        return parent::getEloquentQuery()->where('user_id', auth()->user()->id);
    }
}
