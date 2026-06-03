<?php

use App\Http\Controllers\RegistrationController;
use App\Mail\RegistrationAdminMail;
use App\Mail\RegistrationConfirmationMail;
use App\Models\Registration;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::post('/register', [RegistrationController::class, 'store'])->name('register.submit');

// Certificate PDF download (signed URL — prevents id enumeration of personal data).
Route::get('/registration/{registration}/certificate', [RegistrationController::class, 'certificate'])
    ->name('registration.certificate')
    ->middleware('signed');

// Certificate design preview (local only).
Route::get('/certificate/preview', [RegistrationController::class, 'certificatePreview'])
    ->name('certificate.preview');

/*
|--------------------------------------------------------------------------
| Mail template previews (local only)
|--------------------------------------------------------------------------
| Renders the mailables in the browser so templates can be eyeballed
| without dispatching the queue. Uses the latest registration if one
| exists, otherwise a sample record.
*/
Route::get('/mail-preview/{type}', function (string $type) {
    abort_unless(app()->isLocal(), 404);

    $registration = Registration::latest()->first() ?? new Registration([
        'registration_type' => 'Men',
        'event_type'        => 'Senior',
        'first_name'        => 'Aman',
        'middle_name'       => 'Kumar',
        'last_name'         => 'Singh',
        'dob'               => '2002-05-14',
        'father_name'       => 'Ramesh Singh',
        'mother_name'       => 'Sunita Devi',
        'address'           => 'House 12, Ward 5',
        'village_city'      => 'Begusarai',
        'state'             => 'Bihar',
        'district'          => 'Begusarai',
        'club1'             => 'City Sports Club',
        'club2'             => null,
        'pincode'           => '851101',
        'country'           => 'India',
        'aadhaar'           => '123456789012',
        'mobile'            => '9876543210',
        'email'             => 'aman@example.com',
        'photo_path'        => 'registrations/photos/sample.jpg',
        'signature_path'    => 'registrations/signatures/sample.jpg',
    ]);

    $registration->id ??= 0;

    return match ($type) {
        'confirmation' => new RegistrationConfirmationMail($registration),
        'admin'        => new RegistrationAdminMail($registration),
        default        => abort(404),
    };
})->name('mail.preview');
