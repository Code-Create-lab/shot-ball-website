<?php

namespace App\Filament\Resources\Members\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Full name')
                    ->required()
                    ->maxLength(120),
                Textarea::make('role')
                    ->label('Role / designation')
                    ->helperText('Shown under the name. Press Enter for a line break.')
                    ->required()
                    ->rows(2)
                    ->maxLength(255),
                FileUpload::make('image_path')
                    ->label('Photograph')
                    ->image()
                    ->disk('public')
                    ->directory('members')
                    ->visibility('public')
                    ->imageEditor()
                    ->imageEditorAspectRatios(['1:1', '3:4', '4:3'])
                    ->maxSize(2048)
                    ->required()
                    ->helperText('JPG, PNG or WebP up to 2 MB. Square works best.'),
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
