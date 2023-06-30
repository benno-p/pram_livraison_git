<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

// use Maatwebsite\Excel\Concerns\Exportable;
// use Illuminate\Contracts\Queue\ShouldQueue;

class ExcelExport implements FromView
// , ShouldAutoSize , ShouldQueue
{

    // use Exportable;

    public $view;
    public $data;
    public $options;

    public function __construct($view, $data = "", $options = "")
    {
        $this->view = $view;
        $this->data = $data;
        $this->options = $options;
    }

    public function view(): View
    {
        return view(
            'exports.excel.'.$this->view,
            [
                'data' => $this->data,
                'options' => $this->options,
            ]
        );
    }
}
