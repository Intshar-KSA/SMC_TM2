<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class SendMassegController extends Controller
{
    public function generate(Request $request)
    {

        $user = User::with('projects.tasks.receiver', 'projects.tasks.status')->find($request->input('user_id'));

        if (! $user) {
            return 'المستخدم غير موجود.';
        }

        $report = "====================================\n";
        $report .= "📋 تقرير المهام للمستخدم: *{$user->name}*\n";
        $report .= "====================================\n\n";

        $totalTasks = 0;
        $totalCompletedTasks = 0;

        foreach ($user->projects as $project) {
            $report .= "🚀 مشروع: *{$project->name}*\n";
            $report .= "------------------------------------\n";

            if ($project->tasks->isEmpty()) {
                $report .= "  لا توجد مهام في هذا المشروع.\n";
            } else {
                foreach ($project->tasks as $task) {
                    $receiverName = $task->receiver ? $task->receiver->name : 'غير محدد';
                    $statusName = $task->lastFollowUp ? $task->lastFollowUp->taskStatus->name : 'غير معروف';

                    $report .= "🔹 *المهمة:* {$task->title}\n";
                    $report .= "   *المستلم:* {$receiverName}\n";
                    $report .= "   *الحالة:* {$statusName}\n";
                    $report .= "------------------------------------\n";
                    $totalTasks++;

                    // التحقق من إن كانت المهمة منجزة عبر المتغير is_completely في حالة المهمة
                    if (
                        $task->lastFollowUp &&
                        $task->lastFollowUp->taskStatus &&
                        $task->lastFollowUp->taskStatus->is_completely
                    ) {
                        $totalCompletedTasks++;
                    }
                }
            }
            $report .= "\n";
        }

        // إضافة الملخص في نهاية التقرير
        $report .= "====================================\n";
        // $report .= "📊 ملخص التقرير\n";
        // $report .= "====================================\n";
        // $report .= "إجمالي المشاريع: " . count($user->projects) . "\n";
        $report .= "إجمالي المهام: {$totalTasks}\n";
        $report .= "إجمالي المهام المنجزة: {$totalCompletedTasks}\n";

        $this->sendMessageToGroup($report, $user->id);
    }

    public function generateEmployeeReport(Request $request)
    {
        // جلب المستخدم مع الموظفين
        $user = User::with('emps')->find($request->input('user_id'));
        if (! $user) {
            return 'المستخدم غير موجود.';
        }

        $report = "====================================\n";
        $report .= "📋 تقرير المهام حسب الموظف للمستخدم: *{$user->name}*\n";
        $report .= "====================================\n\n";

        // التكرار على كل موظف
        foreach ($user->emps as $emp) {
            // استرجاع المهام المرسلة إلى الموظف مع تحميل بيانات المشروع والمتابعة الأخيرة وحالة المهمة
            $tasks = $emp->receivedTasks()->with('project', 'lastFollowUp.taskStatus')->get();

            // حساب عدد المهام المنجزة (حيث يكون المتغير is_completely صحيحًا)
            $completedTasksCount = $tasks->filter(function ($task) {
                return $task->lastFollowUp
                    && $task->lastFollowUp->taskStatus
                    && $task->lastFollowUp->taskStatus->is_completely;
            })->count();

            $report .= "👤 الموظف: *{$emp->name}* (المهام المنجزة: {$completedTasksCount})\n";
            $report .= "------------------------------------\n";

            // تجميع المهام بحسب المشروع
            $groupedTasks = $tasks->groupBy(function ($task) {
                return $task->project ? $task->project->id : 'no_project';
            });

            if ($groupedTasks->isEmpty()) {
                $report .= "  لا توجد مهام مرتبطة بهذا الموظف.\n";
            } else {
                foreach ($groupedTasks as $groupKey => $projectTasks) {
                    // الحصول على اسم المشروع من المهمة الأولى إذا كان موجودًا، وإلا نستخدم قيمة افتراضية
                    $projectName = $projectTasks->first()->project ? $projectTasks->first()->project->name : 'مشروع غير محدد';
                    $report .= "🚀 مشروع: *{$projectName}*\n";
                    $report .= "------------------------------------\n";

                    foreach ($projectTasks as $task) {
                        $statusName = $task->lastFollowUp ? $task->lastFollowUp->taskStatus->name : 'غير معروف';
                        $report .= "   - المهمة: {$task->title} (الحالة: {$statusName})\n";
                    }
                    $report .= "\n";
                }
            }
            $report .= "====================================\n\n";
        }

        // إرسال التقرير إلى المجموعة
        $this->sendMessageToGroup($report, $user->id);
    }

    private function sendMessageToGroup($message, $userId = 1)
    {
        $emp = User::find($userId);
        $company_policy = preg_replace('/\r\n|\r|\n/', ' \\n ', $message);
        $auth = $emp->w_api_token;
        $profileId = $emp->w_api_profile_id;

        if (! empty($emp->work_group)) {
            $genral_group = $emp->work_group;
            $response = WhatsAppService::send_with_wapi($auth, $profileId, $genral_group, $company_policy);

            // Use NotificationService for cleaner notifications
            if ($response) {
                \Log::info('The company policy was sent successfully');
                // NotificationService::send('Success', 'The company policy was sent successfully', 'success');
            } else {
                \Log::error('Failed to send the company policy');
                // NotificationService::send('Error', 'Failed to send the company policy', 'danger');
            }
        } else {
            \Log::warning('No work group found for this user');
            // NotificationService::send('Warning', 'No work group found for this user', 'warning');
        }
    }
}
