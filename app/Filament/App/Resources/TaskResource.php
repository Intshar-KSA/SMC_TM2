<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use App\Models\Task;
use App\Models\User;
use Filament\Tables;
use App\Models\Project;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Exports\TasksExport;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;

use Filament\Tables\Filters\Filter;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\App\Resources\TaskResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\TaskResource\RelationManagers\TaskFollowUpsRelationManager;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?int $navigationSort = 2;


    public static function form(Form $form): Form
    {
        $userId = auth()->guard('emp')->user()->user_id;
        return $form
            ->schema([
                Forms\Components\Select::make('project_id')
                    ->relationship('emp_project', 'name')
                    ->label('Project Name')
                    ->searchable()
                    ->preload(),
                    // Select::make('project_id')
                    // ->relationship('emp_project', 'name')
                    // ->required()
                    // ->label('Project'),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),

                // Forms\Components\Select::make('sender_id')
                //     ->relationship('task_emp_app', 'name')
                //     ->required()
                //     ->label('Sender Name'),
                Forms\Components\Select::make('receiver_id')
                    ->relationship('task_emp_app', 'name')
                    ->required()
                    ->label('Receiver Name'),
                Forms\Components\TextInput::make('time_in_minutes')
                    ->numeric()
                    ->default(null),
                    Forms\Components\TextInput::make('exact_time')
                    ->numeric()
                    ->default(0),
                    // Forms\Components\Select::make('parent_id')
                    // ->label('Parent Task')
                    // ->options(function () use ($userId) {
                    //     return Task::whereHas('project', function (Builder $query) use ($userId) {
                    //         $query->where('user_id', $userId);
                    //     })->pluck('title', 'id');
                    // }),
                Forms\Components\Toggle::make('is_recurring')
                    ->required()
                    ->reactive(),
                // Forms\Components\TextInput::make('recurrence_interval_days')
                //     ->numeric()
                //     ->default(null)
                //     ->required(fn ($get) => $get('is_recurring')),
                    Forms\Components\Toggle::make('send_to_group')
                    ->label('send to client group'),
                    // ->visible(fn ($get) => $get('is_recurring')),
            ]);
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('title'),
                Infolists\Components\TextEntry::make('description')
                    ->columnSpan('full'),
                Infolists\Components\TextEntry::make('sender_id'),
                Infolists\Components\TextEntry::make('receiver_id'),
                Infolists\Components\TextEntry::make('time_in_minutes')
                    ->numeric(),
                Infolists\Components\TextEntry::make('start_date')
                    ->dateTime(),
                Infolists\Components\IconEntry::make('is_recurring'),
                Infolists\Components\TextEntry::make('recurrence_interval_days')
                    ->numeric(),
                Infolists\Components\TextEntry::make('next_occurrence')
                    ->dateTime(),
                Infolists\Components\TextEntry::make('parentTask.title'),

                Infolists\Components\TextEntry::make('created_at')
                    ->dateTime(),
                Infolists\Components\TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('projectOwner.name')
                ->label('User Name')
                ->sortable(),
            Tables\Columns\TextColumn::make('title')
                ->searchable(),
            Tables\Columns\TextColumn::make('sender.name')
                ->label('Sender Name') // Optional label
                ->sortable(),
            Tables\Columns\TextColumn::make('receiver.name')
                ->label('Receiver Name') // Optional label
                ->sortable(),
                Tables\Columns\TextColumn::make('lastFollowUp.taskStatus.name')
                ->label('Last Follow Up') // Optional label
                ->sortable(),
            // Tables\Columns\TextColumn::make('sender_id')
            // ->relationship('task_emp', 'name')
            //     ->sortable(),
            // Tables\Columns\TextColumn::make('receiver_id')
            //     ->numeric()
            //     ->sortable(),
            Tables\Columns\TextColumn::make('time_in_minutes')
                ->numeric()
                ->sortable(),
                Tables\Columns\TextColumn::make('exact_time')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('start_date')
                ->dateTime()
                ->sortable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\IconColumn::make('is_recurring')
                ->boolean(),
            Tables\Columns\TextColumn::make('recurrence_interval_days')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('next_occurrence')
                ->dateTime()
                ->sortable(),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
                    Filter::make('receiver_id')
                    ->label('Receiver')
                    ->form([
                        Select::make('receiver')
                            ->label('Receiver Name')
                            ->options(function () {
                                $userId = auth()->guard('emp')->user()->user_id; // الحصول على user_id الحالي
                                return \App\Models\Emp::where('user_id', $userId) // جلب الموظفين التابعين للمستخدم الحالي
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->searchable(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['receiver'],
                            fn (Builder $query, $receiver): Builder => $query->where('receiver_id', $receiver)
                        );
                    }),
                    Filter::make('project_id')
                    ->label('Project Name')
                    ->form([
                        Select::make('project')
                            ->label('Project Name')
                            ->options(function () {
                                $userId = auth()->guard('emp')->user()->user_id; // الحصول على user_id الحالي
                                return \App\Models\Project::where('user_id', $userId) // جلب الموظفين التابعين للمستخدم الحالي
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->searchable(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['project'],
                            fn (Builder $query, $receiver): Builder => $query->where('project_id', $receiver)
                        );
                    }),

                Filter::make('مهامي')
                    ->label('مهامي')
                    ->query(function (Builder $query): Builder {
                        $userId = auth()->id();
                        return $query->where(function($q) use ($userId) {
                            $q->where('receiver_id', $userId);
                            //   ->orWhere('receiver_id', $userId);
                        });
                    }),

            ],layout: FiltersLayout::AboveContentCollapsible)
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\Action::make('clone')
                // ->label('استنساج')
                // // ->icon('heroicon-o-duplicate')
                // ->action(function ($record, $livewire) {
                //     // نسخ السجل الأصلي
                //     $newTask = $record->replicate();
                //     $newTask->title .= ' (نسخة)';
                //     $newTask->save(); // حفظ السجل الجديد

                //     // إعادة توجيه المستخدم إلى صفحة تعديل السجل الجديد
                //     return redirect()->route('filament.resources.tasks.edit', $newTask);
                // })
                // ->requiresConfirmation(), // إضافة تأكيد قبل الاستنساج

            ])
            ->bulkActions([
                BulkAction::make('export')
                ->label('تصدير إلى Excel')
                // ->icon('heroicon-o-document-download')
                ->action(function (Collection $records) {
                    // تصدير السجلات المختارة إلى ملف Excel
                    return Excel::download(new TasksExport($records), 'tasks.xlsx');
                }),
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TaskFollowUpsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
            'view' => Pages\ViewTask::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // $userId = auth()->guard('emp')->user()->user_id;

        // $currentEmp = auth()->guard('emp')->user();
        // if ($currentEmp && !$currentEmp->can_show) {
        //     // إذا كانت can_show هي false، عرض المهام الخاصة فقط بالموظف
        //     $userId2 = auth()->id();

        //     return parent::getEloquentQuery()->where(function($q) use ($userId2) {
        //         $q->where('receiver_id', $userId2);
        //         //   ->orWhere('receiver_id', $userId);
        //     });

        //     // return parent::getEloquentQuery()
        //     //     ->where('receiver_id', $currentEmp->id);
        // }

        // return parent::getEloquentQuery()
        //     ->whereHas('user_project_app', function (Builder $query) use ($userId) {
        //         $query->where('user_id', $userId);
        //     })
        return parent::getEloquentQuery()
            ;
    }
}
