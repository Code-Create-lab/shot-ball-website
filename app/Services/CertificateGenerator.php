<?php

namespace App\Services;

use App\Models\Registration;
use Barryvdh\DomPDF\PDF;
use Dompdf\Dompdf;

class CertificateGenerator
{
    /**
     * Build the registration certificate PDF for a registration.
     *
     * Builds the Barryvdh wrapper directly instead of resolving the
     * `dompdf.wrapper` container binding / Pdf facade. This works even when
     * the package service provider was never auto-discovered (e.g. a server
     * where Composer cannot run and the vendor folder is copied manually).
     */
    public function make(Registration $registration): PDF
    {
        $options = (array) config('dompdf.options', []);
        $dompdf  = new Dompdf($options);

        $basePath = realpath(public_path());
        if ($basePath !== false) {
            $dompdf->setBasePath($basePath);
        }

        $pdf = new PDF($dompdf, app('config'), app('files'), app('view'));

        return $pdf->loadView('pdf.certificate', [
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
