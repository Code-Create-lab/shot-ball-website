<?php

namespace App\Filament\Resources\Players\Schemas;

use App\Models\Player;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PlayerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category')
                    ->label('Category')
                    ->options(Player::CATEGORIES)
                    ->default('national')
                    ->required(),
                TextInput::make('name')
                    ->label('Player name (optional)')
                    ->helperText('Used for the image alt text / accessibility.')
                    ->maxLength(120),
                FileUpload::make('image_path')
                    ->label('Photograph')
                    ->image()
                    ->disk('public')
                    ->directory('players')
                    ->visibility('public')
                    ->imageEditor()
                    ->imageEditorAspectRatios(['1:1', '3:4', '4:3'])
                    ->maxSize(2048)
                    ->required()
                    ->helperText('JPG, PNG or WebP up to 2 MB.'),
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
