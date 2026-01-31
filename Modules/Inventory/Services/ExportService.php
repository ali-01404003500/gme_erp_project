<?php

namespace Modules\Inventory\Services;

use Maatwebsite\Excel\Facades\Excel;
use Modules\Inventory\Services\Export\AstmExportService;
use Dompdf\Dompdf;
use Dompdf\Options;
class ExportService

{


    /*
     |--------------------------------------------------------------------------
     | EXPORT DATA
     |--------------------------------------------------------------------------
    */
    public function exportData($data, $file_path, $filename)
    {
// dd($data);
        if (request('export_type') == 'pdf' || request('export_type') == 'export_pdf') {
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true); // Enable PHP inside views

            $dompdf = new Dompdf($options);
            $html = view($file_path . request('export_type'), $data)->render();
            $dompdf->loadHtml($html);

            // Set Paper Size and Orientation
            $dompdf->setPaper('A4', 'portrait');

            $dompdf->render();

            // Output the PDF with a footer
            $dompdf->stream($filename . '.pdf', array("Attachment" => 0));
        }


        if (request('export_type') == 'excel' || request('export_type') == 'export_excel') {

            $data['file_path'] = $file_path;
            $filename = $filename . '.xlsx';

            return Excel::download(new AstmExportService($data), $filename);
        }
    }
}
