<?php

namespace App\Services;

use App\Models\Registration;
use Barryvdh\DomPDF\PDF;

class CertificateGenerator
{
    /**
     * Build the registration certificate PDF for a registration.
     */
    public function make(Registration $registration): PDF
    {
        return \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.certificate', [
            'registration' => $registration,
        ])
            ->setPaper('a4', 'portrait')
            ->setOption([
                'isRemoteEnabled'      => true,
                'isHtml5ParserEnabled' => true,
                'dpi'                  => 96,
            ]);
    }

    /**
     * Suggested download filename, e.g. certificate-GSBAB-12.pdf.
     */
    public function filename(Registration $registration): string
    {
        return 'certificate-' . $registration->ref . '.pdf';
    }
}
