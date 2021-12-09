<?php

namespace Views;

use Reports\Report;

/**
 * Description of PDFView
 *
 * @author davido
 */
class ReportView implements View
{
    public Report $report;
    public string $contentType;

    public function __construct(Report $report, string $contentType = 'application/pdf') {
        $this->report = $report;
        $this->contentType = $contentType;
    }

    /** {@inheritDoc} */
    public function render(): string {
        header('Content-Type: ' . $this->contentType);
        $this->report->Details();
        return $this->report->Output('S');
    }

}
