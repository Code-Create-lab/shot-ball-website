<?php

namespace App\Filament\Resources\Registrations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RegistrationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('registration_id')
                    ->default(null),
                TextInput::make('registration_type')
                    ->required(),
                TextInput::make('event_type')
                    ->required(),
                TextInput::make('first_name')
                    ->required(),
                TextInput::make('middle_name')
                    ->default(null),
                TextInput::make('last_name')
                    ->required(),
                DatePicker::make('dob')
                    ->required(),
                TextInput::make('father_name')
                    ->required(),
                TextInput::make('mother_name')
                    ->required(),
                TextInput::make('address')
                    ->required(),
                TextInput::make('village_city')
                    ->required(),
                TextInput::make('state')
                    ->required()
                    ->default('Bihar'),
                TextInput::make('district')
                    ->required(),
                TextInput::make('club1')
                    ->required(),
                TextInput::make('club2')
                    ->default(null),
                TextInput::make('pincode')
                    ->required(),
                TextInput::make('country')
                    ->required()
                    ->default('India'),
                TextInput::make('aadhaar')
                    ->required(),
                TextInput::make('mobile')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('photo_path')
                    ->required(),
                TextInput::make('signature_path')
                    ->required(),
            ]);
    }
}
