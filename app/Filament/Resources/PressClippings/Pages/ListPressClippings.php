<?php

namespace App\Filament\Resources\PressClippings\Pages;

use App\Filament\Resources\PressClippings\PressClippingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPressClippings extends ListRecords
{
    protected static string $resource = PressClippingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
