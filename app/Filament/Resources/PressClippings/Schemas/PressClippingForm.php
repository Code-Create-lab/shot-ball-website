<?php

namespace App\Filament\Resources\PressClippings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PressClippingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image_path')
                    ->label('Newspaper clipping')
                    ->image()
                    ->disk('public')
                    ->directory('news')
                    ->visibility('public')
                    ->imageEditor()
                    ->maxSize(4096)
                    ->required()
                    ->helperText('JPG, PNG or WebP up to 4 MB.'),
                TextInput::make('caption')
                    ->label('Caption (optional)')
                    ->helperText('Used for the image alt text / accessibility.')
                    ->maxLength(255),
                TextInput::make('sort_order')
                    ->label('Display order')
                    ->helperText('Lower numbers appear first.')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->required(),
                Toggle::make('is_active')
                    ->label('Visible on website')
                    ->default(true),
            ]);
    }
}
