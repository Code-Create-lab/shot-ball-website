<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Services\CertificateGenerator;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationController extends Controller
{
    /**
     * Download a registration's certificate PDF.
     * Route is protected by a signed URL so registration ids cannot be
     * enumerated to harvest other applicants' personal data (Aadhaar etc).
     */
    public function certificate(Registration $registration, CertificateGenerator $generator): Response
    {
        $pdf = $generator->make($registration);

        return $pdf->download($generator->filename($registration));
    }

    /**
     * Render the certificate design in the browser for quick iteration.
     * Local environment only. Uses the latest registration or a sample.
     */
    public function certificatePreview(Request $request, CertificateGenerator $generator): Response
    {
        abort_unless(app()->isLocal(), 404);

        $registration = Registration::latest()->first() ?? new Registration([
            'registration_type' => 'Women',
            'event_type'        => 'Senior',
            'first_name'        => 'Payal',
            'last_name'         => 'Kumari',
            'dob'               => '2009-09-13',
            'father_name'       => 'Suraj',
            'mother_name'       => 'Kumar Singh',
            'address'           => 'Kamruddinpur, Ward No-5',
            'village_city'      => 'Begusarai',
            'state'             => 'Bihar',
            'district'          => 'Begusarai',
            'club1'             => 'SOS Begusarai',
            'pincode'           => '851134',
            'country'           => 'India',
            'aadhaar'           => '404605501068',
            'mobile'            => '9934120570',
            'email'             => 'suhanigirl189@gmail.com',
        ]);
        $registration->id ??= 0;

        $pdf = $generator->make($registration);

        // ?download=1 forces a file download; default streams inline in the browser.
        return $request->boolean('download')
            ? $pdf->download($generator->filename($registration))
            : $pdf->stream('certificate-preview.pdf');
    }

    /**
     * Store a new player registration.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'registration_type' => 'required|string|in:Men,Women,Boy,Girl',
            'event_type'        => 'required|string|in:Senior,Junior,Sub-Junior',
            'first_name'        => 'required|string|min:3|max:255',
            'middle_name'       => 'nullable|string|max:255',
            'last_name'         => 'required|string|min:3|max:255',
            'dob'               => 'required|date|before:today',
            'father_name'       => 'required|string|min:3|max:255',
            'mother_name'       => 'required|string|min:3|max:255',
            'address'           => 'required|string|max:500',
            'village_city'      => 'required|string|max:255',
            'state'             => 'nullable|string|max:255',
            'district'          => 'required|string|max:255',
            'club1'             => 'required|string|max:255',
            'club2'             => 'nullable|string|max:255',
            'pincode'           => 'required|digits:6',
            'country'           => 'nullable|string|max:255',
            'aadhaar'           => 'required|digits:12',
            'mobile'            => 'required|digits:10',
            'email'             => 'required|email|max:255',
            'photo'             => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'signature'         => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'terms'             => 'accepted',
        ]);

        // Store uploads on the public disk (php artisan storage:link).
        $validated['photo_path']     = $request->file('photo')->store('registrations/photos', 'public');
        $validated['signature_path'] = $request->file('signature')->store('registrations/signatures', 'public');

        // Defaults for readonly fields.
        $validated['state']   = $validated['state'] ?? 'Bihar';
        $validated['country'] = $validated['country'] ?? 'India';

        // Drop non-column keys before persisting.
        unset($validated['photo'], $validated['signature'], $validated['terms']);

        Registration::create($validated);

        return redirect()
            ->route('home')
            ->with('status', 'Registration submitted successfully.');
    }
}
