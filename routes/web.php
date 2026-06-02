<?php

use App\Http\Controllers\RegistrationController;
use App\Mail\RegistrationAdminMail;
use App\Mail\RegistrationConfirmationMail;
use App\Models\Registration;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SystemMonitorController;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::post('/register', [RegistrationController::class, 'store'])->name('register.submit');

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


Route::get('/run-command', function (\Illuminate\Http\Request $request) {
    $controller = app(SystemMonitorController::class);
    $action = $request->query('action');
    return match ($action) {
        'metrics'        => $controller->metrics($request),
        'logs'           => $controller->logs($request),
        'run'            => $controller->runCommand($request),
        'clear_sessions' => $controller->clearSessions($request),
        'debug'          => $controller->debug($request),
        default          => $controller->dashboard($request),
    };
})->withoutMiddleware([
    \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
    \Illuminate\Session\Middleware\StartSession::class,
    \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    // CSRF middleware calls $request->session(); without StartSession above
    // that throws "Session store not set on request". Drop it too — this
    // endpoint is gated by the ADMIN_PANEL_PASSWORD query param, not sessions.
    \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
])->name('system.monitor');

