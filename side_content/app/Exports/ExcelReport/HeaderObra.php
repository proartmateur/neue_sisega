<?php

namespace App\Exports\ExcelReport;

class HeaderObra
{
    private $name;
    private $total;

    /**
     * @param string $name
     * @param float $total
     */
    public function __construct(string $name, float $total)
    {
        $this->name = strtoupper($name);
        $this->total = $total;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        return $this->total;
    }





    public function toArray(): array {
        $key = $this->name;
        return [
            $key => $this->total
        ];
    }


}
