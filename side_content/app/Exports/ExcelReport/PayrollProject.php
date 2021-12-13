<?php

namespace App\Exports\ExcelReport;

class PayrollProject
{
    private string $obra;
    private array $items;

    /**
     * @param string $obra
     * @param array[PayrollRow] $items
     */
    public function __construct(string $obra, array $items)
    {
        $this->obra = strtoupper($obra);
        $this->items = $items;
    }

    public function total()
    {
        $result = 0;
        $scale_factor = 100;

        foreach ($this->items as $item) {
            if ($item->getObra() === $this->obra) {
                $amount = $item->getTotal() * $scale_factor;
                $result += $amount;
            }
        }

        return $result / $scale_factor;
    }


    //region Getters

    /**
     * @return string
     */
    public function getObra(): string
    {
        return $this->obra;
    }

    /**
     * @return array[PayrollRow]
     */
    public function getItems(): array
    {
        return array_filter($this->items, [$this, 'filter']);
    }

    private function filter($item)
    {
        return $item->getObra() === $this->obra;
    }
    //endregion
}
