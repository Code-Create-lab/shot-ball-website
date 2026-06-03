<?php

namespace App\Livewire;

use App\Jobs\SendRegistrationEmails;
use App\Models\Registration;
use Illuminate\Support\Facades\URL;
use Livewire\Component;
use Livewire\WithFileUploads;

class RegistrationForm extends Component
{
    use WithFileUploads;

    // Registration type
    public string $registration_type = '';
    public string $event_type = '';

    // Personal details
    public string $first_name = '';
    public string $middle_name = '';
    public string $last_name = '';
    public string $dob = '';
    public string $father_name = '';
    public string $mother_name = '';

    // Contact details
    public string $address = '';
    public string $village_city = '';
    public string $state = 'Bihar';
    public string $district = '';
    public string $club1 = '';
    public string $club2 = '';
    public string $pincode = '';
    public string $country = 'India';

    // Identity & access
    public string $aadhaar = '';
    public string $mobile = '';
    public string $email = '';

    // Uploads
    public $photo;
    public $signature;

    // Agreement
    public bool $terms = false;

    // UI state
    public bool $submitted = false;

    // Signed certificate download URL shown on the success screen.
    public string $certificateUrl = '';

    public array $districts = [
        'Araria', 'Arwal', 'Aurangabad', 'Banka', 'Begusarai', 'Bhagalpur', 'Bhojpur', 'Buxar', 'Darbhanga',
        'East Champaran (Motihari)', 'Gaya', 'Gopalganj', 'Jamui', 'Jehanabad', 'Kaimur (Bhabua)', 'Katihar',
        'Khagaria', 'Kishanganj', 'Lakhisarai', 'Madhepura', 'Madhubani', 'Munger', 'Muzaffarpur', 'Nalanda',
        'Nawada', 'Patna', 'Purnia', 'Rohtas', 'Saharsa', 'Samastipur', 'Saran', 'Sheikhpura', 'Sheohar',
        'Sitamarhi', 'Siwan', 'Supaul', 'Vaishali', 'West Champaran (Bettiah)',
    ];

    protected function rules(): array
    {
        return [
            'registration_type' => 'required|in:Men,Women,Boy,Girl',
            'event_type'        => 'required|in:Senior,Junior,Sub-Junior',
            'first_name'        => 'required|string|min:3|max:255',
            'middle_name'       => 'nullable|string|max:255',
            'last_name'         => 'required|string|min:3|max:255',
            'dob'               => 'required|date|before:today',
            'father_name'       => 'required|string|min:3|max:255',
            'mother_name'       => 'required|string|min:3|max:255',
            'address'           => 'required|string|max:500',
            'village_city'      => 'required|regex:/^[A-Za-z\s]+$/|max:255',
            'state'             => 'required|string|max:255',
            'district'          => 'required|in:' . implode(',', $this->districts),
            'club1'             => 'required|string|max:255',
            'club2'             => 'nullable|string|max:255',
            'pincode'           => 'required|digits:6',
            'country'           => 'required|string|max:255',
            'aadhaar'           => 'required|digits:12',
            'mobile'            => 'required|digits:10',
            'email'             => 'required|email|max:255',
            'photo'             => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'signature'         => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'terms'             => 'accepted',
        ];
    }

    protected function messages(): array
    {
        return [
            'village_city.regex' => 'The village / city may only contain letters and spaces.',
            'terms.accepted'     => 'You must agree to the terms and conditions.',
            'photo.max'          => 'The photograph may not be larger than 2MB.',
            'signature.max'      => 'The signature may not be larger than 2MB.',
        ];
    }

    /**
     * Real-time validation: validate each field as the user leaves it.
     */
    public function updated($property): void
    {
        $this->validateOnly($property);
    }

    public function submit()
    {
        $validated = $this->validate();

        $validated['photo_path']     = $this->photo->store('registrations/photos', 'public');
        $validated['signature_path'] = $this->signature->store('registrations/signatures', 'public');

        unset($validated['photo'], $validated['signature'], $validated['terms']);

        $registration = Registration::create($validated);

        // Queue confirmation (user) + notification (admin) emails.
        SendRegistrationEmails::dispatch($registration);

        // Signed, time-limited download link (24h) for the certificate.
        $certificateUrl = URL::temporarySignedRoute(
            'registration.certificate',
            now()->addDay(),
            $registration
        );

        $this->reset();
        $this->state = 'Bihar';
        $this->country = 'India';
        $this->submitted = true;
        $this->certificateUrl = $certificateUrl;

        $this->dispatch('registration-saved');
    }

    public function render()
    {
        return view('livewire.registration-form');
    }
}
