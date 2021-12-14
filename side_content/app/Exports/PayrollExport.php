<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PayrollExport implements WithMultipleSheets
{

    use Exportable;

    private array $general;
    private array $destajistas;
    private array $empleados;

    /**
     * @param array $general
     * @param array $destajistas
     * @param array $empleados
     */
    public function __construct(array $general, array $destajistas, array $empleados)
    {
        $this->general = $general;
        $this->destajistas = $destajistas;
        $this->empleados = $empleados;
    }


    public function sheets(): array
    {
        return [
            new PyrollGeneralSheet('General', $this->general['render'], $this->general['count']),
            new PyrollGeneralSheet('Empleados',$this->empleados['render'], $this->empleados['count']),
            new PyrollGeneralSheet('Destajistas',$this->destajistas['render'], $this->destajistas['count']),
        ];
    }
}
