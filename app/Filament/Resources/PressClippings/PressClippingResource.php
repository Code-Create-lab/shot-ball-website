<?php

namespace App\Filament\Resources\PressClippings;

use App\Filament\Resources\PressClippings\Pages\CreatePressClipping;
use App\Filament\Resources\PressClippings\Pages\EditPressClipping;
use App\Filament\Resources\PressClippings\Pages\ListPressClippings;
use App\Filament\Resources\PressClippings\Schemas\PressClippingForm;
use App\Filament\Resources\PressClippings\Tables\PressClippingsTable;
use App\Models\PressClipping;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PressClippingResource extends Resource
{
    protected static ?string $model = PressClipping::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static ?string $recordTitleAttribute = 'caption';

    protected static ?string $navigationLabel = 'Press Clippings';

    public static function form(Schema $schema): Schema
    {
        return PressClippingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PressClippingsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListPressClippings::route('/'),
            'create' => CreatePressClipping::route('/create'),
            'edit'   => EditPressClipping::route('/{record}/edit'),
        ];
    }
}
