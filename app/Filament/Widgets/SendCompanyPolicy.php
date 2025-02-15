<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Services\WhatsAppService;
use App\Services\NotificationService;

class SendCompanyPolicy extends Widget
{
    protected static string $view = 'filament.widgets.send-company-policy';

    public function sendCompanyPolicy(): ?string
    {
        $emp = auth()->user();
        $company_policy = preg_replace('/\r\n|\r|\n/', " \\n ", $emp->company_policy);
        $auth = $emp->w_api_token;
        $profileId = $emp->w_api_profile_id;

        if (!empty($emp->work_group)) {
            $genral_group = $emp->work_group;
            $response = WhatsAppService::send_with_wapi($auth, $profileId, $genral_group, $company_policy);

            // Use NotificationService for cleaner notifications
            if ($response) {
                NotificationService::send('Success', 'The company policy was sent successfully', 'success');
            } else {
                NotificationService::send('Error', 'Failed to send the company policy', 'danger');
            }
        } else {
            NotificationService::send('Warning', 'No work group found for this user', 'warning');
        }

        return "";
    }
}
