<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
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
