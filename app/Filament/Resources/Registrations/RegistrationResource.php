<?php

namespace App\Filament\Resources\Registrations;

use App\Filament\Resources\Registrations\Pages\CreateRegistration;
use App\Filament\Resources\Registrations\Pages\EditRegistration;
use App\Filament\Resources\Registrations\Pages\ListRegistrations;
use App\Filament\Resources\Registrations\Schemas\RegistrationForm;
use App\Filament\Resources\Registrations\Tables\RegistrationsTable;
use App\Models\Registration;
use BackedEnum;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RegistrationResource extends Resource
{
    protected static ?string $model = Registration::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $recordTitleAttribute = 'registration_id';

    protected static ?string $navigationLabel = 'Registrations';

    public static function form(Schema $schema): Schema
    {
        return RegistrationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Registration')
                ->columns(3)
                ->schema([
                    TextEntry::make('registration_id')->label('Reg ID')->weight('bold')->copyable(),
                    TextEntry::make('registration_type')->label('Type')->badge(),
                    TextEntry::make('event_type')->label('Event')->badge(),
                ]),
            Section::make('Personal details')
                ->columns(3)
                ->schema([
                    TextEntry::make('first_name'),
                    TextEntry::make('middle_name')->placeholder('—'),
                    TextEntry::make('last_name'),
                    TextEntry::make('dob')->label('Date of birth')->date('d M Y'),
                    TextEntry::make('father_name')->label("Father's name"),
                    TextEntry::make('mother_name')->label("Mother's name"),
                ]),
            Section::make('Contact')
                ->columns(3)
                ->schema([
                    TextEntry::make('mobile')->icon('heroicon-m-phone'),
                    TextEntry::make('email')->icon('heroicon-m-envelope')->copyable(),
                    TextEntry::make('aadhaar')->label('Aadhaar'),
                    TextEntry::make('address')->columnSpanFull(),
                    TextEntry::make('village_city')->label('Village / City'),
                    TextEntry::make('district'),
                    TextEntry::make('state'),
                    TextEntry::make('pincode'),
                    TextEntry::make('club1')->label('Club 1'),
                    TextEntry::make('club2')->label('Club 2')->placeholder('—'),
                ]),
            Section::make('Documents')
                ->columns(2)
                ->schema([
                    ImageEntry::make('photo_path')->label('Photograph')->disk('public')->height(180),
                    ImageEntry::make('signature_path')->label('Signature')->disk('public')->height(180),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return RegistrationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRegistrations::route('/'),
            'create' => CreateRegistration::route('/create'),
            'edit' => EditRegistration::route('/{record}/edit'),
        ];
    }
}
