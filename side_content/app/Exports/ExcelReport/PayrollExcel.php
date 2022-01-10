<?php

namespace App\Exports\ExcelReport;

use App\Exports\PayrollExport;
use App\Payroll;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use phpDocumentor\Reflection\Types\This;

class PayrollExcel
{

    public static function exportExcel(string $start, string $end, string $public_work)
    {
//        $start = '2021-12-01';
//        $end = '2021-12-11';

        $data = PayrollExcel::projectPayroll(
            $public_work,
            $start,
            $end
        );

        $general = $data['general'];
        $destajistas = $data['destajistas'];
        $empleados = $data['empleados'];

        $export = new PayrollExport($general, $destajistas, $empleados);
        return Excel::download($export, "Reporte_$end-$public_work.xlsx");
    }

    public static function exportExcelAllProjects(string $start, string $end)
    {
//        $start = '2021-12-01';
//        $end = '2021-12-11';


        $prw = self::getAllProjectPayrollsOldie($start, $end);
        $ppp = self::uniquePublicWorks($prw);
        // $public_work = $ppp[0];

        $general = [];
        $destajistas = [];
        $empleados = [];

        $counter = 0;
        $g_sums = [];
        $d_sums = [];
        $e_sums = [];

        $g_pays = [];
        $d_pays = [];
        $e_pays = [];
        foreach ($ppp as $public_work) {

            $data = PayrollExcel::projectPayroll(
                "$public_work",
                $start,
                $end
            );

            $general = $data['general'];
            $destajistas = $data['destajistas'];
            $empleados = $data['empleados'];

            $g_sums[] = $general['render'][1];
            $d_sums[] = $destajistas['render'][1];
            $e_sums[] = $empleados['render'][1];


            $d_pays = array_merge($d_pays, PayrollExcel::isolatePayrollsFromRender($destajistas));
            $g_pays = array_merge($g_pays, PayrollExcel::isolatePayrollsFromRender($general));
            $e_pays = array_merge($e_pays, PayrollExcel::isolatePayrollsFromRender($empleados));

            /*if ($counter == 2) {
                break;
            }*/
            $counter += 1;

        }

        $general_sum = PayrollExcel::cleanAllProjectsHeader($g_sums);
        $destajistas_sum = PayrollExcel::cleanAllProjectsHeader($d_sums);
        $empleados_sum = PayrollExcel::cleanAllProjectsHeader($e_sums);


        //region Build Payrolls extended

        $general = PayrollExcel::reRenderAllProjectsPayrolls($general, $general_sum, $g_pays);
        $destajistas = PayrollExcel::reRenderAllProjectsPayrolls($destajistas, $destajistas_sum, $d_pays);
        $empleados = PayrollExcel::reRenderAllProjectsPayrolls($empleados, $empleados_sum, $e_pays);



        $export = new PayrollExport($general, $destajistas, $empleados);

        return Excel::download($export, "Reporte_$end-$public_work.xlsx");

    }

    private static function reRenderAllProjectsPayrolls($original, $summaries, $payrolls): array
    {
        $result = [];

        $top_line = $original['render'][0];

        //New Header
        $head_of_table_br = $original['render'][2];
        $head_of_table_cols_names = $original['render'][3];
        //payrolls

        $result = [$top_line];
        $result = array_merge($result, $summaries);
        $result[] = $head_of_table_br;
        $result[] = $head_of_table_cols_names;
        $result = array_merge($result, $payrolls);
        $result = [
            'render' => $result,
            'count' => count($payrolls)
        ];

        return $result;
    }

    private static function isolatePayrollsFromRender($payrolls): array
    {
        $result = [];
        $render_payrolls = $payrolls['render'];
        $max = count($render_payrolls);
        for ($i = 4; $i < $max; $i++) {
            $result[] = $render_payrolls[$i];
        }

        return $result;
    }

    private static function cleanAllProjectsHeader(array $sums): array
    {
        //region Clean Header

        $count = 0;
        $clean = [];
        $total = 0;
        foreach ($sums as $sum) {
            if ($count != 0) {
                $sum[5] = '';
            }
            $total += $sum[2];
            $clean[] = $sum;
            $count += 1;
        }

        $clean[0][3] = $total;
        $clean = array_merge([["", "", "", ""]], $clean);

        //endregion
        return $clean;
    }

    private
    static function getAllProjectPayrollsOldie($start, $end)
    {
        return Payroll::select('payrolls.id AS id', 'payrolls.days_worked', 'payrolls.hours_worked', 'payrolls.extra_hours',
            'payrolls.total_salary', 'payrolls.date', 'employees.id as employee_id', 'public_works.id as public_work_id',
            'public_works.name AS public_work', 'payrolls.comments')
            ->join('employees', 'employees.id', 'payrolls.employee_id')
            ->join('public_works', 'public_works.id', 'payrolls.public_work_id')
            ->whereBetween('payrolls.date', [$start, $end . ' 23:59:59'])->get();
    }

    private
    static function uniquePublicWorks($payrolls): array
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

    private
    static function total_bonus($bonuses)
    {
        $result = 0;
        foreach ($bonuses as $bonus) {
            $result += (int)$bonus->amount;
        }
        return $result;
    }

    private
    static function dateSpanish(string $date)
    {
        //Format YYYY-MM-DD
        $aDia = substr($date, 8, 2);
        $aMes = substr($date, 5, 2);
        $aAnio = substr($date, 0, 4);
        $mes = PayrollExcel::getMes($aMes);

        return "$aDia DE $mes DE $aAnio";
    }

    private
    static function getMes(int $mes): string
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

    private
    static function projectPayroll(
        string $proyecto_name,
        string $start,
        string $end
    )
    {

        $payrolls_items = [];
        $destajistas = [];
        $empleados = [];

        $payroll = PayrollExcel::payrollData($start, $end, $proyecto_name);

        $count = 0;

        foreach ($payroll as $pr) {

            $total_bonus = self::calcBonusesFromPayroll($payroll[$count]->Bonuses);

            //region Item
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
            //endregion

            if ($pr_item->getTipo() === 'Destajista') {
                $destajistas[] = $pr_item;
            } else {
                $empleados[] = $pr_item;
            }
            $payrolls_items[] = $pr_item;

            $count += 1;
        }

        $destajistas_render = self::buildRenderArray($proyecto_name, $destajistas, $start, $end);
        $empleados_render = self::buildRenderArray($proyecto_name, $empleados, $start, $end);
        $general_render = self::buildRenderArray($proyecto_name, $payrolls_items, $start, $end);

        return [
            'general' => $general_render,
            'empleados' => $empleados_render,
            'destajistas' => $destajistas_render
        ];
    }

    private
    static function calcBonusesFromPayroll($the_bonuses)
    {
        $total_bonus = 0;
        if (count($the_bonuses) > 1) {

            $total_bonus = PayrollExcel::total_bonus($the_bonuses);
            if (is_null($total_bonus)) {
                $total_bonus = 0;
            }
        }
        return $total_bonus;
    }


    private
    static function buildRenderArray(
        string $proyecto_name, array $payrolls_items, string $start, string $end
    )
    {
        $fecha = PayrollExcel::dateSpanish($end);
        $project = new PayrollProject(
            $proyecto_name, $payrolls_items
        );

        $tipo_payroll = self::payrollType($start, $end);
        $header = new HeaderExcel(
            "NOMINA $tipo_payroll",
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
        return [
            'render' => $result,
            'count' => $table->count()
        ];
    }

    private
    static function payrollType(string $start, string $end)
    {
        $s = Carbon::createFromFormat('Y-m-d', $start);
        $e = Carbon::createFromFormat('Y-m-d', $end);
        $delta = $s->diffInDays($e);

        $semana = $delta <= 7;
        $quincena = $delta <= 15;
        $mensual = $delta <= 28;
        $bimensual = $delta <= 56;
        $trimestral = $delta <= (28 * 3);

        $resultado = 'SEMANAL';


        if ($trimestral) {
            $resultado = 'TRIIMESTRAL';
        }

        if ($bimensual) {
            $resultado = 'BIMESTRAL';
        }

        if ($mensual) {
            $resultado = 'MENSUAL';
        }

        if ($quincena) {
            $resultado = 'QUINCENAL';
        }

        if ($semana) {
            $resultado = 'SEMANAL';
        }


        return $resultado;
    }

    private
    static function payrollData(string $start, string $end, string $proyecto_name)
    {
        return Payroll::select(
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
    }
}
