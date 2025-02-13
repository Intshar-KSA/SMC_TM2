<?php

namespace App\Filament\App\Resources\ProjectAttachmentResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\App\Resources\ProjectAttachmentResource;
use Filament\Infolists\Components\BooleanEntry;




class ViewProjectAttachment extends ViewRecord
{
    protected static string $resource = ProjectAttachmentResource::class;

    // protected  function getInfolist(): array
    // {
    //     return [
    //         Infolists\Components\TextEntry::make('des')
    //             ->label('Description'),
    //         Infolists\Components\TextEntry::make('url')
    //             ->label('URL'),
    //         BooleanEntry::make('is_in_own_drive')
    //             ->label('Is In Own Drive'), // تأكد من استخدام الاسم الصحيح هنا
    //     ];
    // }

}
