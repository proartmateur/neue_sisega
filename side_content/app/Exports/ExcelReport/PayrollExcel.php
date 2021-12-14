<?php

namespace App\Exports\ExcelReport;

use App\Exports\PayrollExport;
use App\Payroll;
use Maatwebsite\Excel\Facades\Excel;
use phpDocumentor\Reflection\Types\This;

class PayrollExcel
{

    public static function exportExcel(string $start, string $end, string $public_work)
    {
//        $start = '2021-12-01';
//        $end = '2021-12-11';
        //$proyecto_name = 'GAP GDL';
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

    private static function total_bonus($bonuses)
    {
        $result = 0;
        foreach ($bonuses as $bonus) {
            $result += (int)$bonus->amount;
        }
        return $result;
    }

    private static function dateSpanish(string $date)
    {
        //Format YYYY-MM-DD
        $aDia = substr($date, 8, 2);
        $aMes = substr($date, 5, 2);
        $aAnio = substr($date, 0, 4);
        $mes = PayrollExcel::getMes($aMes);

        return "$aDia DE $mes DE $aAnio";
    }

    private static function getMes(int $mes): string
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

    private static function projectPayroll(
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
        $total_salarios = 0;
        $total_salarios_destajistas = 0;
        $total_salarios_empleados = 0;
        foreach ($payroll as $pr) {

            $the_bonuses = $payroll[$count]->Bonuses;
            $total_bonus = 0;
            if (count($the_bonuses) > 1) {

                $total_bonus = PayrollExcel::total_bonus($the_bonuses);
                if (is_null($total_bonus)) {
                    $total_bonus = 0;
                }

            }

            $total_salarios += $pr->total_salary;

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
                $total_salarios_destajistas += $pr_item->getTotal();
                $destajistas[] = $pr_item;
            } else {
                $total_salarios_empleados += $pr_item->getTotal();
                $empleados[] = $pr_item;
            }
            $payrolls_items[] = $pr_item;
            $count += 1;
        }

        $destajistas_render = self::buildRenderArray($proyecto_name, $destajistas, $end);
        $empleados_render = self::buildRenderArray($proyecto_name, $empleados, $end);
        $general_render = self::buildRenderArray($proyecto_name, $payrolls_items, $end);
        return [
            'general' => $general_render,
            'empleados' => $empleados_render,
            'destajistas' => $destajistas_render
        ];
    }

    private static function buildRenderArray(
        string $proyecto_name, array $payrolls_items, string $end
    )
    {
        $fecha = PayrollExcel::dateSpanish($end);
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
        return [
            'render' => $result,
            'count' => $table->count()
        ];
    }

    private static function payrollData(string $start, string $end, string $proyecto_name)
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
