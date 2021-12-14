<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PayrollExport implements WithMultipleSheets
{

    use Exportable;

    private array $data;
    private int $general_rows;

    /**
     * @param $data
     */
    public function __construct($data, int $general_rows)
    {
        $this->data = $data;
        $this->general_rows = $general_rows;
    }


    public function sheets(): array
    {
        return [
            new PyrollGeneralSheet('General', $this->data, $this->general_rows),
            new PyrollGeneralSheet('Empleados', $this->data, $this->general_rows),
            new PyrollGeneralSheet('Destajistas', $this->data, $this->general_rows),
        ];
    }
}
