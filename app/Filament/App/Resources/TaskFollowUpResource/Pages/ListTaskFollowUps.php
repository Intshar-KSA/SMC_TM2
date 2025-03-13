<?php

namespace App\Filament\App\Resources\TaskFollowUpResource\Pages;

use Filament\Actions;
use App\Models\TaskStatus;
use App\Services\WhatsAppService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Validation\ValidationException;
use App\Filament\App\Resources\TaskFollowUpResource;
use App\Models\Emp;

class ListTaskFollowUps extends ListRecords
{
    protected static string $resource = TaskFollowUpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->mutateFormDataUsing(function (array $data): array {
                // dd(auth()->guard('emp')->user());
                if (auth()->guard('emp')->user()->is_admin == 0) {

                    $task_status = TaskStatus::where('id', $data['task_status_id'])->first();
                    // dd($task_status);
                    if ($task_status->only_for_admin == true) {
                        Notification::make()
                            ->title('ليس لديك صلاحية')
                            ->danger()
                            ->send();

                        throw ValidationException::withMessages([
                                'task_status_id' => 'ليس لديك صلاحية لتعيين هذا الحالة.',
                        ]);
                    }

                }
                $data['emp_id'] = auth()->guard('emp')->id();
                // // $phone_main = '966571718153';
                // $phone_main= Emp::where('id', $data['receiver_id'])->first()->phone;
                // $auth = '40703bb7812b727ec01c24f2da518c407342559c';
                // $profileId = 'aedd0dc2-8453';
                // $phone = $phone_main.'@c.us';
                // $message = 'test message';


                // $response = WhatsAppService::send_with_wapi($auth, $profileId, $phone, $message);

                // dd($response);

                return $data;
            }),
        ];
    }

    protected function getTableQuery(): ?\Illuminate\Database\Eloquent\Builder
    {
        return parent::getTableQuery()->latest();
    }
}
