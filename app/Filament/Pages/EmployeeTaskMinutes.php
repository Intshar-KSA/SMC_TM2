<?php
namespace App\Filament\Pages;

use App\Models\Emp;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;

class EmployeeTaskMinutes extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static string $view = 'filament.pages.employee-task-minutes';
    protected static ?string $navigationGroup = 'Employee Task Summary';
    public $employees;

    public function mount()
    {
        $startDate = request('start_date');
        $endDate = request('end_date');
        $currentUser = auth()->user();

        $query = Emp::where('user_id', $currentUser->id)
        ->with('sentTasks')
            ->withSum(['sentTasks' => function ($query) use ($startDate, $endDate) {
                if ($startDate) {
                    $query->whereDate('created_at', '>=', $startDate);
                }
                if ($endDate) {
                    $query->whereDate('created_at', '<=', $endDate);
                }
            }], 'time_in_minutes')
            ->withSum(['sentTasks' => function ($query) use ($startDate, $endDate) {
                if ($startDate) {
                    $query->whereDate('created_at', '>=', $startDate);
                }
                if ($endDate) {
                    $query->whereDate('created_at', '<=', $endDate);
                }
            }], 'exact_time'); // إضافة جمع exact_time

        $this->employees = $query->get();
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
        $modelName = class_basename($modelClass); // e.g., "TaskFollowUps"

        // Convert to headline case (e.g., "Task Follow Ups")
        $headline = Str::headline($modelName);

        // Convert to lowercase and capitalize the first character of the first word
        $formatted = Str::lower($headline); // e.g., "task follow ups"
        $formatted = Str::ucfirst($formatted); // e.g., "Task follow ups"

        // Pluralize the formatted string
        $plural = Str::plural($formatted); // e.g., "Task follow ups" -> "Task follow ups" (plural)

        return __($plural); // Translate the plural label
    }

    public static function getNavigationLabel(): string
    {
        return __('Employee task summary');
    }


    public function getTitle(): string | Htmlable
    {
        return '';
    }
}
