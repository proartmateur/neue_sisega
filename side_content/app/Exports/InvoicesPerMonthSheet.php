<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

use Maatwebsite\Excel\Concerns\WithTitle;


class InvoicesPerMonthSheet implements FromArray, WithTitle
{

    private $month;
    private $year;

    public function __construct(int $year, int $month)
    {
        $this->month = $month;
        $this->year  = $year;
    }



    /**
     * @return string
     */
    public function title(): string
    {
        return 'Month ' . $this->month;
    }

    public function array(): array
    {
        // TODO: Implement array() method.
        return [
            ["alsdfh", "popopop"]
        ];
    }
}
