<?php

namespace Tests\Unit;

use App\Employee;
use App\Exports\ExcelReport\HeaderExcel;
use App\Exports\ExcelReport\HeaderObra;
use App\Exports\ExcelReport\HeaderObraArray;
use App\Exports\ExcelReport\PayrollExcel;
use App\Exports\ExcelReport\PayrollProject;
use App\Exports\ExcelReport\PayrollRow;
use App\Exports\ExcelReport\PayrollTable;
use App\Exports\PayrollReport;
use App\Http\Controllers\PayrollsController;
use App\Payroll;
use App\PublicWork;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $hola = "JOJOJO";
        $this->assertTrue(true);
    }

    public function testExcelBasicTest()
    {
        $data = [];
        /*
        $emps = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19];

        array_push($data, ["Nombre completo", " ", "Sueldo", "Horas extra", "Bono", "Total", "Obra", "Banco", "Cuenta", "CLABE", "IMSS", "Tipo", "Firma", "Comentarios"]);

        foreach ($emps as $item_e) {
            array_push($data, [
                "JUANITO",
                " ",
                12.56,
                458,
                23.23,
                100.56,
                "public_work ",
                " BBVA",
                " 12313212Account",
                " awñkldf3243546546 clabe",
                "74798754 ",
                'Empleado',
                "",
                "Comentarios "
            ]);
        }


        array_unshift($data, [' ']);
        array_unshift($data, [
            'A ',
            'B',
            'C',
            'D',
            'E',
            'F',
            'G',
            'H',
            'I',
            'J',
            'K',
            'L',
            'M'
        ]);
        array_unshift($data, ["LOLOL", "", number_format(1000.566565, '2', '.', ''), '', '', '', '$aCadena' . ' ']);


        array_unshift($data, ['NOMINA SEMANAL ']);
        array_unshift($data, [-1]);
*/
        $data = $this->getData();
        $export = new PayrollReport($data);
        //$report = Excel::download($export, 'Reporte.xlsx');
        //$stored = Excel::store($export, '/Users/ennima/Devs/neue_studio/SISEGA/docker_laravel8_php8_apache/src/side_content/tests/Unit/Reporte.xlsx');
        //var_dump($stored);
        //var_dump("zñkdjfhslkdjfhlakjdhsf");
        //$uu = $export->store('/Users/ennima/Devs/neue_studio/SISEGA/docker_laravel8_php8_apache/src/side_content/tests/Unit/Reporte_test.xlsx');

        $this->assertTrue(true);
    }

    private function getData()
    {
        return array(
            0 =>
                array(
                    0 => -1,
                ),
            1 =>
                array(
                    0 => 'NOMINA SEMANAL ',
                ),
            2 =>
                array(
                    0 => 'GAP GDL  ',
                    1 => '',
                    2 => '369884.00',
                    3 => '',
                    4 => '',
                    5 => '',
                    6 => 'NOMINA SEMANAL 30 DE NOVIEMBRE DE 2021 ',
                ),
            3 =>
                array(
                    0 => 'A ',
                    1 => 'B',
                    2 => 'C',
                    3 => 'D',
                    4 => 'E',
                    5 => 'F',
                    6 => 'G',
                    7 => 'H',
                    8 => 'I',
                    9 => 'J',
                    10 => 'K',
                    11 => 'L',
                    12 => 'M',
                ),
            4 =>
                array(
                    0 => ' ',
                ),
            5 =>
                array(
                    0 => 'Nombre completo',
                    1 => ' ',
                    2 => 'Sueldo',
                    3 => 'Horas extra',
                    4 => 'Bono',
                    5 => 'Total',
                    6 => 'Obra',
                    7 => 'Banco',
                    8 => 'Cuenta',
                    9 => 'CLABE',
                    10 => 'IMSS',
                    11 => 'Tipo',
                    12 => 'Firma',
                    13 => 'Comentarios',
                ),
            6 =>
                array(
                    0 => 'Daniel Sigala Medina C.C. ',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '13700.00',
                    6 => 'GAP GDL ',
                    7 => 'Banorte ',
                    8 => '0431963392 ',
                    9 => '072320004319633924 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => ' ',
                ),
            7 =>
                array(
                    0 => 'Sergio Guillen Gutierrez ',
                    1 => ' ',
                    2 => '2300.00',
                    3 => 2060,
                    4 => '0.00',
                    5 => '4360.00',
                    6 => 'GAP GDL ',
                    7 => 'Banorte ',
                    8 => '1042286159 ',
                    9 => '072320010422861596 ',
                    10 => '54776039197 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '21.5 HRS EXTRA ',
                ),
            8 =>
                array(
                    0 => 'Carlos Humberto Landeros Lopez ',
                    1 => ' ',
                    2 => '2200.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2200.00',
                    6 => 'GAP GDL ',
                    7 => 'Banamex ',
                    8 => '7137487 ',
                    9 => '002320904471374877 ',
                    10 => '35170028209 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            9 =>
                array(
                    0 => 'Maria Trinidad Garcia Angel ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 417,
                    4 => '0.00',
                    5 => '2417.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '3167710486 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '5 HORAS EXTRA ',
                ),
            10 =>
                array(
                    0 => 'Juan Carlos Martinez Chontal ',
                    1 => ' ',
                    2 => '2800.00',
                    3 => 583,
                    4 => '0.00',
                    5 => '3383.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '5 HORAS EXTRA ',
                ),
            11 =>
                array(
                    0 => 'Adolfo Garcia Rodriguez ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 458,
                    4 => '0.00',
                    5 => '2458.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '75018301053 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '5.5 HORAS EXTRA ',
                ),
            12 =>
                array(
                    0 => 'Jose Francisco Flores Orozco ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 458,
                    4 => '0.00',
                    5 => '2458.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '4957814702 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '5.5 HORAS EXTRA ',
                ),
            13 =>
                array(
                    0 => 'Guadalupe Juanpedro Orozco ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 417,
                    4 => '0.00',
                    5 => '2417.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '4109401671 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '5 HORAS EXTRA ',
                ),
            14 =>
                array(
                    0 => 'Rocio Enriquez Rodriguez ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 750,
                    4 => '0.00',
                    5 => '2750.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '4069075994 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '9 HORAS EXTRA ',
                ),
            15 =>
                array(
                    0 => 'Rodolfo Enriquez Muñoz ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 1167,
                    4 => '0.00',
                    5 => '3167.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '4897122737 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '14 HORAS EXTRA ',
                ),
            16 =>
                array(
                    0 => 'Cynthia Esther Guillén Venegas ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 958,
                    4 => '0.00',
                    5 => '2958.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '11.5 HORAS EXTRA ',
                ),
            17 =>
                array(
                    0 => 'Hector Javier Soto Castro ',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '8750.00',
                    6 => 'GAP GDL ',
                    7 => 'BBVA ',
                    8 => '1580884564 ',
                    9 => '012771015808845600 ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => 'FAUSTINA, ALONDRA Y DIANA = $2250, DANIELA = $2000 ',
                ),
            18 =>
                array(
                    0 => 'Hector Javier Soto Castro C.C. ',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '8371.00',
                    6 => 'GAP GDL ',
                    7 => 'BBVA Bancomer ',
                    8 => '1580884564 ',
                    9 => '012771015808845643 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => 'GARRAFPNES, BOLSAS NEGRAS Y ESTROPAJOS, PTR 2\'\'X2\'\', RELLENO EXTINTORES, GASTOS MEDICOS ROCIO, MATERIAL ELECTRICO, PLAN DE INTERNET, MICAS, GASOLINA, CELULAR RODOLFO ',
                ),
            19 =>
                array(
                    0 => 'Alejandro de la Vega Ramirez ',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '5800.00',
                    6 => 'GAP GDL ',
                    7 => 'BBVA ',
                    8 => '1577609903 ',
                    9 => '012180015776099031 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => '6 HORAS EXTRA ',
                ),
            20 =>
                array(
                    0 => 'Citlalli Soriano Rodriguez ',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '10000.00',
                    6 => 'GAP GDL ',
                    7 => 'BBVA BANCOMER ',
                    8 => '2925849594 ',
                    9 => '012180029258495945 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => ' ',
                ),
            21 =>
                array(
                    0 => 'Jose Isidro Gomez Ortega ',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '4000.00',
                    6 => 'GAP GDL ',
                    7 => 'BBVA BANCOMER ',
                    8 => '1542771036 ',
                    9 => '012180015427710368 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => '8 HORAS EXTRA ',
                ),
            22 =>
                array(
                    0 => 'Jose Flores Alegria ',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '18340.00',
                    6 => 'GAP GDL ',
                    7 => 'Banamex ',
                    8 => '84645 ',
                    9 => '002320453400846445 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => 'COLOCACIÓN DE LAMPARAS DOBLE ALTURA, SALIDAS PARA ANUNCIOS, NICHOS, AIRE, COLOCACION DE ESCALERILLA ',
                ),
            23 =>
                array(
                    0 => 'Carlos Humberto Landeros Lopez ',
                    1 => ' ',
                    2 => '2200.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2200.00',
                    6 => 'GAP GDL ',
                    7 => 'Banamex ',
                    8 => '7137487 ',
                    9 => '002320904471374877 ',
                    10 => '35170028209 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            24 =>
                array(
                    0 => 'Maria Trinidad Garcia Angel ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 1167,
                    4 => '0.00',
                    5 => '3167.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '3167710486 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '14 HORAS EXTRA ',
                ),
            25 =>
                array(
                    0 => 'Adolfo Garcia Rodriguez ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 1250,
                    4 => '0.00',
                    5 => '3250.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '75018301053 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            26 =>
                array(
                    0 => 'Jose Francisco Flores Orozco ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 1083,
                    4 => '0.00',
                    5 => '3083.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '4957814702 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '13 HORAS EXTRA ',
                ),
            27 =>
                array(
                    0 => 'Guadalupe Juanpedro Orozco ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 833,
                    4 => '0.00',
                    5 => '2833.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '4109401671 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '10 HORAS EXTRA ',
                ),
            28 =>
                array(
                    0 => 'Rocio Enriquez Rodriguez ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 1667,
                    4 => '0.00',
                    5 => '3667.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '4069075994 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '20 HRS EXTRA ',
                ),
            29 =>
                array(
                    0 => 'Rodolfo Enriquez Muñoz ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 2167,
                    4 => '0.00',
                    5 => '4167.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '4897122737 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '25 HRS EXTRA ',
                ),
            30 =>
                array(
                    0 => 'Cynthia Esther Guillén Venegas ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 2000,
                    4 => '0.00',
                    5 => '4000.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '24 HRS EXTRA ',
                ),
            31 =>
                array(
                    0 => 'Faustina Enriquez Muñoz ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 1667,
                    4 => '0.00',
                    5 => '3667.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '04098479333 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '20 HRS EXTRA ',
                ),
            32 =>
                array(
                    0 => 'Diana Laura Rodriguez Rivas ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 750,
                    4 => '0.00',
                    5 => '2750.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '19179706882 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '9 HRS EXTRA ',
                ),
            33 =>
                array(
                    0 => 'Gustavo Diaz Chavez ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2000.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '23048675799 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            34 =>
                array(
                    0 => 'Tito Lopez Roy ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2000.00',
                    6 => 'GAP GDL ',
                    7 => 'BBVA Bancomer ',
                    8 => '1547136320 ',
                    9 => '012320015471363204 ',
                    10 => '19149684193 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            35 =>
                array(
                    0 => 'Uriel Nieves Ruvalcaba ',
                    1 => ' ',
                    2 => '2200.00',
                    3 => 692,
                    4 => '0.00',
                    5 => '2892.00',
                    6 => 'GAP GDL ',
                    7 => 'BBVA Bancomer ',
                    8 => '1585372219 ',
                    9 => '012180015853722195 ',
                    10 => '18170179537 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '13 HRS EXTRA - 500 PRESTAMO ',
                ),
            36 =>
                array(
                    0 => 'Gabriel Ramirez ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 1667,
                    4 => '0.00',
                    5 => '3667.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '4816345112 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '20 HRS EXTRA ',
                ),
            37 =>
                array(
                    0 => 'Oscar Guerrero Muñiz ',
                    1 => ' ',
                    2 => '2300.00',
                    3 => 1246,
                    4 => '0.00',
                    5 => '3546.00',
                    6 => 'GAP GDL ',
                    7 => 'Banorte ',
                    8 => '1044335136 ',
                    9 => '072320010443351360 ',
                    10 => '04978029009 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '13 HRS EXTRA ',
                ),
            38 =>
                array(
                    0 => 'Citlalli Soriano Rodriguez ',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '10000.00',
                    6 => 'GAP GDL ',
                    7 => 'BBVA BANCOMER ',
                    8 => '2925849594 ',
                    9 => '012180029258495945 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => ' ',
                ),
            39 =>
                array(
                    0 => 'Jose Isidro Gomez Ortega ',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '6000.00',
                    6 => 'GAP GDL ',
                    7 => 'BBVA BANCOMER ',
                    8 => '1542771036 ',
                    9 => '012180015427710368 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => '24 HRS EXTRA ',
                ),
            40 =>
                array(
                    0 => 'Luis Alejandro Lopez Aranda C.C. ',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '754.00',
                    6 => 'GAP GDL ',
                    7 => 'BBVA Bancomer ',
                    8 => '1587538574 ',
                    9 => '012180015875385743 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => 'COMIDA TRABAJADORES DOMINGO ',
                ),
            41 =>
                array(
                    0 => 'Hector Javier Soto Castro C.C. ',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '6324.00',
                    6 => 'GAP GDL ',
                    7 => 'BBVA Bancomer ',
                    8 => '1580884564 ',
                    9 => '012771015808845643 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => 'GASOLINA, DIESEL, CINTA MASKKING, COMIDA SABADO, BOLETOS GDL-AGS, CARTAS POLICIA ',
                ),
            42 =>
                array(
                    0 => 'Carlos Humberto Landeros Lopez ',
                    1 => ' ',
                    2 => '2200.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2200.00',
                    6 => 'GAP GDL ',
                    7 => 'Banamex ',
                    8 => '7137487 ',
                    9 => '002320904471374877 ',
                    10 => '35170028209 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            43 =>
                array(
                    0 => 'Maria Trinidad Garcia Angel ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 1292,
                    4 => '0.00',
                    5 => '3292.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '3167710486 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '15.5 HRS EXTRA ',
                ),
            44 =>
                array(
                    0 => 'Juan Carlos Martinez Chontal ',
                    1 => ' ',
                    2 => '2800.00',
                    3 => 1633,
                    4 => '0.00',
                    5 => '4433.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '14 HRS EXTRA ',
                ),
            45 =>
                array(
                    0 => 'Adolfo Garcia Rodriguez ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 1458,
                    4 => '0.00',
                    5 => '3458.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '75018301053 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '17.5 HRS EXTRA ',
                ),
            46 =>
                array(
                    0 => 'Jose Francisco Flores Orozco ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 1708,
                    4 => '0.00',
                    5 => '3708.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '4957814702 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '20.5 HRS EXTRA ',
                ),
            47 =>
                array(
                    0 => 'Guadalupe Juanpedro Orozco ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2000.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '4109401671 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            48 =>
                array(
                    0 => 'Rodolfo Enriquez Muñoz ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 1250,
                    4 => '0.00',
                    5 => '3250.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '4897122737 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '15 HRS EXTRA ',
                ),
            49 =>
                array(
                    0 => 'Rocio Enriquez Rodriguez ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 1708,
                    4 => '0.00',
                    5 => '3708.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '4069075994 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '20.5 HRS EXTRA ',
                ),
            50 =>
                array(
                    0 => 'Cynthia Esther Guillén Venegas ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 1292,
                    4 => '0.00',
                    5 => '3292.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '15.5 HRS EXTRA ',
                ),
            51 =>
                array(
                    0 => 'Gustavo Diaz Chavez ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 1167,
                    4 => '0.00',
                    5 => '3167.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '23048675799 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '14 HRS EXTRA ',
                ),
            52 =>
                array(
                    0 => 'Tito Lopez Roy ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2000.00',
                    6 => 'GAP GDL ',
                    7 => 'BBVA Bancomer ',
                    8 => '1547136320 ',
                    9 => '012320015471363204 ',
                    10 => '19149684193 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            53 =>
                array(
                    0 => 'Gabriel Ramirez ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 500,
                    4 => '0.00',
                    5 => '2500.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '4816345112 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '6 HRS EXTRA ',
                ),
            54 =>
                array(
                    0 => 'Oscar Guerrero Muñiz ',
                    1 => ' ',
                    2 => '2300.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2300.00',
                    6 => 'GAP GDL ',
                    7 => 'Banorte ',
                    8 => '1044335136 ',
                    9 => '072320010443351360 ',
                    10 => '04978029009 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            55 =>
                array(
                    0 => 'Hector Javier Soto Castro C.C. ',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '11551.00',
                    6 => 'GAP GDL ',
                    7 => 'BBVA Bancomer ',
                    8 => '1580884564 ',
                    9 => '012771015808845643 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => 'SEMANA 15/11 - 20/11/2021: CALAVERAS CAMIONETA: $2380, TORRETA $470, FOCOS: $34, BIDONES: $60, MEDICINA: $90, CINTAS DE PRECAUCIÓN: $575, CELULAR ISIDRO: $6000, 2 DC3: $800, 3 PAQUETES DE HOJAS: $261, DESENGRASANTE PINOL Y BIDON: $160, UBER PARA PLACAS CHAROLA: $171, CLORO Y VINAGRE: $150, GASOLINA: $400 ',
                ),
            56 =>
                array(
                    0 => 'Citlalli Soriano Rodriguez ',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '10000.00',
                    6 => 'GAP GDL ',
                    7 => 'BBVA BANCOMER ',
                    8 => '2925849594 ',
                    9 => '012180029258495945 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => ' ',
                ),
            57 =>
                array(
                    0 => 'Jose Isidro Gomez Ortega ',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '6250.00',
                    6 => 'GAP GDL ',
                    7 => 'BBVA BANCOMER ',
                    8 => '1542771036 ',
                    9 => '012180015427710368 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => '26 HRS EXTRA ',
                ),
            58 =>
                array(
                    0 => 'Jose Flores Alegria ',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '22100.00',
                    6 => 'GAP GDL ',
                    7 => 'Banamex ',
                    8 => '84645 ',
                    9 => '002320453400846445 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => 'COLOCACION DE LAMPARAS PASILLO, CABLEADO DEL CGA A PASILLO, ESCALERILLA, LAMPARA TECHUMBRE ',
                ),
            59 =>
                array(
                    0 => 'Luis Alejandro Lopez Aranda C.C. ',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '3042.00',
                    6 => 'GAP GDL ',
                    7 => 'BBVA Bancomer ',
                    8 => '1587538574 ',
                    9 => '012180015875385743 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => 'Comidas de trabajadores, material y traslados de personal. ',
                ),
            60 =>
                array(
                    0 => 'Sergio Guillen Gutierrez ',
                    1 => ' ',
                    2 => '2300.00',
                    3 => 1342,
                    4 => '0.00',
                    5 => '3642.00',
                    6 => 'GAP GDL ',
                    7 => 'Banorte ',
                    8 => '1042286159 ',
                    9 => '072320010422861596 ',
                    10 => '54776039197 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            61 =>
                array(
                    0 => 'Carlos Humberto Landeros Lopez ',
                    1 => ' ',
                    2 => '2200.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2200.00',
                    6 => 'GAP GDL ',
                    7 => 'Banamex ',
                    8 => '7137487 ',
                    9 => '002320904471374877 ',
                    10 => '35170028209 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            62 =>
                array(
                    0 => 'Maria Trinidad Garcia Angel ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 1417,
                    4 => '0.00',
                    5 => '3417.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '3167710486 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            63 =>
                array(
                    0 => 'Juan Carlos Martinez Chontal ',
                    1 => ' ',
                    2 => '2800.00',
                    3 => 642,
                    4 => '0.00',
                    5 => '3442.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            64 =>
                array(
                    0 => 'Adolfo Garcia Rodriguez ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 2292,
                    4 => '0.00',
                    5 => '4292.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '75018301053 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            65 =>
                array(
                    0 => 'Jose Francisco Flores Orozco ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 500,
                    4 => '0.00',
                    5 => '2167.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '4957814702 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            66 =>
                array(
                    0 => 'Guadalupe Juanpedro Orozco ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 1167,
                    4 => '0.00',
                    5 => '3167.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '4109401671 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            67 =>
                array(
                    0 => 'Rocio Enriquez Rodriguez ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 1417,
                    4 => '0.00',
                    5 => '3417.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '4069075994 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            68 =>
                array(
                    0 => 'Rodolfo Enriquez Muñoz ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 1292,
                    4 => '0.00',
                    5 => '3292.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '4897122737 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            69 =>
                array(
                    0 => 'Cynthia Esther Guillén Venegas ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 750,
                    4 => '0.00',
                    5 => '2750.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            70 =>
                array(
                    0 => 'Faustina Enriquez Muñoz ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 917,
                    4 => '0.00',
                    5 => '2917.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '04098479333 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            71 =>
                array(
                    0 => 'Alondra Jaqueline Enriquez Muñoz ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 1458,
                    4 => '0.00',
                    5 => '3458.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '46210388644 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            72 =>
                array(
                    0 => 'Daniela Guadalupe Garcia Salazar ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 1833,
                    4 => '0.00',
                    5 => '3833.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '38180113151 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            73 =>
                array(
                    0 => 'Gustavo Diaz Chavez ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 2750,
                    4 => '0.00',
                    5 => '4750.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '23048675799 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            74 =>
                array(
                    0 => 'Tito Lopez Roy ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 625,
                    4 => '0.00',
                    5 => '2625.00',
                    6 => 'GAP GDL ',
                    7 => 'BBVA Bancomer ',
                    8 => '1547136320 ',
                    9 => '012320015471363204 ',
                    10 => '19149684193 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            75 =>
                array(
                    0 => 'Gabriel Ramirez ',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 1417,
                    4 => '0.00',
                    5 => '3417.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '4816345112 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            76 =>
                array(
                    0 => 'Hector Javier Soto Castro C.C. ',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '18951.00',
                    6 => 'GAP GDL ',
                    7 => 'BBVA Bancomer ',
                    8 => '1580884564 ',
                    9 => '012771015808845643 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => 'SEMANA 22/11 - 27/11/2021: CARPETAS DE ARGOLLA Y PROTECTORES DE HOJA: $661, PERFILES DE ALUMINIO: $1036, COMIDA SÁBADO: $1400, NOCHE BUENAS Y TIERRA: $1365, CARDA Y FELPA: $125, GASOLINA: $350, NOCHE BUENAS: $1540, CHALECOS: $1200, NOCHE BUENAS: $900, TIERRA VEGETAL: $700, ÁCIDO: $903, AEROSOL: $195, TOPES: $952, CUTTER: $66, CENA LUNES: $1738, UBERS GENTE: $839, ÁCIDO; $477, SELLADOR: $1205, CENA MARTES: $1833, REFRESCOS CENA MARTES: $189, PARES DE GUANTES: $260, PLACAS CIEGAS: $40, LLENADO DE CISTERNAS: $120, UBER MATERIAL ELECTRICO: $164, ACIDO MURIATICO: $320, SILICON: $373 ',
                ),
            77 =>
                array(
                    0 => 'Citlalli Soriano Rodriguez ',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '10000.00',
                    6 => 'GAP GDL ',
                    7 => 'BBVA BANCOMER ',
                    8 => '2925849594 ',
                    9 => '012180029258495945 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => ' ',
                ),
            78 =>
                array(
                    0 => 'Jose Isidro Gomez Ortega ',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '7000.00',
                    6 => 'GAP GDL ',
                    7 => 'BBVA BANCOMER ',
                    8 => '1542771036 ',
                    9 => '012180015427710368 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => ' ',
                ),
            79 =>
                array(
                    0 => 'Jose Flores Alegria ',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '20400.00',
                    6 => 'GAP GDL ',
                    7 => 'Banamex ',
                    8 => '84645 ',
                    9 => '002320453400846445 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => ' ',
                ),
            80 =>
                array(
                    0 => 'Israel Naun Lopez Soltero ',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '1000.00',
                    6 => 'GAP GDL ',
                    7 => 'Banamex ',
                    8 => '98372867191 ',
                    9 => '002320904471386373 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => 'REPARACIÓN DE TUBERÍAS Y AJUSTES DE COLADERAS ',
                ),
        );
    }

    public function testHeaderExcel()
    {
        $header = new HeaderExcel(
            "NOMINA mes",
            new HeaderObraArray(
                [
                    new HeaderObra("GAP GDL", 58456.00)
                ]
            ),
            "09 de diciembre 2021"
        );
        $hdata = $header->render();

        $this->assertTrue(true);
    }

    public function testPayrollTable()
    {
        $table = new PayrollTable(
            [
                new PayrollRow(
                    'Enrique Nieto Martínez',
                    40000,
                    0,
                    45000,
                    95000,
                    'My Life',
                    'Karma , Healt, Love & Happyness',
                    '323334+',
                    '111222333444555666777888999',
                    '123456',
                    'Empleado',
                    '',
                    ''
                ),
                new PayrollRow(
                    'Jane Doe',
                    8589.66,
                    0,
                    0,
                    8589.66,
                    'SOME PROJECT',
                    'BBVA',
                    '111222111',
                    '789456123789456123789456123',
                    '123456',
                    'Empleado',
                    '',
                    ''
                )
            ]
        );

        $render = $table->render();
        $this->assertEquals('Nombre Completo', $render[0][0]);
        $this->assertTrue(true);
    }

    public function testPayrollProject()
    {
        $items = [
            new PayrollRow(
                'Enrique Nieto Martínez',
                40000,
                0,
                45000,
                95000,
                'My Life',
                'Karma , Healt, Love & Happyness',
                '323334+',
                '111222333444555666777888999',
                '123456',
                'Empleado',
                '',
                ''
            ),
            new PayrollRow(
                'Jane Doe',
                8589.66,
                0,
                0,
                8589.66,
                'SOME PROJECT',
                'BBVA',
                '111222111',
                '789456123789456123789456123',
                '123456',
                'Empleado',
                '',
                ''
            ),
            new PayrollRow(
                'Jane Doe',
                8589.66,
                0,
                0,
                8589.66,
                'SOME PROJECT',
                'BBVA',
                '111222111',
                '789456123789456123789456123',
                '123456',
                'Empleado',
                '',
                ''
            ),
            new PayrollRow(
                'Jane Doe',
                1000,
                0,
                0,
                1000,
                'SOME PROJECT',
                'BBVA',
                '111222111',
                '789456123789456123789456123',
                '123456',
                'Empleado',
                '',
                ''
            )
        ];

        $project = new PayrollProject(
            'My Life',
            $items
        );

        $total = $project->total();
        $items = $project->getItems();

        $this->assertTrue(true);
    }

    public function testPayroll()
    {
        $start = '2021-12-01';
        $end = '2021-12-11';
        $proyecto_name = 'MUSEO CCU';
        $proy = $this->projectPayroll(
            $proyecto_name,
            $start,
            $end
        );


        $this->assertTrue(true);
    }

    private function projectPayroll(
        string $proyecto_name,
        string $start,
        string $end
    )
    {

        $payrolls_items = [];

        $payroll = Payroll::select(
            'payrolls.id AS id', 'payrolls.days_worked', 'payrolls.hours_worked',
            'payrolls.extra_hours', 'payrolls.total_salary', 'payrolls.date',
            'payrolls.comments',
            'employees.id as employee_id', 'employees.name as employee_name',
            'employees.salary_week as employee_salary_week', 'employees.bank as bank',
            'employees.account as account', 'employees.clabe as clabe',
            'employees.imss_number as imss_number',
            'employees.type as employee_type', 'payrolls.extra_hours as extra_hours',
            'public_works.id as public_work_id',
            'public_works.name AS public_work', 'payrolls.comments'
        )->join('employees', 'employees.id', 'payrolls.employee_id')
            ->join('public_works', 'public_works.id', 'payrolls.public_work_id')
            ->where('public_works.name', 'like', $proyecto_name)
            ->whereBetween('payrolls.date', [$start, $end . ' 23:59:59'])->get();

        $count = 0;
        $total_salarios = 0;
        foreach ($payroll as $pr) {
            $b2 = $payroll[$count]->Bonuses;
            $total_bonus = 0;
            if (count($b2) > 1) {
                $total_bonus = $this->total_bonus($b2);
                if (is_null($total_bonus)) {
                    $total_bonus = 0;
                }
            }

            $total_salarios += $pr->total_salary;

            $pr_item = new PayrollRow(
                $pr->employee_name,
                floatval($pr->employee_salary_week),
                is_null($pr->extra_hours) ? 0 : $pr->extra_hours,
                $total_bonus,
                $pr->total_salary,
                $proyecto_name,
                is_null($pr->bank) ? "" : $pr->bank,
                is_null($pr->account) ? "" : $pr->account,
                is_null($pr->clabe) ? "" : $pr->clabe,
                is_null($pr->imss_number) ? "" : $pr->imss_number,
                $pr->employee_type == 1 ? 'Empleado' : 'Destajista',
                "",
                is_null($pr->comments) ? "" : $pr->comments
            );
            $payrolls_items[] = $pr_item;
            $count += 1;
        }

        $fecha = $this->dateSpanish($end);
        $title = "NOMINA SEMANAL $fecha";
        $project = new PayrollProject(
            $proyecto_name, $payrolls_items
        );

        $header = new HeaderExcel(
            "NOMINA SEMANAL",
            new HeaderObraArray(
                [
                    new HeaderObra($project->getObra(), $project->total())
                ]
            ),
            $fecha
        );
        $hdata = $header->render();

        $table = new PayrollTable(
            $project->getItems()
        );

        $result = array(
            $hdata[0],
            $hdata[1],
            $hdata[2]
        );

        $rows = $table->render();
        foreach ($rows as $row) {
            $result[] = $row;
        }
        return $result;
    }

    private function total_bonus($bonuses)
    {
        $result = 0;
        foreach ($bonuses as $bonus) {
            $result += (int)$bonus->amount;
        }
        return $result;
    }

    private function dateSpanish(string $date)
    {
        //Format YYYY-MM-DD
        $aDia = substr($date, 8, 2);
        $aMes = substr($date, 5, 2);
        $aAnio = substr($date, 0, 4);
        $mes = $this->getMes($aMes);

        return "$aDia DE $mes DE $aAnio";
    }

    private function getMes(int $mes): string
    {
        $meses = [
            1 => 'ENERO',
            2 => 'FEBRERO',
            3 => 'MARZO',
            4 => 'ABRIL',
            5 => 'MAYO',
            6 => 'JUNIO',
            7 => 'JULIO',
            8 => 'AGOSTO',
            9 => 'SEPTIEMBRE',
            10 => 'OCTUBRE',
            11 => 'NOVIEMBRE',
            12 => 'DICIEMBRE'
        ];

        return $meses[$mes];
    }


    public function test_payroll_all_projects()
    {
        $start = '2021-12-01';
        $end = '2021-12-11';
        $proyecto_name = 'MUSEO CCU';
        $proyecto_name = null;
        $controller = new PayrollsController();
        $proy = $controller->reporteTodosLosProyectos($start, $end);


        $this->assertTrue(true);
    }

    public function test_payroll_all_projects_from_class()
    {
        $start = '2021-12-01';
        $end = '2021-12-11';
        $proyecto_name = 'MUSEO CCU';
        $proyecto_name = null;


//        $public_works = PublicWork::all();
//        $pw = PublicWork::where('end_date', '>=', $start)->get();
//        $prw = $this->getAllProjectPayrollsOldie($start, $end);
//        $ppp = $this->uniquePublicWorks($prw);
//        foreach ($public_works as $work) {
//            $w = $work;
//            $s = "";
//        }
        $data = PayrollExcel::exportExcelAllProjects($start, $end);

        $this->assertTrue(true);
    }

    public function test_array_header_render()
    {
        $sums = [];

        for ($i = 0; $i < 3; $i++) {
            $sums[] = [
                "CUITLAJO $i",
                '',
                10019.34,
                '',
                '',
                'NOMINA QUINCENAL BLA BLA BLA'
            ];
        }



        $this->assertTrue(true);
    }


    private function getAllProjectPayrollsOldie($start, $end)
    {
        return Payroll::select('payrolls.id AS id', 'payrolls.days_worked', 'payrolls.hours_worked', 'payrolls.extra_hours',
            'payrolls.total_salary', 'payrolls.date', 'employees.id as employee_id', 'public_works.id as public_work_id',
            'public_works.name AS public_work', 'payrolls.comments')
            ->join('employees', 'employees.id', 'payrolls.employee_id')
            ->join('public_works', 'public_works.id', 'payrolls.public_work_id')
            ->whereBetween('payrolls.date', [$start, $end . ' 23:59:59'])->get();
    }

    private function uniquePublicWorks($payrolls): array
    {
        $result = [];

        foreach ($payrolls as $payroll) {
            $pw = strtoupper($payroll->public_work);
            if (!in_array($pw, $result)) {
                $result[] = $pw;
            }
            $s = "";
        }
        return $result;
    }

}
