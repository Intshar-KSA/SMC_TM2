<?php
namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class UserInvitationWidget extends Widget
{
    protected static string $view = 'filament.widgets.user-invitation-widget';

    public static function canView(): bool
    {
        // عرض الويدجت فقط إذا كان المستخدم مسجلاً الدخول
        return auth()->user()->isAdmin();
    }

    public function getUserInvitationLink(): string
    {
        // إنشاء رابط الدعوة باستخدام user_id الخاص بالمستخدم الحالي
        return url('/request-add-employee/' . auth()->user()->id);
    }
}
