<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class PayrollAllProjectsSheet implements FromArray, WithTitle, ShouldAutoSize, WithStyles, WithColumnFormatting
{

    private $_title;
    private $data;
    private int $count_data;
    private int $count_projects;

    public function __construct(
        string $title,
        array  $data,
        int    $count_data,
        int    $count_projects
    )
    {
        $this->_title = $title;
        $this->data = $data;
        $this->count_data = $count_data;
        $this->count_projects = $count_projects;
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
            'D' => '"$"#,##0.00_-',
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
        //$sheet->mergeCells('A2:N2');

        //Merge Projects Cells
        $max_v_merge = $this->count_projects + 1;
        $table_header_row = $max_v_merge + 2;
        $sheet->mergeCells("D2:E$max_v_merge");
        $sheet->mergeCells("F2:J$max_v_merge");

        //$sheet->getRowDimension(2)->setRowHeight(50);



        $header_font = ['font' => ['bold' => true, 'size' => 20], 'alignment' => ['horizontal' => 'center']];

        $font_styles = [
            'A1' => ['font' => ['bold' => true, 'size' => 14], 'alignment' => ['horizontal' => 'left']],
            'D2' => $header_font,
            'F2' => $header_font,
        ];

        for ($i = 2; $i <= $max_v_merge; $i++) {
            $font_styles["A$i"] = $header_font;
            $font_styles["B$i"] = $header_font;
            $font_styles["C$i"] = $header_font;
            $sheet->mergeCells("B$i:C$i");
        }


        for ($i = 5; $i < $this->count_data + ($max_v_merge + 3); $i++) {
            $sheet->getRowDimension($i)->setRowHeight(25);
        }

        //ns30.prodns.mx
        //ns31.prodns.mx
        $font_end = [
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

            "A$table_header_row" => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'left']],
            "B$table_header_row" => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
            "C$table_header_row" => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
            "D$table_header_row" => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
            "E$table_header_row" => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
            "F$table_header_row" => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
            "G$table_header_row" => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
            "H$table_header_row" => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
            "I$table_header_row" => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
            "J$table_header_row" => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
            "K$table_header_row" => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'left']],
            "L$table_header_row" => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'left']],
            "M$table_header_row" => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'left']],

        ];

        $font_styles_ok = array_merge($font_styles, $font_end);

        return $font_styles_ok;
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
