@extends('emails.layout')

@section('subject', 'Your registration is received')
@section('preheader', 'We have received your Goal Shot Ball Association registration — reference ' . $registration->ref)

@section('content')
  <!-- Eyebrow -->
  <table role="presentation" cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td style="background-color:#FEF3C7; border:1px solid #FDE68A; border-radius:999px; padding:6px 14px;">
        <span style="font-family:'Barlow Condensed', Arial, sans-serif; font-size:12px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#92400E;">
          Registration Received
        </span>
      </td>
    </tr>
  </table>

  <h1 class="gsb-h1"
    style="margin:18px 0 12px 0; font-family:'Barlow Condensed', Arial, sans-serif; font-size:34px; line-height:40px; font-weight:800; color:#0F172A;">
    Thanks, {{ $registration->first_name }}!
  </h1>

  <p style="margin:0 0 24px 0; font-family:Barlow, Arial, sans-serif; font-size:16px; line-height:26px; color:#1E293B;">
    We have received your registration with the <strong>Goal Shot Ball Association of Bihar</strong>.
    Our team will review your details and get in touch shortly. Your reference number is below — keep it handy.
  </p>

  <!-- Reference chip -->
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0 0 24px 0;">
    <tr>
      <td style="background-color:#0F172A; border-radius:12px; padding:16px 20px;">
        <span style="font-family:Barlow, Arial, sans-serif; font-size:12px; letter-spacing:1px; text-transform:uppercase; color:#FCD34D;">
          Reference ID
        </span>
        <br>
        <span style="font-family:'Barlow Condensed', Arial, sans-serif; font-size:24px; font-weight:800; letter-spacing:1px; color:#FFFFFF;">
          {{ $registration->ref }}
        </span>
      </td>
    </tr>
  </table>

  <!-- Summary card -->
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
    style="background-color:#FFF7E6; border:1px solid #FDE68A; border-radius:12px;">
    <tr>
      <td style="padding:8px 20px 12px 20px;">
        <p style="margin:14px 0 6px 0; font-family:'Barlow Condensed', Arial, sans-serif; font-size:13px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#92400E;">
          Submission summary
        </p>

        @include('emails.partials.row', ['label' => 'Registration type', 'value' => $registration->registration_type . ' · ' . $registration->event_type])
        @include('emails.partials.row', ['label' => 'Name', 'value' => trim($registration->first_name . ' ' . $registration->middle_name . ' ' . $registration->last_name)])
        @include('emails.partials.row', ['label' => 'District', 'value' => $registration->district . ', ' . $registration->state])
        @include('emails.partials.row', ['label' => 'Mobile', 'value' => $registration->mobile, 'last' => true])
      </td>
    </tr>
  </table>

  <p style="margin:24px 0 8px 0; font-family:Barlow, Arial, sans-serif; font-size:14px; line-height:22px; color:#78350F;">
    If any detail above is incorrect, simply reply to this email and we will help you fix it.
  </p>
@endsection
