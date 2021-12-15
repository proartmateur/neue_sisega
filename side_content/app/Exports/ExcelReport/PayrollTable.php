<?php

namespace App\Exports\ExcelReport;

class PayrollTable
{
    private array $rows;

    /**
     * @param array $rows
     */
    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function count()
    {
        return count($this->rows);
    }

    public function render(): array
    {
        $resultado = [
            PayrollRow::head()
        ];

        foreach ($this->rows as $row) {
            $resultado[] = $row->toArray();
        }

        return $resultado;
    }


}
