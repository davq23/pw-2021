<?php

namespace Reports;

use TFPDF\tFPDF;

/**
 * Description of TestReport
 *
 * @author davido
 */
class TestReport extends tFPDF implements Report
{
    private string $message;

    public function __construct(string $message, $orientation = 'P', $unit = 'mm', $size = 'A4') {
        parent::__construct($orientation, $unit, $size);

        $this->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true);
        $this->SetFont('DejaVu', '', 14);

        $this->message = $message;
    }

    //put your code here
    public function Details() {
        $this->AddPage();
        $this->Cell(50, 100, $this->message, 1);
    }

}
