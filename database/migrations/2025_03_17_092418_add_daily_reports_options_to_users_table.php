<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDailyReportsOptionsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('enable_daily_project_report')->default(0)->after('enable_employee_notifications');
            $table->boolean('enable_daily_employee_report')->default(0)->after('enable_daily_project_report');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['enable_daily_project_report', 'enable_daily_employee_report']);
        });
    }
}
