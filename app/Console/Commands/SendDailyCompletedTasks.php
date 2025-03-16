<?php

namespace App\Console\Commands;

use App\Models\Emp;
use App\Models\Task;
use App\Models\User;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendDailyCompletedTasks extends Command
{
    protected $signature = 'tasks:send-report {user_id}';

    protected $description = 'إرسال تقرير يومي بالمهام المسندة والمنجزة لكل موظف';

    public function handle()
    {
        $userId = $this->argument('user_id'); // معرف المستخدم الذي نريد استخراج بياناته
        $today = Carbon::today(); // تاريخ اليوم

        // جلب جميع الموظفين المرتبطين بمشاريع المستخدم المحدد
        $employees = Emp::whereHas('receivedTasks', function ($query) use ($userId) {
            $query->whereHas('project', function ($subQuery) use ($userId) {
                $subQuery->where('user_id', $userId);
            });
        })->get();

        $message = "====================================\n";
        $message .= "📋 تقرير المهام اليومية\n";
        $message .= "====================================\n\n";

        foreach ($employees as $employee) {

            // الحصول على المهام لكل موظف
            $assignedTasks = Task::where('receiver_id', $employee->id)
                ->whereHas('project', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->whereDate('start_date', $today)
                ->get();

            $completedTasks = Task::where('receiver_id', $employee->id)
                ->whereHas('project', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->whereHas('status', function ($query) {
                    $query->where('is_completely', true);
                })
                ->whereDate('updated_at', $today)
                ->get();

            $message .= "👤 الموظف: **{$employee->name}**\n";
            $message .= "------------------------------------\n";

            // عرض المهام المسندة مع الترقيم
            $message .= "📌 *المهام المسندة اليوم:*\n";
            if ($assignedTasks->count() > 0) {
                $i = 1;
                foreach ($assignedTasks as $task) {
                    $message .= "   {$i}. {$task->title}\n";
                    $i++;
                }
            } else {
                $message .= "   لا يوجد مهام مسندة اليوم.\n";
            }
            $message .= "\n";

            // عرض المهام المكتملة مع الترقيم
            $message .= "✅ *المهام المكتملة اليوم:*\n";
            if ($completedTasks->count() > 0) {
                $i = 1;
                foreach ($completedTasks as $task) {
                    $message .= "   {$i}. {$task->title}\n";
                    $i++;
                }
            } else {
                $message .= "   لا يوجد مهام مكتملة اليوم.\n";
            }
            $message .= "\n====================================\n\n";

        }

        // إرسال الرسالة إلى جروب العمل
        $this->sendMessageToGroup($message, $userId);
        $this->info('تم إرسال التقرير اليومي بنجاح.');
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
