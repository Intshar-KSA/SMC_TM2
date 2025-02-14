<?php

namespace App\Filament\Resources;

use App\Enums\DayOffEnum;
use App\Enums\RequestStatusEnum;
use App\Models\Emp;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Exports\EmpsExport;
use App\Filament\Forms\RegisterAccountForm;
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
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Support\Str;

class EmpResource extends Resource
{
    protected static ?string $model = Emp::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 0;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                RegisterAccountForm::getRegisterAccountFormComponent('Primary information'),
                Section::make(__('Other information'))
        ->schema([
            Forms\Components\Select::make('user_id')
            ->relationship('user', 'name', function (Builder $query) {
                return $query->where('type', 'admin');
            })->hidden(auth()->user()->type == 'admin'),
            Forms\Components\TextInput::make('sheet_api_url'),
            Forms\Components\TextInput::make('post_url'),

            Forms\Components\TextInput::make('number_of_hours_per_day')
            ->required()
            ->numeric()
            ->default(8),
        Select::make('day_off')
    ->options(DayOffEnum::options())
    ->multiple()
    ->required()
    ->default([DayOffEnum::Friday->value])
    ->dehydrated(true)
    ->afterStateHydrated(function ($component, $state) {
        if (is_string($state)) {
            $component->state(json_decode($state, true));
        }
    }),
Select::make('request_status')
    ->options(RequestStatusEnum::options())
    ->default(RequestStatusEnum::default())
    ->required()
    ->hiddenOn('create'),


Forms\Components\Toggle::make('is_admin')
->required(),
Forms\Components\Toggle::make('can_show')
->default(false),
Forms\Components\Toggle::make('is_active')
->default(true),


        ])
        ->columnSpan(['lg' => 1]),

            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->numeric()
                    ->sortable(),
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
                    Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                    Tables\Columns\TextColumn::make('request_status')
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
                    ->sortable()->badge() ,

                    Tables\Columns\TextColumn::make('day_off')
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
                ->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
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

    /**
     * Get the translated model label.
     */
    public static function getModelLabel(): string
    {
        $modelClass = static::$model;
        $modelName = class_basename($modelClass);
        return __("{$modelName}");
    }

    /**
     * Get the translated plural model label.
     */
    public static function getPluralModelLabel(): string
    {
        $modelClass = static::$model;
        $modelName = class_basename($modelClass);
        $plural= Str::plural(Str::headline($modelName));
        return  __("{$plural}");
    }
}
