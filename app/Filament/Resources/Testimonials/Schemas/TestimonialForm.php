<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Athlete name')
                    ->required()
                    ->maxLength(120),
                Textarea::make('quote')
                    ->label('Testimonial')
                    ->required()
                    ->rows(5)
                    ->maxLength(1000),
                TextInput::make('avatar')
                    ->label('Avatar initial (optional)')
                    ->helperText('Single letter shown in the badge. Defaults to the first letter of the name.')
                    ->maxLength(2),
                Select::make('rating')
                    ->label('Star rating')
                    ->options([5 => '5', 4 => '4', 3 => '3', 2 => '2', 1 => '1'])
                    ->default(5)
                    ->required(),
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
