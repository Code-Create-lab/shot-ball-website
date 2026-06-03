<?php

namespace App\Filament\Resources\Registrations\Tables;

use App\Services\CertificateGenerator;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RegistrationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('registration_id')
                    ->label('Reg ID')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),
                ImageColumn::make('photo_path')
                    ->label('Photo')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(asset('assets/img/favicons/favicon.png')),
                TextColumn::make('first_name')
                    ->label('Name')
                    ->formatStateUsing(fn ($record) => trim("{$record->first_name} {$record->middle_name} {$record->last_name}"))
                    ->searchable(['first_name', 'middle_name', 'last_name'])
                    ->sortable(),
                TextColumn::make('registration_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'Men' => 'info',
                        'Women' => 'danger',
                        'Boy' => 'success',
                        'Girl' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('event_type')
                    ->label('Event')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('district')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mobile')
                    ->searchable()
                    ->icon('heroicon-m-phone'),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Registered')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('registration_type')
                    ->label('Type')
                    ->options([
                        'Men' => 'Men',
                        'Women' => 'Women',
                        'Boy' => 'Boy',
                        'Girl' => 'Girl',
                    ]),
                SelectFilter::make('event_type')
                    ->label('Event')
                    ->options([
                        'Senior' => 'Senior',
                        'Junior' => 'Junior',
                        'Sub-Junior' => 'Sub-Junior',
                    ]),
                SelectFilter::make('district')
                    ->searchable()
                    ->options(fn () => \App\Models\Registration::query()
                        ->distinct()
                        ->orderBy('district')
                        ->pluck('district', 'district')
                        ->all()),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('certificate')
                    ->label('PDF')
                    ->icon('heroicon-m-document-arrow-down')
                    ->color('success')
                    ->action(function ($record) {
                        $generator = app(CertificateGenerator::class);
                        $pdf = $generator->make($record);

                        return response()->streamDownload(
                            fn () => print ($pdf->output()),
                            $generator->filename($record),
                            ['Content-Type' => 'application/pdf'],
                        );
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('certificates')
                        ->label('Download PDFs')
                        ->icon('heroicon-m-document-arrow-down')
                        ->color('success')
                        ->action(function ($records) {
                            $generator = app(CertificateGenerator::class);

                            $zipPath = tempnam(sys_get_temp_dir(), 'certs') . '.zip';
                            $zip = new \ZipArchive();
                            $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

                            foreach ($records as $record) {
                                $zip->addFromString(
                                    $generator->filename($record),
                                    $generator->make($record)->output(),
                                );
                            }
                            $zip->close();

                            return response()
                                ->download($zipPath, 'certificates.zip')
                                ->deleteFileAfterSend();
                        })
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
