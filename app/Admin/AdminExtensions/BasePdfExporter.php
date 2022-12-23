<?php

namespace App\Admin\AdminExtensions;

// use Barryvdh\DomPDF\Facade as PDF;
use Barryvdh\DomPDF\Facade\Pdf;
use Encore\Admin\Grid\Exporters\AbstractExporter;

abstract class BasePdfExporter extends AbstractExporter
{
    // use Exportable;

    protected $title;

    protected $headings = [];

    protected $fileName;

    protected $data;

    protected $view = 'pdf.global_exporter';

    protected $columns = [];

    protected $width;

    public function export()
    {
        $no = 1;
        $width = $this->width;
        $headings = $this->headings;
        $data = $this->data;
        $title = $this->title;
        $pdf = Pdf::setPaper('a4', 'landscape')->loadView($this->view, compact(['headings', 'data', 'no', 'title', 'width']));
        return $pdf->download($this->fileName)->prepare(request())->send();
        exit;
    }
}
