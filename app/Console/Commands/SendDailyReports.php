<?php

namespace App\Console\Commands;

use App\Http\Controllers\SendMassegController;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class SendDailyReports extends Command
{
    // قم بتحديد توقيع الأمر؛ يمكنك تمرير معطيات مثل user_id إذا دعت الحاجة
    protected $signature = 'reports:send';

    // وصف الأمر
    protected $description = 'إرسال تقارير المهام اليومية للمستخدم';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // يمكنك استدعاء الكنترولر مباشرة أو استدعاء خدمة في حال قمت بفصل المنطق
        $controller = new SendMassegController;

        // إنشاء كائن Request لتمرير معطيات مثل user_id (افترض هنا أنه يتم تمرير معرف المستخدم من خلال أمر الـ artisan)
        $request = new Request(['user_id' => 1]);

        // استدعاء الدالتين؛ يمكنك استدعاء واحدة تلو الأخرى أو دمجها في دالة واحدة
        $controller->generate($request);
        $controller->generateEmployeeReport($request);

        $this->info('تم إرسال التقارير بنجاح.');
    }
}
