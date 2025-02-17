<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use App\Models\Task;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\TaskStatus;
use Filament\Tables\Table;
use App\Models\TaskFollowUp;
use Filament\Resources\Resource;
use App\Services\WhatsAppService;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\TaskFollowUpResource\Pages;
use App\Filament\App\Resources\TaskFollowUpResource\RelationManagers;
use App\Helpers\ModelLabelHelper;

class TaskFollowUpResource extends Resource
{
    protected static ?string $model = TaskFollowUp::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        $userId = auth()->guard('emp')->user()->user_id;

        return $form
            ->schema([
                Forms\Components\Select::make('task_id')
                    ->label('Task')
                    ->options(function () use ($userId) {
                        return Task::whereHas('project', function (Builder $query) use ($userId) {
                            $query->where('user_id', $userId);
                        })->pluck('title', 'id');
                    })
                    ->required()
                    ->searchable(),

                Forms\Components\Select::make('task_status_id')
                    ->relationship('taskStatusForUser', 'name')
                    ->required()
                    ->reactive() // لجعل القائمة التفاعلية
                    ->afterStateUpdated(function (callable $set, $state) {
                        $taskStatus = TaskStatus::find($state);
                        $set('is_completed', $taskStatus && $taskStatus->is_completely); // تعيين إذا كانت الحالة مكتملة
                    }),

                Forms\Components\Textarea::make('note')
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('exact_time')
                    ->label('Exact Time (minutes)')
                    ->numeric()
                    ->minValue(0)
                    ->required(fn (callable $get) => $get('is_completed')) // إجبارية الحقل إذا كانت المهمة مكتملة
                    ->hidden(fn (callable $get) => !$get('is_completed')) // إخفاء الحقل إذا لم تكن الحالة مكتملة
                    ->reactive(), // لجعل الحقل يتفاعل مع التغييرات
            ])
            ->statePath('data'); // للتأكد من عدم تأثير الحالة المؤقتة على البيانات
    }




    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('emp_id')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('task_id')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('task_status_id')
                //     ->numeric()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('emp.name')  // Assuming relationship is defined in TaskFollowUp model
                    ->label('Employee')
                    ->sortable(),
                Tables\Columns\TextColumn::make('task.title') // Assuming relationship is defined in TaskFollowUp model
                    ->label('Task Title')
                    ->sortable(),
                    Tables\Columns\TextColumn::make('note') // Assuming relationship is defined in TaskFollowUp model
                    ->label('Note')
                    ->sortable(),
                Tables\Columns\TextColumn::make('taskStatus.name') // Assuming relationship is defined in TaskFollowUp model
                    ->label('Task Status')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                    // ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make()
                // ->mutateFormDataUsing(function (array $data): array {
                //     // dd(auth()->guard('emp')->user());
                //     if (auth()->guard('emp')->user()->is_admin == 0) {

                //         $task_status = TaskStatus::where('id', $data['task_status_id'])->first();
                //         // dd($task_status);
                //         if ($task_status->only_for_admin == true) {
                //             Notification::make()
                //                 ->title('ليس لديك صلاحية')
                //                 ->danger()
                //                 ->send();

                //             throw ValidationException::withMessages([
                //                     'task_status_id' => 'ليس لديك صلاحية لتعيين هذا الحالة.',
                //             ]);
                //         }

                //     }
                //     $data['emp_id'] = auth()->guard('emp')->id();
                //     $phone_main = '966571718153';
                //     $auth = '40703bb7812b727ec01c24f2da518c407342559c';
                //     $profileId = 'aedd0dc2-8453';
                //     $phone = $phone_main.'@c.us';
                //     $message = 'test message';

                //     $response = WhatsAppService::send_with_wapi($auth, $profileId, $phone, $message);



                //     return $data;
                // }),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTaskFollowUps::route('/'),
            'create' => Pages\CreateTaskFollowUp::route('/create'),
            // 'edit' => Pages\EditTaskFollowUp::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $userId = auth()->guard('emp')->user()->user_id;
        // return TaskFollowUp::whereHas('task.emp_project2', function (Builder $query) use ($userId) {
        //     $query->where('user_id', $userId);
        // });
        return TaskFollowUp::whereHas('task', function (Builder $query) use ($userId) {
            $query->whereHas('project', function (Builder $query) use ($userId) {
                $query->where('user_id', $userId);
            });
        });
    }
    public static function getModelLabel(): string
    {
        return ModelLabelHelper::getModelLabel(static::$model);
    }

    public static function getPluralModelLabel(): string
    {
        return ModelLabelHelper::getPluralModelLabel(static::$model);
    }
}
