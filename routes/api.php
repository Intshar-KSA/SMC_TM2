<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmpController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SendMassegController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskFollowUpController;
use App\Http\Controllers\TaskStatusController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::post('/tokens/create', function (Request $request) {
//     $token = $request->user()->createToken($request->token_name);

//     return ['token' => $token->plainTextToken];
// });
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'getUser']);
    Route::post('/tokens/create', [AuthController::class, 'createToken']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/subscriptions', [SubscriptionController::class, 'index']);
    Route::post('/subscriptions', [SubscriptionController::class, 'store']);
    Route::get('/subscriptions/{id}', [SubscriptionController::class, 'show']);
    Route::put('/subscriptions/{id}', [SubscriptionController::class, 'update']);
    Route::delete('/subscriptions/{id}', [SubscriptionController::class, 'destroy']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::prefix('projects')->group(function () {
    Route::get('/', [ProjectController::class, 'index']);
    Route::post('/', [ProjectController::class, 'store']);
    Route::get('/{id}', [ProjectController::class, 'show']);
    Route::put('/{id}', [ProjectController::class, 'update']);
    Route::delete('/{id}', [ProjectController::class, 'destroy']);
});

Route::prefix('tasks')->group(function () {
    Route::get('/', [TaskController::class, 'index']);
    Route::post('/', [TaskController::class, 'store']);
    Route::get('/{id}', [TaskController::class, 'show']);
    Route::put('/{id}', [TaskController::class, 'update']);
    Route::delete('/{id}', [TaskController::class, 'destroy']);

});
Route::post('/tasks/recurring', [TaskController::class, 'createRecurringTask']);
Route::put('/tasks/recurring/{task}', [TaskController::class, 'updateRecurringTask']);
Route::get('/projects/{projectId}/recurring-tasks', [TaskController::class, 'recurringTasksByProject']);

Route::get('/projects/{projectId}/tasks', [TaskController::class, 'getProjectTasks']);
Route::get('/projects/{projectId}/tasks_with_emp', [TaskController::class, 'tasksByProjectwithEmp']);
Route::get('/projects/{projectId}/tasks_emp_lastfollowup', [TaskController::class, 'tasksByProjectwithEmpـlastfollowup']);

Route::get('/task-follow-ups', [TaskFollowUpController::class, 'index']);
Route::get('/task-follow-ups/{id}', [TaskFollowUpController::class, 'show']);
Route::post('/task-follow-ups', [TaskFollowUpController::class, 'store']);
Route::put('/task-follow-ups/{id}', [TaskFollowUpController::class, 'update']);
Route::delete('/task-follow-ups/{id}', [TaskFollowUpController::class, 'delete']);
Route::get('/tasks/{taskId}/task-follow-ups', [TaskFollowUpController::class, 'getTaskFollowUps']);

Route::get('/emps', [EmpController::class, 'index']);
Route::get('/emps/{id}', [EmpController::class, 'show']);
Route::post('/emps', [EmpController::class, 'store']);
Route::put('/emps/{id}', [EmpController::class, 'update']);
Route::delete('/emps/{id}', [EmpController::class, 'delete']);
Route::get('/users/{userId}/employees', [EmpController::class, 'getUserEmployees']);

Route::get('/users/{userId}/projects', [ProjectController::class, 'getUserProjects']);

// عرض جميع حالات المهام
Route::get('/task-statuses', [TaskStatusController::class, 'index']);

// عرض حالة مهمة معينة
Route::get('/task-statuses/{id}', [TaskStatusController::class, 'show']);

// إنشاء حالة مهمة جديدة
Route::post('/task-statuses', [TaskStatusController::class, 'store']);
Route::get('/task-status/user/{user_id}', [TaskStatusController::class, 'getByUserId']);

// تحديث حالة مهمة
Route::put('/task-statuses/{id}', [TaskStatusController::class, 'update']);

// حذف حالة مهمة
Route::delete('/task-statuses/{id}', [TaskStatusController::class, 'destroy']);

Route::post('/run-task-report', [SendMassegController::class, 'generateEmployeeReport']);
