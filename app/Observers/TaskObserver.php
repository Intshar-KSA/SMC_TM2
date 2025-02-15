<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\Emp;
use App\Models\User;
use App\Models\Project;
use App\Services\WhatsAppService;
use Filament\Notifications\Notification;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        // Fetch related data
        $project = Project::find($task->project_id);
        $whatsapp_group_id = $project->whatsapp_group_id;
        $phone_main = Emp::find($task->receiver_id)->phone;
        $reciver = Emp::find($task->receiver_id)->name;
        $sender = "الادارة"; // Default sender name
        $auth = User::find(auth()->id())->w_api_token;
        $profileId = User::find(auth()->id())->w_api_profile_id;
        $phone = $phone_main . '@c.us';
        $time_in_minutes = $task->time_in_minutes;
        $title = $task->title;
        $des = $task->description;
        $des = preg_replace("/\r\n|\r|\n/", ' \\n ', $des);

        // Prepare the WhatsApp message
        $message = "مهمة جديدة بعنوان: $title \\nمسند المهمة: $sender \\n مستلم المهمة: $reciver \\n وصف المهمة: $des \\n الزمن اللازم: $time_in_minutes دقيقة";

        // Send WhatsApp notifications if enabled
        $user = User::find(auth()->id());
        if ($user->enable_whatsapp_notifications) {
            if ($user->enable_employee_notifications) {
                WhatsAppService::send_with_wapi($auth, $profileId, $phone, $message);
            }
            if ($user->work_group != null && $user->work_group != '') {
                $genral_group = $user->work_group;
                WhatsAppService::send_with_wapi($auth, $profileId, $genral_group, $message);
            }
            if ($task->send_to_group && $user->enable_group_notifications) {
                if ($whatsapp_group_id != null && $whatsapp_group_id != '') {
                    WhatsAppService::send_with_wapi($auth, $profileId, $whatsapp_group_id, $message);
                }
            }
        }

        // Send POST request to the receiver's post URL
        $url = Emp::find($task->receiver_id)->post_url;
        $data_temp = [
            'date_and_time' => now()->format('Y/m/d H:i:s'),
            'name' => $title,
            'des' => $des,
            'task_sender_name' => $sender,
            'emp_name' => $reciver,
            'time' => $time_in_minutes,
            'معلومات تنفيذ المهمة' => ''
        ];

        WhatsAppService::sendPostRequest($url, $data_temp);
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        // Handle task update logic if needed
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        // Handle task deletion logic if needed
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        // Handle task restoration logic if needed
    }

    /**
     * Handle the Task "forceDeleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        // Handle task force deletion logic if needed
    }
}
