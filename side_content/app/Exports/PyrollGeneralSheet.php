<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PyrollGeneralSheet implements FromArray, WithTitle, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    private $_title;
    private $data;

    public function __construct(string $title, array $data){
        $this->_title = $title;
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function title(): string
    {
        return $this->_title;
    }

    public function columnFormats(): array
    {
        return [
            'A' => '@',
            'B' => '@',
            'C' => '"$"#,##0.00_-',
            'D' => '"$"#,##0.00_-',
            'E' => '"$"#,##0.00_-',
            'F' => '"$"#,##0.00_-',
            'G' => '@',
            'H' => '@',
            'I' => '@',
            'J' => '@',
            'K' => '@',
            'L' => '@',
            'M' => '@',
            'N' => '@',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:N1');
        $sheet->mergeCells('A2:B2');
        $sheet->mergeCells('C2:E2');
        $sheet->mergeCells('G2:L2');
        for($i = 5; $i < 2 + 4; $i++ ){
            $sheet->getRowDimension($i)->setRowHeight(25);
        }
    }
}
