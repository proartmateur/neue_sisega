<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
//use Maatwebsite\Excel\Concerns\WithMultipleSheets;
//use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
//use Maatwebsite\Excel\Concerns\WithDrawings;
//use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithColumnWidths;


class PayrollReport implements FromArray, ShouldAutoSize, WithStyles, WithColumnFormatting, /*WithDrawings,*/ WithColumnWidths /*, WithMapping, WithStyles, WithMultipleSheets*/
{
    use Exportable;

    protected $payrolls;
    protected $obras = 0;
    protected $totales = [];


    public function __construct(array $payrolls)
    {
        $this->payrolls = $payrolls;
        logger($payrolls);

    }

    /**
     * @return array
     */
    public function array(): array
    {
        if($this->payrolls[0][0] == -1){
          unset($this->payrolls[0]);
          $this->obras = -1;
          return $this->payrolls;

        }
        else{

           $this->obras = $this->payrolls[0][0];
           //unset($this->payrolls[0]);

           $vuelta = 0;

           $cuantos_tiene = count($this->payrolls[0][1]);

           foreach ($this->payrolls[0][1] as $clave => $valor) {



              if($vuelta != 0){
                   array_push($this->totales, [$clave.' ','',$valor]);
              }
              else{
                  array_push($this->totales, [$clave.' ','',$valor,'','',$this->payrolls[1][2], $this->payrolls[1][7].' ' ]);
              }

              $vuelta++;
           }

           $this->payrolls[1][7] = '';
           $this->payrolls[1][2] = '';
           $this->payrolls[1][0] = '';



          unset($this->payrolls[0]);
          unset($this->payrolls[1]);

           array_unshift($this->payrolls, $this->totales);
           array_unshift($this->payrolls, ['NOMINA SEMANAL ']);



          return $this->payrolls;
        }




        //return  $data = [];
    }

    /*public function sheets(): array
    {
        $sheets = ['Reporte'];



        return $sheets;
    }*/

    public function styles(Worksheet $sheet)
    {
        /*$sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('B1')->getFont()->setBold(true);
        $sheet->getStyle('C1')->getFont()->setBold(true);
        $sheet->getStyle('D1')->getFont()->setBold(true);
        $sheet->getStyle('E1')->getFont()->setBold(true);
        $sheet->getStyle('F1')->getFont()->setBold(true);
        $sheet->getStyle('G1')->getFont()->setBold(true);
        $sheet->getStyle('H1')->getFont()->setBold(true);
        $sheet->getStyle('I1')->getFont()->setBold(true);
        $sheet->getStyle('J1')->getFont()->setBold(true);*/

        $sheet->mergeCells('A1:N1');




      //$sheet->cells('A20:F20')->setBackground('#000000');





        if($this->obras == -1){

          $sheet->mergeCells('A2:B2');
          $sheet->mergeCells('C2:E2');
          $sheet->mergeCells('G2:L2');


          for($i = 5; $i < count($this->payrolls) + 4; $i++ ){
              $sheet->getRowDimension($i)->setRowHeight(25);
          }

           $sheet->getRowDimension(2)->setRowHeight(50);


          $vv = 3;
          foreach($this->payrolls as $elemento){
               $sheet->mergeCells('A'.$vv.':B'.$vv);

               $vv++;
          }






          return [


           'C2' => ['font' => ['bold' => true, 'size' => 20], 'alignment' => ['horizontal' => 'center']],
           'F2' => ['font' => ['bold' => true, 'size' => 20], 'alignment' => ['horizontal' => 'center']],
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
           'K4' => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
           'A' => ['alignment' => ['horizontal' => 'left', 'vertical' => 'center']],
           'B' => ['alignment' => ['horizontal' => 'left', 'vertical' => 'center']],
           'C' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
           'D' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
           'E' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
           'F' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
           'G' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
           'H' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
           'I' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
           'J' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
           'K' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
           'L' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
           'M' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
           'N' => ['alignment' => ['horizontal' => 'left', 'vertical' => 'center']],
           'A1' => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'left']],
           'A2' => ['font' => ['bold' => true, 'size' => 14], 'alignment' => ['horizontal' => 'center']],
           'B2' => ['font' => ['bold' => true, 'size' => 14], 'alignment' => ['horizontal' => 'center']],

            'G2' => ['font' => ['bold' => true, 'size' => 20], 'alignment' => ['horizontal' => 'center']],









           ];

        }
        else{






           $posiciones = $this->obras + 3;








          $regresar['A1'] = ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'left']];



        //setRowHeight() and setWidth()
          for($i = $this->obras + 4; $i < count($this->payrolls) + $this->obras; $i++ ){
              $sheet->getRowDimension($i)->setRowHeight(25);
          }


















          for($i = 2; $i < $this->obras + 3; $i++){

           $sheet->mergeCells('A'.$i.':B'.$i);



           $sheet->mergeCells('C'.$i.':E'.$i);
           //$sheet->mergeCells('E'.$i.':H'.$i);

            $regresar['A'.$i] = ['font' => ['bold' => true, 'size' => 14], 'alignment' => ['horizontal' => 'center']];

           $regresar['B'.$i] = ['font' => ['bold' => true, 'size' => 14], 'alignment' => ['horizontal' => 'center']];
           $regresar['C'.$i] = ['font' => ['bold' => true, 'size' => 14], 'alignment' => ['horizontal' => 'center']];
           $regresar['F'.$i] = ['font' => ['bold' => true, 'size' => 20], 'alignment' => ['horizontal' => 'center']];
           $regresar['G'.$i] = ['font' => ['bold' => true, 'size' => 20], 'alignment' => ['horizontal' => 'center']];
           $regresar['G'.$i] = ['font' => ['bold' => true, 'size' => 20], 'alignment' => ['horizontal' => 'center']];

          }

          $r = $this->obras + 1;

          eval('$sheet->mergeCells(\'F2:F'.$r.'\');');
          eval('$sheet->mergeCells(\'G2:J'.$r.'\');');


          $vv = $this->obras;
          foreach($this->payrolls as $elemento){
               $sheet->mergeCells('A'.$vv.':B'.$vv);

               $vv++;
          }















           eval('$regresar[\''.$posiciones.'\'] = [\'font\' => [\'bold\' => true], \'alignment\' => [\'horizontal\' => \'center\']];');
           eval('$regresar[\'A'.$posiciones.'\'] = [\'font\' => [\'bold\' => true], \'alignment\' => [\'horizontal\' => \'left\', \'vertical\' => \'center\']];');

            eval('$regresar[\'B'.$posiciones.'\'] = [\'font\' => [\'bold\' => true], \'alignment\' => [\'horizontal\' => \'left\']];');



           $regresar['A'] = ['alignment' => ['vertical' => 'center']];

           $regresar['B'] = ['alignment' => ['horizontal' => 'left', 'vertical' => 'center']];
           $regresar['C'] = ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']];
           $regresar['D'] = ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']];
           $regresar['E'] = ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']];
           $regresar['F'] = ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']];

           $regresar['G'] = ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']];
           $regresar['H'] = ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']];
           $regresar['I'] = ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']];
           $regresar['J'] = ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']];
           $regresar['K'] = ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']];
           $regresar['L'] = ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']];
           $regresar['M'] = ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']];
           $regresar['N'] = ['alignment' => ['horizontal' => 'left', 'vertical' => 'center']];











           return $regresar;

        }




           /* // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],

            // Styling a specific cell by coordinate.
            'B2' => ['font' => ['italic' => true]],

            // Styling an entire column.
            'C'  => ['font' => ['size' => 16]],*/



    }

    public function columnFormats(): array
    {//FORMAT_NUMBER
        /*return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_NUMBER,
            'C' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_TEXT,
            'I' => NumberFormat::FORMAT_TEXT,
            'J' => NumberFormat::FORMAT_TEXT,
        ];*/

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


    /*public function drawings()
    {
       $imagenes = [];



        if($this->payrolls[0][0] != -1){

            $despuesde = count($this->payrolls[0][1]) + 4;
            $ve = 0;
            $vec = 0;

            foreach($this->payrolls as $elemento){

                if( $ve > 3){

                    if( trim($this->payrolls[$ve][0]) != ''){
                         if(file_exists(public_path(trim($this->payrolls[$ve][0])))){

                             $extension = strtolower(pathinfo(trim($this->payrolls[$ve][0]), PATHINFO_EXTENSION));
                             if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png'){

                            $drawing = new Drawing();

                            $drawing->setPath(public_path(trim($this->payrolls[$ve][0])));
                            $drawing->setHeight(80);
                            $drawing->setCoordinates('A'.($despuesde +  $vec));

                            array_push($imagenes, $drawing);

                            $this->payrolls[$ve][0] = '';
                             }
                              $this->payrolls[$ve][0] = '';
                         }
                    }

                     $vec ++;
                }

                $ve++;
            }

        }
        else{

            $despuesde = 4;
            $ve = 0;
            $vec = 0;

            foreach($this->payrolls as $elemento){

                if( $ve > 3){

                    if( trim($this->payrolls[$ve][0]) != ''){

                        if(file_exists(public_path(trim($this->payrolls[$ve][0])))){

                             $extension = strtolower(pathinfo(trim($this->payrolls[$ve][0]), PATHINFO_EXTENSION));
                             if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png'){

                            $drawing = new Drawing();
                            $drawing->setPath(public_path(trim($this->payrolls[$ve][0])));
                            $drawing->setHeight(80);
                            $drawing->setCoordinates('A'.($despuesde +  $vec));

                            array_push($imagenes, $drawing);

                            $this->payrolls[$ve][0] = '';
                             }
                             $this->payrolls[$ve][0] = '';
                        }
                    }

                     $vec ++;
                }

                $ve++;
            }

        }//else






        return $imagenes;


    }*/

    public function columnWidths(): array
    {
        return [
            'A' => 25,
        ];
    }




}
