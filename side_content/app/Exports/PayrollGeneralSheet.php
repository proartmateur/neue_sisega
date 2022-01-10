<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PayrollGeneralSheet implements FromArray, WithTitle, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    private $_title;
    private $data;
    private int $count_data;

    public function __construct(string $title, array $data, int $count_data){
        $this->_title = $title;
        $this->data = $data;
        $this->count_data = $count_data;
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
            'B' => '"$"#,##0.00_-',
            'C' => '"$"#,##0.00_-',
            'D' => '@',
            'E' => '@',
            'F' => '@',
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
        $sheet->mergeCells('F2:L2');

        $sheet->getRowDimension(2)->setRowHeight(50);
        for($i = 5; $i < $this->count_data + 5; $i++ ){
            $sheet->getRowDimension($i)->setRowHeight(25);
        }

        return [
            'A1' => ['font' => ['bold' => true, 'size' => 14], 'alignment' => ['horizontal' => 'left']],
            'A2' => ['font' => ['bold' => true, 'size' => 20], 'alignment' => ['horizontal' => 'center']],
            'C2' => ['font' => ['bold' => true, 'size' => 20], 'alignment' => ['horizontal' => 'center']],
            'F2' => ['font' => ['bold' => true, 'size' => 20], 'alignment' => ['horizontal' => 'left']],

            'A4' => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'left']],
            'B4' => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
            'C4' => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
            'D4' => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
            'E4' => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
            'F4' => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
            'G4' => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
            'H4' => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
            'I4' => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
            'J4' => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
            'K4' => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'left']],
            'L4' => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'left']],
            'M4' => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'left']],

            'A' => ['alignment' => ['horizontal' => 'left', 'vertical' => 'center']],
            'B' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
            'C' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
            'D' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
            'E' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
            'F' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
            'G' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
            'H' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
            'I' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
            'J' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
            'K' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'left']],
            'L' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'left']],
            'M' => ['alignment' => ['horizontal' => 'left', 'vertical' => 'left']],

        ];
    }
}
