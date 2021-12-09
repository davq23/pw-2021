<?php

namespace Reports;

use Domains\Exam;
use Domains\Sample;
use TFPDF\tFPDF;

/**
 * Description of ExamReport
 *
 * @author davido
 */
class ExamReport extends tFPDF implements Report
{
    protected Exam $exam;

    public function __construct(
        Exam $exam,
        $orientation = 'P',
        $unit = 'mm',
        $size = 'A4'
    ) {
        parent::__construct($orientation, $unit, $size);

        $this->exam = $exam;
    }

    public function Header() {
        parent::Header();
    }

    public function Footer() {
        parent::Footer();
    }

    public function Details() {
        $this->AddPage();

        $this->Cell(160, 25, 'DESCRIPTION');
        $this->MultiCell(160, 25, $this->exam->getDescription());
        $this->Cell(160, 25, 'RESULTS');
        $this->MultiCell(160, 25, $this->exam->getResults());
    }

    public function Output($dest = '', $name = '', $isUTF8 = false) {
        $this->Details();
        parent::Output($dest, $name, $isUTF8);
    }

}
