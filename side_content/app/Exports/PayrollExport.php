<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PayrollExport implements WithMultipleSheets
{

    use Exportable;

    private $data;

    /**
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }


    public function sheets(): array
    {
        return [
            new PyrollGeneralSheet('General', $this->data),
            new PyrollGeneralSheet('Empleados', $this->data),
            new PyrollGeneralSheet('Destajistas', $this->data),
        ];
    }
}
