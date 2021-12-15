<?php

namespace App\Exports\ExcelReport;

class HeaderObraArray
{
    private $items;

    /**
     * @param array[HeaderObra] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
        if ($this->count() < 0) {
            throw new \Exception("Es necesario ingresar una obra al menos.");
        }
    }

    public function value(): array
    {
        return $this->items;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function toArray(): array
    {
        $resultado = [];
        foreach ($this->items as $item) {
            $resultado[] = $item->toArray();
        }
        return $resultado;
    }

}
