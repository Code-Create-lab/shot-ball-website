@extends('emails.layout')

@section('subject', 'New player registration')
@section('preheader', 'New registration: ' . trim($registration->first_name . ' ' . $registration->last_name) . ' — ' . $registration->district)

@section('content')
  <!-- Eyebrow -->
  <table role="presentation" cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td style="background-color:#DBEAFE; border:1px solid #93C5FD; border-radius:999px; padding:6px 14px;">
        <span style="font-family:'Barlow Condensed', Arial, sans-serif; font-size:12px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#1D4ED8;">
          Admin Notification
        </span>
      </td>
    </tr>
  </table>

  <h1 class="gsb-h1"
    style="margin:18px 0 12px 0; font-family:'Barlow Condensed', Arial, sans-serif; font-size:34px; line-height:40px; font-weight:800; color:#0F172A;">
    New player registration
  </h1>

  <p style="margin:0 0 24px 0; font-family:Barlow, Arial, sans-serif; font-size:16px; line-height:26px; color:#1E293B;">
    A new registration was submitted via the website. Reference
    <strong style="color:#0F172A;">{{ $registration->ref }}</strong>.
  </p>

  <!-- Detail card -->
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
    style="background-color:#FFF7E6; border:1px solid #FDE68A; border-radius:12px;">
    <tr>
      <td style="padding:8px 20px 12px 20px;">
        <p style="margin:14px 0 6px 0; font-family:'Barlow Condensed', Arial, sans-serif; font-size:13px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#92400E;">
          Registrant details
        </p>

        @include('emails.partials.row', ['label' => 'Registration ID', 'value' => $registration->ref])
        @include('emails.partials.row', ['label' => 'Registration type', 'value' => $registration->registration_type])
        @include('emails.partials.row', ['label' => 'Event type', 'value' => $registration->event_type])
        @include('emails.partials.row', ['label' => 'Name', 'value' => trim($registration->first_name . ' ' . $registration->middle_name . ' ' . $registration->last_name)])
        @include('emails.partials.row', ['label' => 'Date of birth', 'value' => optional($registration->dob)->format('d M Y')])
        @include('emails.partials.row', ['label' => "Father's name", 'value' => $registration->father_name])
        @include('emails.partials.row', ['label' => "Mother's name", 'value' => $registration->mother_name])
        @include('emails.partials.row', ['label' => 'Address', 'value' => $registration->address])
        @include('emails.partials.row', ['label' => 'Village / City', 'value' => $registration->village_city])
        @include('emails.partials.row', ['label' => 'District', 'value' => $registration->district . ', ' . $registration->state])
        @include('emails.partials.row', ['label' => 'Club 1', 'value' => $registration->club1])
        @include('emails.partials.row', ['label' => 'Club 2', 'value' => $registration->club2])
        @include('emails.partials.row', ['label' => 'Pincode', 'value' => $registration->pincode])
        @include('emails.partials.row', ['label' => 'Aadhaar', 'value' => $registration->aadhaar])
        @include('emails.partials.row', ['label' => 'Mobile', 'value' => $registration->mobile])
        @include('emails.partials.row', ['label' => 'Email', 'value' => $registration->email, 'last' => true])
      </td>
    </tr>
  </table>

  <p style="margin:24px 0 8px 0; font-family:Barlow, Arial, sans-serif; font-size:14px; line-height:22px; color:#78350F;">
    Photo and signature are stored under <code style="color:#0F172A;">storage/app/public/registrations</code>.
  </p>
@endsection
