<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PayrollAllProjectsExport implements WithMultipleSheets
{

    use Exportable;

    private array $general;
    private array $destajistas;
    private array $empleados;
    private int $count_projects;

    /**
     * @param array $general
     * @param array $destajistas
     * @param array $empleados
     */
    public function __construct(array $general, array $destajistas, array $empleados, int $count_projects)
    {
        $this->general = $general;
        $this->destajistas = $destajistas;
        $this->empleados = $empleados;
        $this->count_projects = $count_projects;
    }


    public function sheets(): array
    {
        return [
            new PayrollAllProjectsSheet('General', $this->general['render'], $this->general['count'], $this->count_projects),
            new PayrollAllProjectsSheet('Empleados',$this->empleados['render'], $this->empleados['count'], $this->count_projects),
            new PayrollAllProjectsSheet('Destajistas',$this->destajistas['render'], $this->destajistas['count'], $this->count_projects),
        ];
    }
}
