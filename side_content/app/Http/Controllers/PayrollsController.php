<?php

namespace App\Http\Controllers;

use App\Bonus;
use App\Employee;
use App\Exports\ExcelReport\HeaderExcel;
use App\Exports\ExcelReport\HeaderObra;
use App\Exports\ExcelReport\HeaderObraArray;
use App\Exports\ExcelReport\PayrollExcel;
use App\Exports\ExcelReport\PayrollProject;
use App\Exports\ExcelReport\PayrollRow;
use App\Exports\ExcelReport\PayrollTable;
use App\Exports\InvoicesExport;
use App\Exports\PayrollExport;
use App\Exports\PayrollReport;
use App\Http\Requests\PayrollsRequest;
use App\Payroll;
use App\PublicWork;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Session;
use Redirect;
use Barryvdh\DomPDF\Facade as PDF;

class PayrollsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $public_works = PublicWork::where('status', '1')->pluck('name', 'id')->toArray();

        return view('payrolls.index', compact('public_works'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PayrollsRequest $request)
    {
        $employee = Employee::find($request['employee_id']);

        $payroll = Payroll::create([
            'employee_id' => $request['employee_id'],
            'days_worked' => $request['days_worked'],
            'hours_worked' => $request['hours_worked'],
            'extra_hours' => $request['extra_hours'],
            'comments' => $request['comments'],
            'date' => $request['date'],
            'public_work_id' => $request['public_work_id'],
            'total_salary' => $request['total_amount']
        ]);


        $fecha = $request['date'];
        $autoNumDia = (int)substr($fecha, 8, 2);
        $autoDiasdelMes = date('t', strtotime($fecha));
        $autoDiaSemana = date('w', strtotime($fecha));//Miern 3

        $adia = $autoDiasdelMes - 6;


        if ($employee->type == 1) {//Solo los empleados tienen bono
            if ($autoNumDia >= $adia && $autoDiaSemana == 3) {//�ltimo mi�rcoles
                $payroll->Bonuses()->attach(1);
                $payroll->Bonuses()->attach(2);
            } else {
                $bonuses = $request['bonuses_list'];

                if (!empty($bonuses)) {
                    foreach ($bonuses as $key => $item) {
                        /*$payroll->Bonuses()->attach($bonuses[$key]['bonus_id'], ['date' => $bonuses[$key]['bonus_date']]);*/
                        if ($bonuses[$key]['bonus_id'] != null) {
                            $payroll->Bonuses()->attach($bonuses[$key]['bonus_id']);
                        }
                    }
                }
            }
        }


        Session::flash('success', 'Empleado creado correctamente.');
        return Redirect::to('/employees/getPayrolls/' . $request['employee_id']);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->role == 1 || Auth::user()->role == 2) {
            $payroll = Payroll::find($id);

            $employee = Employee::find($payroll->employee_id);

            $bonuses_array = [];
            /*$bonuses = Bonus::all()->pluck('name', 'id')->toArray();*/
            $bonuses = Bonus::all();
            foreach ($bonuses as $bonus) {
                $bonuses_array[$bonus->id] = $bonus->name . " - $" . $bonus->amount;
            }

            $extra = Bonus::all()->pluck('amount', 'id')->toArray();

            $count = sizeof($payroll->Bonuses);

            $public_works = PublicWork::where('status', '1')->pluck('name', 'id')->toArray();

            $type = 'Payroll';

            return view('payrolls.edit', compact('payroll', 'employee', 'bonuses_array', 'extra', 'count', 'public_works', 'type'));
        } else {
            return Redirect('/payrolls');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(PayrollsRequest $request, $id)
    {
        /*echo "<pre>";
        var_dump($request->all());
        echo "</pre>";
        return ;*/

        $payroll = Payroll::find($id);


        $payroll->update([
            'days_worked' => $request['days_worked'],
            'hours_worked' => $request['hours_worked'],
            'extra_hours' => $request['extra_hours'],
            'comments' => $request['comments'],
            'date' => $request['date'],
            'public_work_id' => $request['public_work_id'],
            'total_salary' => $request['total_amount']
        ]);

        $bonuses = $request['bonuses_list'];

        $array = [];

        if (!empty($bonuses)) {
            for ($i = 0; $i < count($bonuses); $i++) {
                //$array[$bonuses[$i]['bonus_id']] = ['date' => $bonuses[$i]['bonus_date']];
                if ($bonuses[$i]['bonus_id'] != null) {
                    array_push($array, $bonuses[$i]['bonus_id']);
                }
            }
        }

        $payroll->Bonuses()->sync($array);

        Session::flash('success', 'N�mina actualizada correctamente.');

        if ($request['type'] == 'Employee') {
            return Redirect::to('/employees/getPayrolls/' . $request['employee_id']);
        } else {
            return Redirect::to('/payrolls');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payroll = Payroll::find($id);

        if (!empty($payroll->Bonuses)) {
            $payroll->Bonuses()->detach();
        }

        $payroll->delete();

        Session::flash('success', 'N�mina eliminada correctamente');
    }

    public function editPieceworkerPayroll($payroll_id)
    {
        if (Auth::user()->role < 3) {
            $payroll = Payroll::find($payroll_id);

            $employee = Employee::find($payroll->employee_id);

            $public_works = PublicWork::where('status', '1')->pluck('name', 'id')->toArray();

            $type = 'Payroll';

            return view('payrolls.editPieceworkerPayroll', compact('payroll', 'employee', 'public_works', 'type'));
        } else {
            return Redirect('/payrolls');
        }
    }

    public function actualizar_bonus(Request $request)
    {

        $aPayrollid = $request['aPayrollid'];
        $aValor = $request['aValor'];
        $aChecado = $request['aChecado'];

        $payment = Payroll::find($aPayrollid);

        $bonus = Bonus::find($aValor);


        if ($aChecado == 1) {
            $payment->Bonuses()->attach($aValor);
            $payment->total_salary = $payment->total_salary + $bonus->amount;
            $payment->save();
        } else {
            $payment->Bonuses()->detach($aValor);
            $payment->total_salary = $payment->total_salary - $bonus->amount;
            $payment->save();
        }


        $nuevototalsalario = "$" . number_format(ceil($payment->total_salary), '2', '.', ',');

        $total_bonus = 0;

        foreach ($payment->Bonuses as $bonus) {
            $total_bonus += (int)$bonus->amount;
        }

        $nuevobonos = "$" . number_format($total_bonus, '2', '.', '');


        return [1, $nuevobonos, $nuevototalsalario];
    }

    public function search_payrolls(Request $request, Payroll $payroll)
    {
        $query = $payroll->newQuery();

        $dates = explode(" / ", $request['range']);
        $start_date = explode("-", $dates[0]);
        $end_date = explode("-", $dates[1]);

        $start = $start_date[2] . "-" . $start_date[1] . "-" . $start_date[0];
        $end = $end_date[2] . "-" . $end_date[1] . "-" . $end_date[0];

        $query->select('payrolls.id AS id', 'payrolls.days_worked', 'payrolls.hours_worked', 'payrolls.extra_hours',
            'payrolls.comments', 'payrolls.total_salary', 'payrolls.date', 'employees.id as employee_id',
            'public_works.name AS public_work')
            ->join('employees', 'employees.id', 'payrolls.employee_id')
            ->join('public_works', 'public_works.id', 'payrolls.public_work_id')
            ->whereBetween('payrolls.date', [$start, $end . ' 23:59:59']);

        if ($request['public_work_id'] != '') {
            $query->where('payrolls.public_work_id', $request['public_work_id']);
        }

        $query = $query->get();

        if (sizeof($query) < 1) {
            return "";
        }

        $array = [];
        $total = 0;
        foreach ($query as $key) {
            $bonuses = [];
            $bonuses_dates = [];

            $employee = Employee::find($key->employee_id);

            /*$public_work = PublicWork::find($key->public_work_id);

            $public_work_name = '';
            if(!empty($public_work)){
                $public_work_name = $public_work->name;
            }*/

            $payment = Payroll::find($key->id);
            foreach ($payment->Bonuses as $bonus) {
                array_push($bonuses, $bonus->name);
                array_push($bonuses_dates, $bonus->pivot->date);
            }

            $class = new \stdClass;

            /*echo 'E___'.$key->id.'--';
            echo $employee->type.'<br>';*/

            $class->id = [$key->id, $employee->type];


            $class->photography = '<div class="aIcon" style="background-image: url(\'https://www.sisega.app/' . $employee->photography . '\')"></div>'; //'<img src="https://www.sisega.app/'.$employee->photography.'" width="100">';//$employee->photography//$employee->photography;
            $class->full_name = $employee->name;
            $class->public_work = $key->public_work;
            $class->type = $employee->type == 1 ? 'Empleado' : 'Destajista';

            /*$class->days_worked = $key->days_worked;
            $class->hours_worked = $key->hours_worked;
            $class->extra_hours = $key->extra_hours;
            $class->comments = $key->comments;
            $class->bonuses = $bonuses;
            $class->bonuses_dates = $bonuses_dates;
            $class->date = $key->date;*/


            $total_bonus = 0;

            $bonustiene = '';
            $bonus_uniforme = '';
            $bonus_asistencia = '';
            foreach ($payment->Bonuses as $bonus) {
                $total_bonus += (int)$bonus->amount;

                if ($bonus->id == 1) {
                    $bonus_uniforme = 'checked';
                } else {
                    $bonus_asistencia = 'checked';
                }

            }


            $bonus_todos = Bonus::all();
            foreach ($bonus_todos as $b) {

                if ($b->id == 1) {


                    $bonustiene .= $b->name . ' &nbsp;<input type="checkbox" id="bonus-1" value="1" class="abonuscheck" data-payrollid="' . $key->id . '" ' . $bonus_uniforme . '><br><br>';
                } else {


                    $bonustiene .= $b->name . ' <input type="checkbox" id="bonus-2" value="2" class="abonuscheck" data-payrollid="' . $key->id . '" ' . $bonus_asistencia . '>';
                }

            }


            $payroll_signo = 0;
            if ($payment->extra_hours != '') {
                $payroll_signo = $payment->extra_hours;
            }

            $class->sueldo = "$" . number_format(ceil($employee->salary_week), '2', '.', ',');
            $class->extras = "$" . number_format($payroll_signo, '2', '.', '');


            $class->bono = "<div id='bonostotal-" . $payment->id . "'>$" . number_format($total_bonus, '2', '.', '') . "</div>";

            $total_final = $key->total_salary;//+$total_bonus+$payroll_signo;

            $class->total_salary = "<div id='sueldototal-" . $payment->id . "'>$" . number_format(ceil($total_final), '2', '.', ',') . "</div>";


            $class->htmlbonus = $bonustiene;
            //$total += (float)$key->total_salary;

            if ($employee->type != 1) {//Sino es empleado, no debe tener bonos
                $class->htmlbonus = '';
            }


            $total += number_format(ceil($total_final), '2', '.', '');


            array_push($array, $class);
        }

        $total = "$" . number_format($total, '2', '.', ',');

        return [$array, $total];
    }

    public function export_pdf(Request $request)
    {
        $dates = explode(" / ", $request['date_range']);
        $start_date = explode("-", $dates[0]);
        $end_date = explode("-", $dates[1]);

        $start = $start_date[2] . "-" . $start_date[1] . "-" . $start_date[0];
        $end = $end_date[2] . "-" . $end_date[1] . "-" . $end_date[0];

        $fileName = \Str::random(10) . "_" . time() . ".pdf";

        if ($request['public_work'] != '') {
            $payrolls = Payroll::select('payrolls.id AS id', 'payrolls.days_worked', 'payrolls.hours_worked', 'payrolls.extra_hours',
                'payrolls.total_salary', 'payrolls.date', 'employees.id as employee_id', 'public_works.id as public_work_id',
                'public_works.name AS public_work', 'payrolls.comments')
                ->join('employees', 'employees.id', 'payrolls.employee_id')
                ->join('public_works', 'public_works.id', 'payrolls.public_work_id')
                ->whereBetween('payrolls.date', [$start, $end . ' 23:59:59'])
                ->where('payrolls.public_work_id', $request['public_work'])->get();

            $array = [];
            $total = 0;

            foreach ($payrolls as $payroll) {
                $actual_payroll = Payroll::find($payroll->id);

                $total_bonus = 0;
                foreach ($actual_payroll->Bonuses as $bonus) {
                    $total_bonus += (int)$bonus->amount;
                }

                $employee = Employee::find($payroll->employee_id);

                $class = new \stdClass;
                $class->full_name = $employee->name;//.' '.$employee->last_name;
                $class->total_bonus = "$" . number_format(ceil($total_bonus), '2', '.', ',');;
                $class->extra_hours = "$" . number_format(ceil($payroll->extra_hours), '2', '.', ',');
                $class->public_work = $payroll->public_work;
                $class->bank = $employee->bank;
                $class->account = $employee->account;
                $class->clabe = $employee->clabe;
                $class->stall = $employee->type == 1 ? 'Empleado' : 'Destajista';

                $class->total_salary = "$" . number_format(ceil($payroll->total_salary), '2', '.', ',');
                $total += (float)$payroll->total_salary;

                array_push($array, $class);
            }

            $total = "$" . number_format(ceil($total), '2', '.', ',');

            $public_work = PublicWork::find($request['public_work']);

            $pdf = PDF::loadView('templates.payrolls_filtered_report', compact('array', 'public_work', 'total', 'end'))
                ->setPaper('a4', 'landscape');

            return $pdf->download('reporte_' . $fileName);
        } else {
            $payrolls = Payroll::select('payrolls.id AS id', 'payrolls.days_worked', 'payrolls.hours_worked', 'payrolls.extra_hours',
                'payrolls.total_salary', 'payrolls.date', 'employees.id as employee_id', 'public_works.id as public_work_id',
                'public_works.name AS public_work', 'payrolls.comments')
                ->join('employees', 'employees.id', 'payrolls.employee_id')
                ->join('public_works', 'public_works.id', 'payrolls.public_work_id')
                ->whereBetween('payrolls.date', [$start, $end . ' 23:59:59'])->get();

            $array = [];
            $total = 0;
            $public_works_array = [];


            $total_salarios = 0;//Todos los salarios
            $total_por_obra = [];//Salarios total por obra

            foreach ($payrolls as $payroll) {
                $actual_payroll = Payroll::find($payroll->id);

                $total_bonus = 0;
                foreach ($actual_payroll->Bonuses as $bonus) {
                    $total_bonus += (int)$bonus->amount;
                }

                $employee = Employee::find($payroll->employee_id);

                $class = new \stdClass;
                $class->full_name = $employee->name;//.' '.$employee->last_name;
                $class->total_bonus = "$" . number_format(ceil($total_bonus), '2', '.', ',');;
                $class->extra_hours = "$" . number_format(ceil($payroll->extra_hours), '2', '.', ',');
                $class->public_work = $payroll->public_work;
                $class->bank = $employee->bank;
                $class->account = $employee->account;
                $class->clabe = $employee->clabe;
                $class->stall = $employee->type == 1 ? 'Empleado' : 'Destajista';

                $class->total_salary = "$" . number_format(ceil($payroll->total_salary), '2', '.', ',');
                $total += (float)$payroll->total_salary;

                array_push($array, $class);

                if (!in_array($payroll->public_work_id, $public_works_array)) {
                    array_push($public_works_array, $payroll->public_work_id);
                }

                $total_salarios += number_format(ceil($payroll->total_salary), '2', '.', '');

                if (isset($total_por_obra[$payroll->public_work])) {
                    $total_por_obra[$payroll->public_work] += number_format(ceil($payroll->total_salary), '2', '.', '');
                } else {
                    $total_por_obra[$payroll->public_work] = number_format(ceil($payroll->total_salary), '2', '.', '');
                }
            }

            $total = "$" . number_format(ceil($total), '2', '.', ',');

            $total_salarios = "$" . number_format(ceil($total_salarios), '2', '.', ',');


            /*$public_works = [];

            foreach ($public_works_array as $item){
                $public_work = PublicWork::find($item);
                $amounts = Payroll::where('public_work_id', $item)->get();

                $public_work_total = 0;
                foreach ($amounts as $amount) {
                    $public_work_total += (float)$amount->total_salary;


                }

                $class = new \stdClass;
                $class->name = $public_work->name;
                $class->amount = "$".number_format(ceil($public_work_total),'2','.',',');

                array_push($public_works, $class);


            }
                $size = (sizeof($public_works))*45;
            */

            /*$view = \View::make('templates.payrolls_report',compact('array', 'public_works', 'total', 'end'));
            $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8');
            $html2pdf->writeHTML($view);
            $html2pdf->output('reporte_'.$fileName,'D');*/


            $pdf = PDF::loadView('templates.payrolls_report', compact('array', 'end', 'total_salarios', 'total_por_obra'))->setPaper('a4', 'landscape');

            return $pdf->download('reporte_' . $fileName);
        }
    }

    public function export_excel(Request $request)
    {
        $date_range = $this->obtenerFechas($request['date_range_excel']);
        $start = $date_range['start'];
        $end = $date_range['end'];
        //21
        //$proyecto_name = 'GAP GDL';

        if (!is_null($request['public_work_excel'])) {
            $public_work = PublicWork::find(['id' => $request['public_work_excel']])->first();
            $proyecto_name = $public_work->name;
            return PayrollExcel::exportExcel($start, $end, $proyecto_name);
        }

        return $this->reporteTodosLosProyectos($start, $end);
    }

    private function obtenerFechas(string $date_range){
        $now = Carbon::now();
        $week_init = $now->subDays(7);
        if (is_null($date_range)) {
            return "Debe seleccionar un rango de fechas";
        }

        $dates = explode(" / ", $date_range);
        $start_date = explode("-", $dates[0]);
        $end_date = explode("-", $dates[1]);

        $start = $start_date[2] . "-" . $start_date[1] . "-" . $start_date[0];
        $end = $end_date[2] . "-" . $end_date[1] . "-" . $end_date[0];

        if ($start === $end) {
            $start = $week_init->format('Y-m-d');
        }

        return [
            'start' => $start,
            'end' => $end
        ];

    }

    private function reporteTodosLosProyectos($start, $end){
        $payrolls = Payroll::select('payrolls.id AS id', 'payrolls.days_worked', 'payrolls.hours_worked', 'payrolls.extra_hours',
            'payrolls.total_salary', 'payrolls.date', 'employees.id as employee_id', 'public_works.id as public_work_id',
            'public_works.name AS public_work', 'payrolls.comments')
            ->join('employees', 'employees.id', 'payrolls.employee_id')
            ->join('public_works', 'public_works.id', 'payrolls.public_work_id')
            ->whereBetween('payrolls.date', [$start, $end . ' 23:59:59'])->get();

        $count_payrolls = count($payrolls);
        if($count_payrolls === 0) {
            $public_work = PublicWork::all()->first();
            $proyecto_name = $public_work->name;
            $proyecto_name = "Todos (0 registros)";
            return PayrollExcel::exportExcel($start, $end, $proyecto_name);
        }

        $array = [];

        $data = [];

        array_push($data, ["Nombre completo", " ", "Sueldo", "Horas extra", "Bono", "Total", "Obra", "Banco", "Cuenta", "CLABE", "IMSS", "Tipo", "Firma", "Comentarios"]);


        $total_salarios = 0;
        $total_por_obra = [];

        foreach ($payrolls as $payroll) {
            $employee = Employee::find($payroll->employee_id);

            $actual_payroll = Payroll::find($payroll->id);

            $total_bonus = 0;
            foreach ($actual_payroll->Bonuses as $bonus) {
                $total_bonus += (int)$bonus->amount;
            }

            if ($payroll->extra_hours != '') {
                $payroll_signo = $payroll->extra_hours;
                //$payroll_signo = "$".$payroll->extra_hours;
            } else {
                $payroll_signo = $payroll->extra_hours;
            }


            /*array_push($data, [
                $employee->name.' '.$employee->last_name,
                "$".$total_bonus,
                $payroll_signo,
                "$".number_format(ceil($payroll->total_salary),'2','.',','),
                $payroll->public_work,
                $employee->bank,
                $employee->account,
                $employee->clabe,
                $employee->type == 1 ? 'Empleado' : 'Destajista',
                ""
            ]);*/

            array_push($data, [
                //$employee->photography." ",
                $employee->name . "",/*' '.$employee->last_name,*/
                " ",
                number_format($employee->salary_week, '2', '.', ''),
                $payroll_signo,
                number_format($total_bonus, '2', '.', ''),


                number_format(ceil($payroll->total_salary), '2', '.', ''),
                //"$".number_format(ceil($payroll->total_salary),'2','.',','),
                $payroll->public_work . " ",
                $employee->bank . " ",
                $employee->account . " ",
                $employee->clabe . " ",
                $employee->imss_number . " ",
                $employee->type == 1 ? 'Empleado' : 'Destajista',
                "",
                $payroll->comments . " "
            ]);

            /*$class = new \stdClass;
            $class->full_name = $employee->name.' '.$employee->last_name;
            $class->extra_hours = $payroll->extra_hours;
            $class->salary = $employee->salary_week;
            $class->public_work = $payroll->public_work;
            $class->bank = $employee->bank;
            $class->account = $employee->account;
            $class->clabe = $employee->clabe;

            $class->total_salary = "$".number_format(ceil($payroll->total_salary),'2','.',',');
            $total += (float)$payroll->total_salary;

            array_push($array, $class);*/

            $total_salarios += number_format(ceil($payroll->total_salary), '2', '.', '');

            if (isset($total_por_obra[$payroll->public_work])) {
                $total_por_obra[$payroll->public_work] += number_format(ceil($payroll->total_salary), '2', '.', '');
            } else {
                $total_por_obra[$payroll->public_work] = number_format(ceil($payroll->total_salary), '2', '.', '');
            }


        }


        $aDia = substr($end, 8, 2);
        $aMes = substr($end, 5, 2);
        $aAnio = substr($end, 0, 4);

        $aMeses = ['01' => 'ENERO',
            '02' => 'FEBRERO',
            '03' => 'MARZO',
            '04' => 'ABRIL',
            '05' => 'MAYO',
            '06' => 'JUNIO',
            '07' => 'JULIO',
            '08' => 'AGOSTO',
            '09' => 'SEPTIEMBRE',
            '10' => 'OCTUBRE',
            '11' => 'NOVIEMBRE',
            '12' => 'DICIEMBRE'];

        $aCadena = 'NOMINA SEMANAL ' . $aDia . ' DE ' . $aMeses[$aMes] . ' DE ' . $aAnio;

        array_unshift($data, [' ']);
        array_unshift($data, ["TOTAL ", "", number_format($total_salarios, '2', '.', ''), '', '', '', '', $aCadena . ' ']);
        //array_unshift($data, ['NOMINA SEMANAL ']);
        array_unshift($data, [count($total_por_obra), $total_por_obra]);

        // Aquí se genera el excel
        $export = new PayrollReport($data);

        return Excel::download($export, 'Reporte.xlsx');
    }



    public function clonar(Request $request, Payroll $payroll)
    {


        $fechapago = $request['date'];

        $query = $payroll->newQuery();

        $dates = explode(" / ", $request['clonar-rango']);
        $start_date = explode("-", $dates[0]);
        $end_date = explode("-", $dates[1]);

        $start = $start_date[2] . "-" . $start_date[1] . "-" . $start_date[0];
        $end = $end_date[2] . "-" . $end_date[1] . "-" . $end_date[0];

        $query->select('payrolls.id AS id', 'payrolls.days_worked', 'payrolls.hours_worked', 'payrolls.extra_hours',
            'payrolls.comments', 'payrolls.total_salary', 'payrolls.date', 'employees.id as employee_id',
            'public_works.name AS public_work', 'public_works.id as public_work_id', 'payrolls.comments', 'payrolls.date', 'payrolls.total_salary', 'employees.type as tipo_empleado', 'employees.salary_week as salary_week')
            ->join('employees', 'employees.id', 'payrolls.employee_id')
            ->join('public_works', 'public_works.id', 'payrolls.public_work_id')
            ->whereBetween('payrolls.date', [$start, $end . ' 23:59:59']);


        if ($request['clonar-obra'] != '0') {
            $query->where('payrolls.public_work_id', $request['clonar-obra']);
        }

        $query = $query->get();


        foreach ($query as $key) {

            $salario = 0;

            if ($key->salary_week == '' || $key->salary_week == NULL) {
                $salario = 0;
            } else {
                $salario = $key->salary_week;
            }

            //echo $key->employee_id.'---'.$salario .'<br><br>';
            if ($key->tipo_empleado == 1) {
                $payroll = Payroll::create([
                    'clonado' => 1,
                    'employee_id' => $key->employee_id,
                    'days_worked' => 6,//$key->days_worked,
                    'hours_worked' => 48,//$key->hours_worked,
                    //'extra_hours' => '',//$key->extra_hours,
                    'comments' => '',//$key->comments,
                    'date' => $fechapago,
                    'public_work_id' => $key->public_work_id,
                    'total_salary' => ($key->salary_week == NULL) ? 0 : $key->salary_week//$key->total_salary
                ]);
            } else {
                $payroll = Payroll::create([
                    'clonado' => 1,
                    'employee_id' => $key->employee_id,
                    'comments' => '',//$key->comments,
                    'date' => $fechapago,
                    'public_work_id' => $key->public_work_id,
                    'total_salary' => 0//$key->total_salary
                ]);
            }


            $fecha = $fechapago;
            $autoNumDia = (int)substr($fecha, 8, 2);
            $autoDiasdelMes = date('t', strtotime($fecha));
            $autoDiaSemana = date('w', strtotime($fecha));//Miern 3

            $adia = $autoDiasdelMes - 6;


            if ($key->tipo_empleado == 1) {//Solo los empleados tienen bono
                if ($autoNumDia >= $adia && $autoDiaSemana == 3) {//�ltimo mi�rcoles
                    $payroll->Bonuses()->attach(1);
                    $payroll->Bonuses()->attach(2);

                    $bonus1 = Bonus::find(1);
                    $bonus2 = Bonus::find(2);

                    $payroll->total_salary = $payroll->total_salary + $bonus1->amount + $bonus2->amount;
                    $payroll->save();
                }
            }


        }


        $fechapagobuscar = explode("-", $fechapago);
        $fpb = $fechapagobuscar[2] . '-' . $fechapagobuscar[1] . '-' . $fechapagobuscar[0];


        return redirect()->route('payrolls.edicion.masiva', [$fpb, $fpb, $request['clonar-obra']]);

        //return [$array, $total];

        //return view('admin.productos.index', compact('array','total','fechapago'));

    }

    public function edicionMasiva($fecha1, $fecha2, $obra, Payroll $payroll)
    {

        $public_works = PublicWork::where('status', '1')->pluck('name', 'id')->toArray();

        // $fechapago = $request['date'];

        //$rango = '24-11-2021 / 25-11-2021';//$request['clonar-rango'];
        //$obra = 1;//$request['clonar-obra'];


        //$rango = $request['clonar-rango'];
        //$obra = $request['clonar-obra'];


        $query = $payroll->newQuery();

        //$dates = explode(" / ", $rango);
        //$start_date = explode("-", $dates[0]);
        //$end_date = explode("-", $dates[1]);
        $start_date = explode("-", $fecha1);
        $end_date = explode("-", $fecha2);

        $start = $start_date[2] . "-" . $start_date[1] . "-" . $start_date[0];
        $end = $end_date[2] . "-" . $end_date[1] . "-" . $end_date[0];


        $query->select('payrolls.id AS id', 'payrolls.days_worked', 'payrolls.hours_worked', 'payrolls.extra_hours',
            'payrolls.comments', 'payrolls.total_salary', 'payrolls.date', 'employees.id as employee_id',
            'public_works.name AS public_work', 'public_works.id as public_work_id', 'payrolls.comments', 'payrolls.date', 'payrolls.total_salary', 'employees.type as tipo_empleado', 'employees.salary_week as salary_week', 'employees.name as name_empleado', 'employees.photography as foto')
            ->join('employees', 'employees.id', 'payrolls.employee_id')
            ->join('public_works', 'public_works.id', 'payrolls.public_work_id')
            ->whereBetween('payrolls.date', [$start, $end . ' 23:59:59']);


        if ($obra != '0') {
            $query->where('payrolls.public_work_id', $obra);
        }

        $query = $query->get();


        /*foreach ($query as $key){
            echo $key->public_work_id;
        }*/

        $bonus_todos = Bonus::all();


        return view('payrolls.edicion-masiva', compact('query', 'bonus_todos', 'public_works'));


    }


    public function edicionMasicaGuardaUnaNomina(PayrollsRequest $request)
    { /*echo 'JOLA';
        echo "<pre>";
        var_dump($request->all());
        echo "</pre>";*/


        $payroll = Payroll::find($request['id']);

        $empleado = Employee::find($request['employee_id']);


        if ($empleado->type == 1) {
            $payroll->update([
                'days_worked' => $request['days_worked'],
                'hours_worked' => $request['hours_worked'],
                'extra_hours' => $request['extra_hours'],
                'comments' => $request['comments'],
                'date' => $request['date'],
                'public_work_id' => $request['public_work_id'],
                'total_salary' => $request['total_salary']
            ]);


            $tiene_bono_uniforme = 0;
            $tiene_bono_asistencia = 0;

            foreach ($payroll->Bonuses as $bonus) {

                if ($bonus->id == 1) {
                    $tiene_bono_uniforme = 1;
                }
                if ($bonus->id == 2) {
                    $tiene_bono_asistencia = 1;
                }


            }


            if (isset($request['bono_uniforme'])) {

                if ($tiene_bono_uniforme == 0) {

                    $bonus = Bonus::find(1);
                    $payroll->Bonuses()->attach(1);
                    /*$payroll->total_salary = $payroll->total_salary + $bonus->amount;
                    $payroll->save();*/

                }

            } else {

                if ($tiene_bono_uniforme == 1) {

                    $payroll->Bonuses()->detach(1);
                    /*$payroll->total_salary = $payroll->total_salary - $bonus->amount;
                    $payroll->save();*/
                }
            }

            if (isset($request['bono_asistencia'])) {

                if ($tiene_bono_asistencia == 0) {
                    $bonus = Bonus::find(2);
                    $payroll->Bonuses()->attach(2);
                    /*$payroll->total_salary = $payroll->total_salary + $bonus->amount;
                    $payroll->save();*/
                }
            } else {
                if ($tiene_bono_asistencia == 1) {
                    $payroll->Bonuses()->detach(2);
                    /*$payroll->total_salary = $payroll->total_salary - $bonus->amount;
                    $payroll->save();*/
                }
            }


        }//Fin  if($empleado->type == 1)
        else {
            $payroll->update([
                'comments' => $request['comments'],
                'date' => $request['date'],
                'public_work_id' => $request['public_work_id'],
                'total_salary' => $request['total_salary']
            ]);
        }


        return 1;
    }
}
