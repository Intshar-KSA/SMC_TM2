<?php

namespace App\Console\Commands;

use App\Http\Controllers\SendMassegController;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class SendDailyReports extends Command
{
    protected $signature = 'reports:send';

    protected $description = 'إرسال تقارير المهام اليومية لجميع المستخدمين الذين لديهم التقارير المفعلة';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // استدعاء الكنترولر الذي يحتوي على دوال التقارير
        $controller = new SendMassegController;

        // الحصول على جميع المستخدمين
        $users = User::all();

        foreach ($users as $user) {
            // التحقق مما إذا كان أي من تقارير اليوم مفعلة للمستخدم
            if ($user->enable_daily_project_report || $user->enable_daily_employee_report) {
                $this->info("معالجة التقارير للمستخدم: {$user->id}");

                // إنشاء Request جديد مع user_id الخاص بالمستخدم الحالي
                $request = new Request(['user_id' => $user->id]);

                // إذا كان تقرير المشاريع مفعل
                if ($user->enable_daily_project_report) {
                    $controller->generate($request);
                    $this->info("تم إرسال تقرير المشاريع للمستخدم: {$user->id}");
                }

                // إذا كان تقرير الموظفين مفعل
                if ($user->enable_daily_employee_report) {
                    $controller->generateEmployeeReport($request);
                    $this->info("تم إرسال تقرير الموظفين للمستخدم: {$user->id}");
                }
            } else {
                $this->info("لا توجد تقارير مفعلة للمستخدم: {$user->id}");
            }
        }

        $this->info('تم إرسال جميع التقارير بنجاح.');
    }
}
