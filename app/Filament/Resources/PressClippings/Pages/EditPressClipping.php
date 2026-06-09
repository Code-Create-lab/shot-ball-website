<?php

namespace App\Filament\Resources\PressClippings\Pages;

use App\Filament\Resources\PressClippings\PressClippingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPressClipping extends EditRecord
{
    protected static string $resource = PressClippingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
