@php
    use Illuminate\Support\Facades\Storage;

    /**
     * Resolve a stored upload to an absolute filesystem path for dompdf.
     * Returns null when the file is missing (e.g. sample/preview records).
     */
    $resolveUpload = function (?string $path): ?string {
        if (! $path) {
            return null;
        }
        $full = Storage::disk('public')->path($path);
        return is_file($full) ? $full : null;
    };

    $logoPath   = public_path('assets/img/logo.png');
    $logo       = is_file($logoPath) ? $logoPath : null;
    $photo      = $resolveUpload($registration->photo_path ?? null);
    $signature  = $resolveUpload($registration->signature_path ?? null);

    $fullName = trim(implode(' ', array_filter([
        $registration->first_name ?? null,
        $registration->middle_name ?? null,
        $registration->last_name ?? null,
    ])));

    // Age from DOB.
    $age = null;
    if (! empty($registration->dob)) {
        try {
            $age = \Illuminate\Support\Carbon::parse($registration->dob)->age;
        } catch (\Throwable $e) {
            $age = null;
        }
    }

    // Session year, e.g. 2025-2026 (April-March handball season).
    $created = $registration->created_at ?? now();
    $y       = (int) $created->format('Y');
    $start   = (int) $created->format('n') >= 4 ? $y : $y - 1;
    $session = $start . '-' . ($start + 1);

    $addressLine = trim(implode(', ', array_filter([
        $registration->address ?? null,
        $registration->village_city ?? null,
        $registration->district ?? null,
        $registration->state ?? null,
        ! empty($registration->pincode) ? 'Pin-' . $registration->pincode : null,
    ])));

    $clubLine = trim(implode(' / ', array_filter([
        $registration->club1 ?? null,
        $registration->club2 ?? null,
    ])));

    $dobFmt = ! empty($registration->dob)
        ? \Illuminate\Support\Carbon::parse($registration->dob)->format('d-m-Y')
        : '—';
@endphp
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<style>
    /* A4 portrait @96dpi = 794 x 1123px. Margin via .page padding so the
       red frame never touches the sheet edge (prevents right-border clip). */
    @page { size: A4 portrait; margin: 0; }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    /* dompdf renders A4 as 595.28 x 841.89pt; at 96dpi that's 793.7 x 1122.5px.
       dompdf ignores fixed height/overflow for pagination, so we DON'T force a
       page-tall box (that overflowed onto page 2). Instead the frame auto-wraps
       the content and a safe min-height keeps the border near the page bottom
       while staying under the single-page break. */
    html, body { width: 794px; }

    body {
        font-family: "DejaVu Sans", sans-serif;
        color: #1a1a1a;
        font-size: 12px;
    }

    /* Page frame — double red border like the reference.
       dompdf resolves child width against the page, not the parent's padding
       box, so the frame gets an EXPLICIT width + margin:auto to sit inside the
       sheet on all four sides (fixes the right border bleeding off-page). */
    .page {
        position: relative;
        width: 794px;
    }
    .frame-outer {
        width: 760px;              /* 17px gutter each side of the 794px page */
        margin: 16px auto;
        border: 2px solid #b91c1c;
        padding: 4px;
        min-height: 1040px;        /* ~780pt: fills page, stays < 1122px break */
        position: relative;
    }
    .frame-inner {
        border: 2px solid #b91c1c;
        padding: 20px 24px;
        min-height: 1028px;
        position: relative;
        z-index: 2;
    }

    /* Watermark logo — centered, low opacity, behind content */
    .watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 520px;
        height: 520px;
        margin-top: -260px;
        margin-left: -260px;
        opacity: 0.07;
        z-index: 1;
        text-align: center;
    }
    .watermark img { width: 520px; height: auto; }

    /* Header */
    .header { width: 100%; }
    .header td { vertical-align: middle; }
    .header .logo-cell { width: 90px; text-align: center; }
    .header .logo-cell img { width: 78px; height: auto; }
    .title-box {
        border: 2px solid #b91c1c;
        background: #fff;
        text-align: center;
        padding: 10px 8px;
    }
    .title-box h1 {
        font-size: 25px;
        font-weight: 800;
        color: #8a0c14;
        letter-spacing: 0.5px;
        line-height: 1.1;
    }

    .divider {
        border: 0;
        border-top: 2px dotted #7c3aed;
        margin: 12px 0;
    }

    /* ID + type strips */
    .strip { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
    .strip td { padding: 9px 14px; vertical-align: middle; }
    .strip .label {
        font-weight: 800;
        color: #1e3a8a;
        font-size: 16px;
        letter-spacing: 0.5px;
    }
    .strip .value {
        font-weight: 800;
        color: #b91c1c;
        font-size: 14px;
        letter-spacing: 1px;
    }
    .strip-id   { background: #e7f0d8; border: 1px solid #c7d8a8; }
    .strip-type { background: #fde4d0; border: 1px solid #f3c39c; }

    /* Section header bars */
    .section-bar {
        background: #cfe6f5;
        color: #b91c1c;
        font-weight: 800;
        font-size: 16px;
        letter-spacing: 0.5px;
        padding: 7px 14px;
        margin: 14px 0 10px;
    }

    /* Detail rows */
    .details { width: 100%; border-collapse: collapse; }
    .details td { padding: 7px 0; vertical-align: top; }
    .details .k {
        width: 190px;
        font-weight: 700;
        color: #111;
        font-size: 13px;
    }
    .details .sep { width: 14px; color: #111; }
    .details .v {
        color: #1f2937;
        font-size: 13px;
        font-family: "DejaVu Sans Mono", monospace;
    }

    /* Photo box (top-right of personal details) */
    .photo-box {
        width: 118px;
        height: 148px;
        border: 1px solid #9ca3af;
        padding: 4px;
        background: #fff;
        text-align: center;
    }
    .photo-box img { width: 108px; height: 138px; object-fit: cover; }
    .photo-ph {
        width: 108px; height: 138px;
        background: #f3f4f6;
        color: #9ca3af;
        font-size: 10px;
        line-height: 138px;
    }

    /* Signatures */
    .sign { width: 100%; margin-top: 46px; }
    .sign td { width: 50%; vertical-align: bottom; padding: 0 18px; }
    .sign .img-row { height: 56px; vertical-align: bottom; }
    .sign .img-row img { max-height: 52px; max-width: 200px; }
    .sign .line {
        border-top: 1.5px solid #111;
        margin-top: 4px;
        padding-top: 6px;
        font-weight: 800;
        font-size: 13px;
        color: #111;
    }
</style>
</head>
<body>
<div class="page">
    <div class="frame-outer">
        <div class="frame-inner">

            {{-- Watermark --}}
            @if ($logo)
                <div class="watermark"><img src="{{ $logo }}" alt=""></div>
            @endif

            {{-- Header --}}
            <table class="header">
                <tr>
                    <td class="logo-cell">
                        @if ($logo)<img src="{{ $logo }}" alt="logo">@endif
                    </td>
                    <td>
                        <div class="title-box">
                            <h1>GOAL SHOT BALL ASSOCIATION OF BIHAR</h1>
                        </div>
                    </td>
                </tr>
            </table>

            <hr class="divider">

            {{-- Application ID --}}
            <table class="strip">
                <tr class="strip-id">
                    <td class="label" style="width:42%;">APPLICATION ID :</td>
                    <td class="value">{{ $registration->ref }}</td>
                </tr>
            </table>

            {{-- Registration type --}}
            <table class="strip">
                <tr class="strip-type">
                    <td class="label" style="width:42%;">REGISTRATION TYPE :</td>
                    <td class="value">{{ strtoupper($registration->registration_type ?? '') }}</td>
                </tr>
            </table>

            {{-- Personal details --}}
            <div class="section-bar">PERSONAL DETAILS : -</div>

            <table class="details">
                <tr>
                    <td style="width:75%;">
                        <table class="details">
                            <tr><td class="k">Name</td><td class="sep">:</td><td class="v">{{ $fullName }}</td></tr>
                            <tr><td class="k">Father's Name</td><td class="sep">:</td><td class="v">{{ $registration->father_name }}</td></tr>
                            <tr><td class="k">Mother's Name</td><td class="sep">:</td><td class="v">{{ $registration->mother_name }}</td></tr>
                            <tr><td class="k">Date of Birth</td><td class="sep">:</td><td class="v">{{ $dobFmt }}</td></tr>
                            <tr><td class="k">Age</td><td class="sep">:</td><td class="v">{{ $age ?? '—' }}</td></tr>
                            <tr><td class="k">Aadhar Id</td><td class="sep">:</td><td class="v">{{ $registration->aadhaar }}</td></tr>
                            <tr><td class="k">Email</td><td class="sep">:</td><td class="v">{{ $registration->email }}</td></tr>
                            <tr><td class="k">Contact</td><td class="sep">:</td><td class="v">{{ $registration->mobile }}</td></tr>
                        </table>
                    </td>
                    <td style="width:25%; text-align:right; vertical-align:top;">
                        <div class="photo-box">
                            @if ($photo)
                                <img src="{{ $photo }}" alt="photo">
                            @else
                                <div class="photo-ph">Photo</div>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>

            <table class="details">
                <tr><td class="k" style="width:190px;">Address</td><td class="sep" style="width:14px;">:</td><td class="v">{{ $addressLine }}</td></tr>
            </table>

            {{-- Professional details --}}
            <div class="section-bar">PROFESSIONAL DETAILS : -</div>

            <table class="details">
                <tr><td class="k">Profile</td><td class="sep">:</td><td class="v">Player</td></tr>
                <tr><td class="k">Sessional Year</td><td class="sep">:</td><td class="v">{{ $session }}</td></tr>
                <tr><td class="k">Play Level</td><td class="sep">:</td><td class="v">{{ $registration->event_type }}</td></tr>
                <tr><td class="k">District Unit</td><td class="sep">:</td><td class="v">{{ $registration->district }}</td></tr>
                @if ($clubLine)
                    <tr><td class="k">Club</td><td class="sep">:</td><td class="v">{{ $clubLine }}</td></tr>
                @endif
                <tr><td class="k">Country</td><td class="sep">:</td><td class="v">{{ $registration->country ?? 'India' }}</td></tr>
            </table>

            {{-- Signatures --}}
            <table class="sign">
                <tr>
                    <td class="img-row">@if ($signature)<img src="{{ $signature }}" alt="signature">@endif</td>
                    <td class="img-row"></td>
                </tr>
                <tr>
                    <td class="line">Signature of Applicant</td>
                    <td class="line">Signature of District Secretary</td>
                </tr>
            </table>

        </div>
    </div>
</div>
</body>
</html>
