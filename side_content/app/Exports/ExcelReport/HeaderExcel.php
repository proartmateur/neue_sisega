<?php

namespace App\Exports\ExcelReport;

class HeaderExcel
{
    private string $type;
    private HeaderObraArray $obras;
    private string $fecha;

    /**
     * @param string $type
     * @param HeaderObraArray $obras
     * @param string $fecha
     */
    public function __construct(string $type, HeaderObraArray $obras, string $fecha)
    {
        $this->type = strtoupper($type);
        $this->obras = $obras;
        $this->fecha = $fecha;
    }


    public function render(): array
    {
        $fecha = $this->fecha;
        $type = $this->type;
        $obras = $this->obras->value();
        $resultado = [
            [$this->type]
        ];
        $summary = [];

        if ($this->obras->count() === 1) {
            $summary = [
                $obras[0]->getName(),
                '',
                $obras[0]->getTotal(),
                '',
                '',
                '',
                strtoupper("$type $fecha")
            ];
        }

        $resultado[] = $summary;
        $resultado[] = ['','','','','',''];
        return $resultado;
    }


}
