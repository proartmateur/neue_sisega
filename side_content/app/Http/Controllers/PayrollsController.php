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


        /*$payrolls = Payroll::all();

        $total = 0;
        foreach ($payrolls as $payroll){
            $public_work = PublicWork::find($payroll->public_work_id);

            if(!$public_work){
                $payroll->public_work = '';
            }else{
                $payroll->public_work = $public_work->name;
            }

            $employee = Employee::find($payroll->employee_id);

            $payroll->full_name = $employee->name.' '.$employee->last_name;
            $payroll->bank = $employee->bank;
            $payroll->account = $employee->account;
            $payroll->clabe = $employee->clabe;
            $payroll->format_total = "$".number_format(ceil($payroll->total_salary),'2','.',',');

            $total += (float)$payroll->total_salary;
        }*/

        $public_works = PublicWork::where('status', '1')->pluck('name', 'id')->toArray();


        //$public_works = PublicWork::all()->pluck('name', 'id')->toArray();

        /*$total = ceil($total);

        $total = "$".number_format($total,'2','.',',');*/

        return view('payrolls.index', compact('public_works'));
        /*return view('templates.payrolls_report');*/
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
        $date_range = $request['date_range_excel'];
        $now = Carbon::now();
        $week_init = $now->subDays(7);
        if (is_null($date_range)) {
            return "Debe seleccionar un rango de fechas";
        }

        $dates = explode(" / ", $request['date_range_excel']);
        $start_date = explode("-", $dates[0]);
        $end_date = explode("-", $dates[1]);

        $start = $start_date[2] . "-" . $start_date[1] . "-" . $start_date[0];
        $end = $end_date[2] . "-" . $end_date[1] . "-" . $end_date[0];

        if ($start === $end) {
            $start = $week_init->format('Y-m-d');
        }

        $proyecto_name = 'GAP GDL';

        if (!is_null($request['public_work_excel'])) {
            $public_work = PublicWork::find(['id' => $request['public_work_excel']])->first();
            $proyecto_name = $public_work->name;
            return PayrollExcel::exportExcel($start, $end, $proyecto_name);
        }

        // 2021-12-01
        // 2021-12-11
        $fileName = \Str::random(10) . "_" . time() . ".pdf";

        if ($request['public_work_excel'] != '') {
            $payrolls = Payroll::select('payrolls.id AS id', 'payrolls.days_worked', 'payrolls.hours_worked', 'payrolls.extra_hours',
                'payrolls.total_salary', 'payrolls.date', 'employees.id as employee_id', 'public_works.id as public_work_id',
                'public_works.name AS public_work', 'payrolls.comments')
                ->join('employees', 'employees.id', 'payrolls.employee_id')
                ->join('public_works', 'public_works.id', 'payrolls.public_work_id')
                ->whereBetween('payrolls.date', [$start, $end . ' 23:59:59'])
                ->where('payrolls.public_work_id', $request['public_work_excel'])->get();
            logger("exportando excel...");
            logger($payrolls[0]);
            $array = [];

            $data = [];

//            array_push($data, ["Nombre completo"," ","Sueldo", "Horas extra","Bono",  "Total", "Obra", "Banco", "Cuenta", "CLABE","IMSS", "Tipo", "Firma", "Comentarios"]);
            array_push($data, ["Nombre completo", " ", "Sueldo", "Horas extra", "Bono", "Total", "Obra", "Banco", "Cuenta", "CLABE", "IMSS", "Tipo", "Firma", "Comentarios"]);

            $total_salarios = 0;
            $nombre_obra = '';
            foreach ($payrolls as $payroll) {
                $employee = Employee::find($payroll->employee_id);

                $actual_payroll = Payroll::find($payroll->id);
                logger($actual_payroll);
                logger("END ------------");
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


                /* array_push($data, [
                     $employee->name." ",
                     "$".$total_bonus,
                     $payroll_signo,
                     "$".number_format(ceil($payroll->total_salary),'2','.',','),
                     $payroll->public_work." ",
                     $employee->bank." ",
                     $employee->account." ",
                     $employee->clabe." ",
                     $employee->type == 1 ? 'Empleado' : 'Destajista',
                     ""
                 ]);*/

                array_push($data, [

                    //$employee->photography." ",
                    $employee->name . " ",/*' '.$e
                    $employee->name." ",/*' '.$employee->last_name,*/
                    " ",
                    number_format($employee->salary_week, '2', '.', ''),
                    $payroll_signo,
                    number_format($total_bonus, '2', '.', ''),


                    number_format(ceil($payroll->total_salary), '2', '.', ''),
                    //"$".number_format(ceil($payroll->total_salary),'2','.',','),
                    $payroll->public_work,
                    $employee->bank . " ",
                    $employee->account . " ",
                    $employee->clabe . " ",
                    $employee->imss_number . " ",
                    $employee->type == 1 ? 'Empleado' : 'Destajista',
                    "",
                    $payroll->comments . " "
                ]);

                $total_salarios += number_format(ceil($payroll->total_salary), '2', '.', '');

                $nombre_obra = $payroll->public_work . " ";
            }


            $aDia = substr($end, 8, 2);
            $aMes = substr($end, 5, 2);
            $aAnio = substr($end, 0, 4);

            $aMeses = [
                '01' => 'ENERO',
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
                '12' => 'DICIEMBRE'
            ];

            $aCadena = 'NOMINA SEMANAL ' . $aDia . ' DE ' . $aMeses[$aMes] . ' DE ' . $aAnio;


            array_unshift($data, [' ']);
//            array_unshift($data, [
//                'A ',
//                'B',
//                'C',
//                'D',
//                'E',
//                'F',
//                'G',
//                'H',
//                'I',
//                'J',
//                'K',
//                'L',
//                'M'
//            ]);
            array_unshift($data, [trim($nombre_obra) . "1--", "", number_format($total_salarios, '2', '.', ''), '', '', '', $aCadena . ' ']);


            array_unshift($data, ['NOMINA SEMANAL ']);
            array_unshift($data, [-1]);


            /*array_push($data, [
                    " ",

                     " ",

                    " ",
                   number_format($total_salarios,'2','.',''),

                    " ",
                    " ",
                    " ",
                    " ",
                   " ",
                    ""
                ]);*/

            $export = new PayrollReport($data);
            //$export->sheets([]);

            /*$export->sheet->setColumnFormat(array(
             'A' => '@',
             'B' => '@',
             'D' => '@',
             'E' => '@',
             'F' => '@',
             'G' => '@',
             'H' => '@',
             'I' => '@',
             'J' => '@',
           ));*/

            //$stored = Excel::store($export, '/Users/ennima/Devs/neue_studio/SISEGA/docker_laravel8_php8_apache/src/side_content/tests/Unit/Reporte.xlsx');
            return Excel::download($export, 'Reporte.xlsx');


        } else {
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

    private function buildEncabezadoExcel(
        string          $nomina_type,
        HeaderObraArray $obras
    )
    {

    }

    private function exportExcelTest()
    {
        $start = '2021-12-01';
        $end = '2021-12-11';
        $proyecto_name = 'GAP GDL';
        $data = $this->projectPayroll(
            $proyecto_name,
            $start,
            $end
        );
        //$export = new PayrollReport($data);
        //$export = new InvoicesExport(2021);
        $export = new PayrollExport($data['general'], $data['general_count']);
        return Excel::download($export, 'Reporte_test2.xlsx');
    }

    /**
     * @throws \Exception
     */
    private function getExcelData()
    {
        $items = $this->getItems();

        $project = new PayrollProject(
            'My Life',
            $items
        );

        $header = new HeaderExcel(
            "NOMINA mensual",
            new HeaderObraArray(
                [
                    new HeaderObra($project->getObra(), $project->total())
                ]
            ),
            "09 de diciembre 2021"
        );
        $hdata = $header->render();

        $table = new PayrollTable(
            $project->getItems()
        );

        $result = array(
            $hdata[0],
            $hdata[1],
            $hdata[2],
//            5 =>
//                array(
//                    0 => 'Nombre completo',
//
//                    2 => 'Sueldo',
//                    3 => 'Horas extra',
//                    4 => 'Bono',
//                    5 => 'Total',
//                    6 => 'Obra',
//                    7 => 'Banco',
//                    8 => 'Cuenta',
//                    9 => 'CLABE',
//                    10 => 'IMSS',
//                    11 => 'Tipo',
//                    12 => 'Firma',
//                    13 => 'Comentarios',
//                ),
//            6 =>
//                array(
//                    0 => 'Daniel Sigala Medina C.C. ',
//
//                    2 => '0.00',
//                    3 => NULL,
//                    4 => '0.00',
//                    5 => '13700.00',
//                    6 => 'GAP GDL',
//                    7 => 'Banorte',
//                    8 => '0431963392',
//                    9 => '072320004319633924',
//                    10 => ' ',
//                    11 => 'Destajista',
//                    12 => '',
//                    13 => ' ',
//                ),
        );

        $rows = $table->render();
        foreach ($rows as $row) {
            $result[] = $row;
        }
        return $result;
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
//            $actual_payrol = Payroll::find($pr->id);
//            $bonuses = $actual_payrol->Bonuses;
            $b2 = $payroll[$count]->Bonuses;
            $b3 = $pr->Bonuses;
            $total_bonus = 0;
            if (count($b2) > 1) {
                $op = $b2;
                $total_bonus = $this->total_bonus($b2);
                if (is_null($total_bonus)) {
                    $total_bonus = 0;
                }
                $k = 0;
            }
            $payroll_signo = $pr->extra_hours;
            $proyecto = $pr->public_work;
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
        return [
            'general' => $result,
            'general_count' => $table->count()
        ];
    }


    private function getItems()
    {
        return [
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
    }

    private function getExcelDataBig()
    {
        return array(
            0 =>
                array(
                    0 => 11,
                    1 =>
                        array(
                            'SISEGA HOME' => 52506.0,
                            'MUSEO CCU' => 17650.0,
                            'CUTLAJO' => 84360.0,
                            'PASEOS LILAS 67' => 20266.0,
                            'PREPA 21' => 108861.0,
                            'GAP MOCHIS' => 56432.0,
                            'GAP AGS' => 154011.0,
                            'PREPA SAN JOSE DEL VALLE' => 93009.0,
                            'GAP GDL' => 53748.0,
                            'CUCEI' => 13720.0,
                            'GENERAL' => 21000.0,
                        ),
                ),
            1 =>
                array(
                    0 => 'TOTAL ',
                    1 => '',
                    2 => '675563.00',
                    3 => '',
                    4 => '',
                    5 => '',
                    6 => '',
                    7 => 'NOMINA SEMANAL 11 DE DICIEMBRE DE 2021 ',
                ),
            2 =>
                array(
                    0 => ' ',
                ),
            3 =>
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
            4 =>
                array(
                    0 => 'Ana Bertha Sigala Aguilar C.C.',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '8209.00',
                    6 => 'SISEGA HOME ',
                    7 => 'BBVA Bancomer ',
                    8 => '1593535943 ',
                    9 => '012320015935359437 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => ' ',
                ),
            5 =>
                array(
                    0 => 'Jose Sanchez Rodriguez',
                    1 => ' ',
                    2 => '1800.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '1800.00',
                    6 => 'MUSEO CCU ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            6 =>
                array(
                    0 => 'Florentino Olmos Flores',
                    1 => ' ',
                    2 => '2100.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2100.00',
                    6 => 'MUSEO CCU ',
                    7 => 'Azteca ',
                    8 => '54231354704359 ',
                    9 => '127320013547043594 ',
                    10 => '54766025362 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            7 =>
                array(
                    0 => 'J. Guadalupe Velazquez Hernandez',
                    1 => ' ',
                    2 => '2000.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2000.00',
                    6 => 'MUSEO CCU ',
                    7 => 'BBVA Bancomer ',
                    8 => '1578729151 ',
                    9 => '012320015787291510 ',
                    10 => '04705235960 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            8 =>
                array(
                    0 => 'Ubaldo Alvarez Rojas',
                    1 => ' ',
                    2 => '2300.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2300.00',
                    6 => 'MUSEO CCU ',
                    7 => 'BBVA Bancomer ',
                    8 => '1586960581 ',
                    9 => '012180015869605811 ',
                    10 => '53967201624 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            9 =>
                array(
                    0 => 'Veronica Galvan Navarro',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 2000,
                    4 => '0.00',
                    5 => '4000.00',
                    6 => 'MUSEO CCU ',
                    7 => 'Banorte ',
                    8 => '1058126827 ',
                    9 => '072320010581268276 ',
                    10 => '2197125889 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => 'VACACIONES ',
                ),
            10 =>
                array(
                    0 => 'Yesenia Vargas Cuevas',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2500.00',
                    6 => 'MUSEO CCU ',
                    7 => 'Banorte ',
                    8 => '1051032068 ',
                    9 => '072320010510320680 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => ' ',
                ),
            11 =>
                array(
                    0 => 'Carlos Humberto Landeros Lopez',
                    1 => ' ',
                    2 => '2200.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2200.00',
                    6 => 'MUSEO CCU ',
                    7 => 'Banamex ',
                    8 => '7137487 ',
                    9 => '002320904471374877 ',
                    10 => '35170028209 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            12 =>
                array(
                    0 => 'Jose Flores Alegria',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '750.00',
                    6 => 'MUSEO CCU ',
                    7 => 'Banamex ',
                    8 => '84645 ',
                    9 => '002320453400846445 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => ' ',
                ),
            13 =>
                array(
                    0 => 'Jose Morfin Rodriguez',
                    1 => ' ',
                    2 => '2200.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2200.00',
                    6 => 'CUTLAJO ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            14 =>
                array(
                    0 => 'Jesus Israel Carrillo Rangel',
                    1 => ' ',
                    2 => '900.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '900.00',
                    6 => 'CUTLAJO ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            15 =>
                array(
                    0 => 'Charo Cristina Navarro Flores',
                    1 => ' ',
                    2 => '2000.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2000.00',
                    6 => 'CUTLAJO ',
                    7 => 'BBVA BANCOMER ',
                    8 => '1516034982 ',
                    9 => '012180015160349821 ',
                    10 => '2199860236 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            16 =>
                array(
                    0 => 'Andres Mata Espinoza',
                    1 => ' ',
                    2 => '1700.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '1700.00',
                    6 => 'CUTLAJO ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            17 =>
                array(
                    0 => 'Mario Alvarez Ocegueda',
                    1 => ' ',
                    2 => '1700.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '1700.00',
                    6 => 'CUTLAJO ',
                    7 => 'BBVA Bancomer ',
                    8 => '0472922169 ',
                    9 => '012320004729221698 ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            18 =>
                array(
                    0 => 'Octavio Alonso Ledesma Hernández',
                    1 => ' ',
                    2 => '1700.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '1700.00',
                    6 => 'CUTLAJO ',
                    7 => 'BBVA Bancomer ',
                    8 => '1563694107 ',
                    9 => '012320015636941076 ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            19 =>
                array(
                    0 => 'Jorge Miguel Iñiguez Perez',
                    1 => ' ',
                    2 => '1700.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '1700.00',
                    6 => 'CUTLAJO ',
                    7 => 'BBVA Bancomer ',
                    8 => '2880891029 ',
                    9 => '012320028808910291 ',
                    10 => '17149799938 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            20 =>
                array(
                    0 => 'Jose Flores Alegria',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2760.00',
                    6 => 'CUTLAJO ',
                    7 => 'Banamex ',
                    8 => '84645 ',
                    9 => '002320453400846445 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => 'SE PAGARAN SALIDAS ELECTRICAS Y CONTACTOS ',
                ),
            21 =>
                array(
                    0 => 'Luis Antonio Guevara Alvarez',
                    1 => ' ',
                    2 => '2000.00',
                    3 => NULL,
                    4 => '350.00',
                    5 => '2017.00',
                    6 => 'PASEOS LILAS 67 ',
                    7 => 'BBVA BANCOMER ',
                    8 => '1512077970 ',
                    9 => '012180015120779703 ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => 'FALTO UN DIA ',
                ),
            22 =>
                array(
                    0 => 'Luis Fernando Pineda Escobar',
                    1 => ' ',
                    2 => '2000.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2000.00',
                    6 => 'PASEOS LILAS 67 ',
                    7 => 'Banamex ',
                    8 => '56015298517 ',
                    9 => '002073560152985179 ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            23 =>
                array(
                    0 => 'Daniela Guadalupe Garcia Salazar',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 83,
                    4 => '0.00',
                    5 => '2083.00',
                    6 => 'PASEOS LILAS 67 ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '38180113151 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            24 =>
                array(
                    0 => 'Gustavo Diaz Chavez',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 167,
                    4 => '0.00',
                    5 => '2167.00',
                    6 => 'PASEOS LILAS 67 ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '23048675799 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            25 =>
                array(
                    0 => 'Cesar salvador de la cruz',
                    1 => ' ',
                    2 => '2800.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2800.00',
                    6 => 'PASEOS LILAS 67 ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '28098605687 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            26 =>
                array(
                    0 => 'Guillermo Lopez Mendoza',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '4000.00',
                    6 => 'PASEOS LILAS 67 ',
                    7 => 'BBVA Bancomer ',
                    8 => '1540004944 ',
                    9 => '012320015400049447 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => '34 M2 YESO ',
                ),
            27 =>
                array(
                    0 => 'Pedro Escobar Hernandez',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '4400.00',
                    6 => 'PASEOS LILAS 67 ',
                    7 => 'Banamex ',
                    8 => '9765822055 ',
                    9 => '002320701606532386 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => 'A CUENTA DE ZAVALETA Y BOLEADOS ',
                ),
            28 =>
                array(
                    0 => 'Jose Adrian Morfin Maldonado C.C.',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '799.00',
                    6 => 'PASEOS LILAS 67 ',
                    7 => 'Banorte ',
                    8 => '0302916247 ',
                    9 => '072320003029162472 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => 'CAJA CHICA NOVIEMBRE ',
                ),
            29 =>
                array(
                    0 => 'Brandon Martinez Parra',
                    1 => ' ',
                    2 => '1900.00',
                    3 => 158,
                    4 => '0.00',
                    5 => '2058.00',
                    6 => 'PREPA 21 ',
                    7 => 'BBVA Bancomer ',
                    8 => '1590336711 ',
                    9 => '012320015903367118 ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            30 =>
                array(
                    0 => 'Pablo Romero Gomez',
                    1 => ' ',
                    2 => '1700.00',
                    3 => 2141,
                    4 => '0.00',
                    5 => '3841.00',
                    6 => 'PREPA 21 ',
                    7 => '1 ',
                    8 => '1 ',
                    9 => '1 ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            31 =>
                array(
                    0 => 'Veronica Silva Benitez',
                    1 => ' ',
                    2 => '1500.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '1500.00',
                    6 => 'PREPA 21 ',
                    7 => '1 ',
                    8 => '1 ',
                    9 => '1 ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            32 =>
                array(
                    0 => 'Juan Daniel Guevara Alvarez',
                    1 => ' ',
                    2 => '2000.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2000.00',
                    6 => 'PREPA 21 ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            33 =>
                array(
                    0 => 'Isidro Hernandez Silva',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '27086.00',
                    6 => 'PREPA 21 ',
                    7 => 'Banamex ',
                    8 => '7282660 ',
                    9 => '002375701572826605 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => 'DESTAJO ',
                ),
            34 =>
                array(
                    0 => 'Javier Jimenez Reyes',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '34910.00',
                    6 => 'PREPA 21 ',
                    7 => 'Azteca ',
                    8 => '99941374060913 ',
                    9 => '127320013740609133 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => 'DESTAJO DE CIMBRA ',
                ),
            35 =>
                array(
                    0 => 'Javier Jimenez Reyes',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '14000.00',
                    6 => 'PREPA 21 ',
                    7 => 'Azteca ',
                    8 => '99941374060913 ',
                    9 => '127320013740609133 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => ' ',
                ),
            36 =>
                array(
                    0 => 'Fernando Daniel Yañez Carrillo',
                    1 => ' ',
                    2 => '1900.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '1900.00',
                    6 => 'PREPA 21 ',
                    7 => 'BBVA Bancomer ',
                    8 => '1598224514 ',
                    9 => '012320015982245141 ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            37 =>
                array(
                    0 => 'Erick Duban Yañez Carrillo',
                    1 => ' ',
                    2 => '1900.00',
                    3 => -1584,
                    4 => '0.00',
                    5 => '316.00',
                    6 => 'PREPA 21 ',
                    7 => 'BBVA Bancomer ',
                    8 => '1526774604 ',
                    9 => '012320015267746048 ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            38 =>
                array(
                    0 => 'Jose Herrera Aranda',
                    1 => ' ',
                    2 => '4500.00',
                    3 => 730,
                    4 => '0.00',
                    5 => '5230.00',
                    6 => 'GAP MOCHIS ',
                    7 => 'Banamex ',
                    8 => '4146675 ',
                    9 => '002362701641466750 ',
                    10 => '75988013252 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            39 =>
                array(
                    0 => 'Jorge Cisneros Madero',
                    1 => ' ',
                    2 => '2500.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2500.00',
                    6 => 'GAP MOCHIS ',
                    7 => 'Scotiabank ',
                    8 => '25601594536 ',
                    9 => '044320256015945365 ',
                    10 => '04907593562 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            40 =>
                array(
                    0 => 'Oliver Rojas García',
                    1 => ' ',
                    2 => '3800.00',
                    3 => 125,
                    4 => '0.00',
                    5 => '3925.00',
                    6 => 'GAP MOCHIS ',
                    7 => 'BBVA Bancomer ',
                    8 => '1572850076 ',
                    9 => '012180015728500769 ',
                    10 => '4099126270 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            41 =>
                array(
                    0 => 'Aida Verduzco Acosta',
                    1 => ' ',
                    2 => '1800.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '1800.00',
                    6 => 'GAP MOCHIS ',
                    7 => 'BBVA ',
                    8 => '00740611842948598088 ',
                    9 => '012730029485980880 ',
                    10 => '24957286420 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            42 =>
                array(
                    0 => 'Isidro Tiscareño Mendez',
                    1 => ' ',
                    2 => '3100.00',
                    3 => 105,
                    4 => '0.00',
                    5 => '3205.00',
                    6 => 'GAP MOCHIS ',
                    7 => 'Scotiabank ',
                    8 => '25603953733 ',
                    9 => '044320256039537335 ',
                    10 => '0713938982 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            43 =>
                array(
                    0 => 'Xochitl Isabel Castro Martinez',
                    1 => ' ',
                    2 => '2800.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2800.00',
                    6 => 'GAP MOCHIS ',
                    7 => 'BBVA ',
                    8 => '1570999779 ',
                    9 => '012180015709997799 ',
                    10 => '8169747329 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            44 =>
                array(
                    0 => 'Jorge Alberto Lopez Douriet',
                    1 => ' ',
                    2 => '3800.00',
                    3 => 700,
                    4 => '0.00',
                    5 => '4500.00',
                    6 => 'GAP MOCHIS ',
                    7 => 'BANAMEX ',
                    8 => '98596500099 ',
                    9 => '002743904531068011 ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            45 =>
                array(
                    0 => 'Guadalupe Federico Ortega Bojorquez',
                    1 => ' ',
                    2 => '1800.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '1800.00',
                    6 => 'GAP MOCHIS ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '21018371415 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            46 =>
                array(
                    0 => 'Eduardo Socorro Gastelum Peñuelas',
                    1 => ' ',
                    2 => '3000.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '3000.00',
                    6 => 'GAP MOCHIS ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '23937334581 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            47 =>
                array(
                    0 => 'Cornelio Rabago Armenta',
                    1 => ' ',
                    2 => '1600.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '1600.00',
                    6 => 'GAP MOCHIS ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '2300836493 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            48 =>
                array(
                    0 => 'Laura Elena Lara Valdez',
                    1 => ' ',
                    2 => '1600.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '1600.00',
                    6 => 'GAP MOCHIS ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '24977643675 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            49 =>
                array(
                    0 => 'Jesus Nicolas Aguirre Salgado',
                    1 => ' ',
                    2 => '3000.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '3000.00',
                    6 => 'GAP MOCHIS ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '23896840628 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            50 =>
                array(
                    0 => 'Hugo Misael Cardenas Martinez C.C.',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '18927.00',
                    6 => 'GAP MOCHIS ',
                    7 => 'BBVA Bancomer ',
                    8 => '1585791106 ',
                    9 => '012320015857911063 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => 'CAJA CHICA ',
                ),
            51 =>
                array(
                    0 => 'Mariana Rocha Cazares C.C.',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2545.00',
                    6 => 'GAP MOCHIS ',
                    7 => 'SANTANDER ',
                    8 => '60596538742 ',
                    9 => '014045605965387421 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => 'CAJA CHICA ',
                ),
            52 =>
                array(
                    0 => 'Antonio Rivera Ulloa',
                    1 => ' ',
                    2 => '2400.00',
                    3 => 1034,
                    4 => '0.00',
                    5 => '3434.00',
                    6 => 'GAP AGS ',
                    7 => 'Banorte ',
                    8 => '0591079272 ',
                    9 => '072320005910792726 ',
                    10 => '4038020873 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '5 HRS EXTRAS+ BONO 500 ',
                ),
            53 =>
                array(
                    0 => 'Salvador Miramontes Garcia',
                    1 => ' ',
                    2 => '2800.00',
                    3 => 1122,
                    4 => '0.00',
                    5 => '3922.00',
                    6 => 'GAP AGS ',
                    7 => 'Banorte ',
                    8 => '0366093234 ',
                    9 => '072320003660932346 ',
                    10 => '59169914054 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '5HRAS EXTRAS + BONO 500 ',
                ),
            54 =>
                array(
                    0 => 'Juan Carlos Mendez Gomez',
                    1 => ' ',
                    2 => '2400.00',
                    3 => 2153,
                    4 => '0.00',
                    5 => '4553.00',
                    6 => 'GAP AGS ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '8HRS EXTRAS + BONO 500 ',
                ),
            55 =>
                array(
                    0 => 'Bryan Saul Perez Rivera',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 944,
                    4 => '0.00',
                    5 => '2944.00',
                    6 => 'GAP AGS ',
                    7 => 'Banamex ',
                    8 => '7136251 ',
                    9 => '002320904471362517 ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '5HRS EXTRAS + BONO 500 ',
                ),
            56 =>
                array(
                    0 => 'Blanca Cecilia Lorena Trujillo',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 1200,
                    4 => '0.00',
                    5 => '3200.00',
                    6 => 'GAP AGS ',
                    7 => 'BBVA ',
                    8 => '1526063575 ',
                    9 => '012010015260635754 ',
                    10 => '51128914408 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '6HRS EXTRAS + DOMINGO TRABAJADO ',
                ),
            57 =>
                array(
                    0 => 'Claudia Gabriela Valadez Ramirez',
                    1 => ' ',
                    2 => '2200.00',
                    3 => 1320,
                    4 => '0.00',
                    5 => '3520.00',
                    6 => 'GAP AGS ',
                    7 => 'BBVA ',
                    8 => '1526063575 ',
                    9 => '012010015260635754 ',
                    10 => '51118906901 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '6 HRS EXTRAS + DOMINGO TRABAJADO ',
                ),
            58 =>
                array(
                    0 => 'Erika Perez Perez',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 622,
                    4 => '0.00',
                    5 => '2622.00',
                    6 => 'GAP AGS ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '59169769334 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '+ 7 HRS EXTRAS ',
                ),
            59 =>
                array(
                    0 => 'Antony Warduhay Hermosillo Lopez',
                    1 => ' ',
                    2 => '2300.00',
                    3 => 1074,
                    4 => '0.00',
                    5 => '2608.00',
                    6 => 'GAP AGS ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '19169990041 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '+ 3 HRS EXTRAS + DOMINGO TRABAJADO ',
                ),
            60 =>
                array(
                    0 => 'Nancy Fabiola Varela Cruz',
                    1 => ' ',
                    2 => '2300.00',
                    3 => 102,
                    4 => '0.00',
                    5 => '2402.00',
                    6 => 'GAP AGS ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '10200026820 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '+ 1 HRA EXTRA ',
                ),
            61 =>
                array(
                    0 => 'Julian Anatolio Villalobos Diaz',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 266,
                    4 => '0.00',
                    5 => '1266.00',
                    6 => 'GAP AGS ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '08200177643 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '+ 3 HRS EXTRAS ',
                ),
            62 =>
                array(
                    0 => 'Saiasi Jazmin Baez Esparza',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 444,
                    4 => '0.00',
                    5 => '2444.00',
                    6 => 'GAP AGS ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '17179355130 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '+6 HRS EXTRAS ',
                ),
            63 =>
                array(
                    0 => 'Leonardo Alexis Lopez Martinez',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 1111,
                    4 => '0.00',
                    5 => '3111.00',
                    6 => 'GAP AGS ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '02180142776 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '5 HRS EXTRAS + DOMINGO TRABAJADO ',
                ),
            64 =>
                array(
                    0 => 'Ricardo Varela Velazquez',
                    1 => ' ',
                    2 => '3000.00',
                    3 => 1667,
                    4 => '0.00',
                    5 => '4667.00',
                    6 => 'GAP AGS ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '51967751291 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '+5HRS EXTRAS + DOMINGO TRABAJADO ',
                ),
            65 =>
                array(
                    0 => 'Emiliano Martinez Hernandez',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 800,
                    4 => '0.00',
                    5 => '2800.00',
                    6 => 'GAP AGS ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '54170378332 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '+9 HRS EXTRAS ',
                ),
            66 =>
                array(
                    0 => 'Jose Marcelino Avila Ramirez',
                    1 => ' ',
                    2 => '3000.00',
                    3 => 1733,
                    4 => '0.00',
                    5 => '4733.00',
                    6 => 'GAP AGS ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '12058800819 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '+7 HRS EXTRAS ',
                ),
            67 =>
                array(
                    0 => 'Alejandro de la Vega Ramirez',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '7087.00',
                    6 => 'GAP AGS ',
                    7 => 'BBVA ',
                    8 => '1577609903 ',
                    9 => '012180015776099031 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => '+9HRS EXTRAS + BONO 500 + DOMINGO TRABAJADO ',
                ),
            68 =>
                array(
                    0 => 'Jordy Saul Flores Mendoza',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 2144,
                    4 => '0.00',
                    5 => '4144.00',
                    6 => 'GAP AGS ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '13130000659 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '+11 HRS EXTRAS + BONO 500 + DOMINGO TRABAJADO ',
                ),
            69 =>
                array(
                    0 => 'Ramiro Gutierrez Hernandez',
                    1 => ' ',
                    2 => '3000.00',
                    3 => 2833,
                    4 => '0.00',
                    5 => '5833.00',
                    6 => 'GAP AGS ',
                    7 => 'Banorte ',
                    8 => '1081787936 ',
                    9 => '072320010817879368 ',
                    10 => '4036404988 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '+10 HRS EXTRAS + BONO  500 + DOMINGO TRABAJADO ',
                ),
            70 =>
                array(
                    0 => 'Francisco Javier Velazquez Delgado',
                    1 => ' ',
                    2 => '3000.00',
                    3 => 1633,
                    4 => '0.00',
                    5 => '4633.00',
                    6 => 'GAP AGS ',
                    7 => 'Banorte ',
                    8 => '0591076356 ',
                    9 => '072320005910763564 ',
                    10 => '56968008732 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '+10HRS EXTRAS + BONO 500 ',
                ),
            71 =>
                array(
                    0 => 'Rodolfo Figueroa Contreras',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 2056,
                    4 => '0.00',
                    5 => '4056.00',
                    6 => 'GAP AGS ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '04887227116 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '+10 HRS EXTRAS + BONO 500 + DOMINGO TRABAJO ',
                ),
            72 =>
                array(
                    0 => 'Janeth Alejandra Martinez Lopez',
                    1 => ' ',
                    2 => '2300.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2300.00',
                    6 => 'GAP AGS ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '03149664512 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            73 =>
                array(
                    0 => 'Maria Guadalupe Galindo Lara',
                    1 => ' ',
                    2 => '2300.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2300.00',
                    6 => 'GAP AGS ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '05199526236 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            74 =>
                array(
                    0 => 'Andrea Jaqueline Mireles Velazquez',
                    1 => ' ',
                    2 => '2300.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2300.00',
                    6 => 'GAP AGS ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '02219981640 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            75 =>
                array(
                    0 => 'Humberto Rodriguez Ochoa',
                    1 => ' ',
                    2 => '2800.00',
                    3 => 1556,
                    4 => '0.00',
                    5 => '4356.00',
                    6 => 'GAP AGS ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '5187720345 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '+5 HRS EXTRAS + DOMINGO TRABAJADO ',
                ),
            76 =>
                array(
                    0 => 'Fermin De Lira Padilla',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 689,
                    4 => '0.00',
                    5 => '2689.00',
                    6 => 'GAP AGS ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '51998402799 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '+4 HRS EXTRAS + DOMINGO TRABAJADO ',
                ),
            77 =>
                array(
                    0 => 'Luis Gerardo Cruz Baez',
                    1 => ' ',
                    2 => '2750.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2750.00',
                    6 => 'GAP AGS ',
                    7 => 'BBVA ',
                    8 => '1594129329 ',
                    9 => '012180015941293293 ',
                    10 => '24169638566 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            78 =>
                array(
                    0 => 'Josue Saul Gonzalez De Alba',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 356,
                    4 => '0.00',
                    5 => '2356.00',
                    6 => 'GAP AGS ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '74149584596 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '+4 HRS EXTRAS + DOMINGO TRABAJADO ',
                ),
            79 =>
                array(
                    0 => 'Marco Antonio Sígala Aguilar C.C.',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '21250.00',
                    6 => 'PREPA 21 ',
                    7 => 'Banorte ',
                    8 => '0312117650 ',
                    9 => '072370003121176507 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => 'INTERNET + 2000 DE NOMINA QUE FALTARON ',
                ),
            80 =>
                array(
                    0 => 'Juan Carlos Martinez Chontal',
                    1 => ' ',
                    2 => '2800.00',
                    3 => 624,
                    4 => '0.00',
                    5 => '3424.00',
                    6 => 'GAP AGS ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '+1 HRA EXTRA + BONO 500 ',
                ),
            81 =>
                array(
                    0 => 'Juan Antonio Guevara Bernal',
                    1 => ' ',
                    2 => '3000.00',
                    3 => 1900,
                    4 => '0.00',
                    5 => '4900.00',
                    6 => 'GAP AGS ',
                    7 => 'BBVA Bancomer ',
                    8 => '1575371824 ',
                    9 => '012180015853718248 ',
                    10 => '3485680002 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '+3 HRS EXTRAS + BONO 500 ',
                ),
            82 =>
                array(
                    0 => 'Juan Manuel de Santiago Serrano',
                    1 => ' ',
                    2 => '3500.00',
                    3 => 2133,
                    4 => '0.00',
                    5 => '5633.00',
                    6 => 'GAP AGS ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '51008442207 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '+3HRS EXTRAS + BONO 500 + DOMINGO TRABAJADO ',
                ),
            83 =>
                array(
                    0 => 'Oscar Guerrero Muñiz',
                    1 => ' ',
                    2 => '2300.00',
                    3 => 1573,
                    4 => '0.00',
                    5 => '3873.00',
                    6 => 'GAP AGS ',
                    7 => 'Banorte ',
                    8 => '1044335136 ',
                    9 => '072320010443351360 ',
                    10 => '04978029009 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '+3HRS EXTRAS + BONO 500 + DOMINGO TRABAJADO ',
                ),
            84 =>
                array(
                    0 => 'Cristofer Abel Guerrero Rivas',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 1433,
                    4 => '0.00',
                    5 => '3433.00',
                    6 => 'GAP AGS ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '19210177051 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => '+3HRS EXTRAS + BONO 500+ DOMINGO TRABAJADO ',
                ),
            85 =>
                array(
                    0 => 'Juan Diaz Moralez',
                    1 => ' ',
                    2 => '2800.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2334.00',
                    6 => 'GAP AGS ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '51925801188 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            86 =>
                array(
                    0 => 'David Diaz Rivera',
                    1 => ' ',
                    2 => '2000.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '1667.00',
                    6 => 'GAP AGS ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '51139537073 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            87 =>
                array(
                    0 => 'Diego Fernandez Alfaro C.C.',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '3597.00',
                    6 => 'SISEGA HOME ',
                    7 => 'BBVA Bancomer ',
                    8 => '0460375381 ',
                    9 => '012320004603753813 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => ' ',
                ),
            88 =>
                array(
                    0 => 'Juan Paulo Cobian Orozco',
                    1 => ' ',
                    2 => '3000.00',
                    3 => 0,
                    4 => '0.00',
                    5 => '3000.00',
                    6 => 'SISEGA HOME ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '04057925259 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            89 =>
                array(
                    0 => 'Manuel Morfin Ramirez',
                    1 => ' ',
                    2 => '2600.00',
                    3 => 0,
                    4 => '0.00',
                    5 => '2600.00',
                    6 => 'SISEGA HOME ',
                    7 => 'Banorte ',
                    8 => '0361013808 ',
                    9 => '072320003610138080 ',
                    10 => '4089002549 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            90 =>
                array(
                    0 => 'Gerardo Regino Hernandez',
                    1 => ' ',
                    2 => '2400.00',
                    3 => 0,
                    4 => '0.00',
                    5 => '2400.00',
                    6 => 'SISEGA HOME ',
                    7 => 'Bancoppel ',
                    8 => '10379415135 ',
                    9 => '137320103794151359 ',
                    10 => '37169660547 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            91 =>
                array(
                    0 => 'Luis Felipe Ramos Sahagun',
                    1 => ' ',
                    2 => '1800.00',
                    3 => 0,
                    4 => '0.00',
                    5 => '1800.00',
                    6 => 'SISEGA HOME ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '27210230796 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            92 =>
                array(
                    0 => 'Vidal Gonzalez Pasillas',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 0,
                    4 => '0.00',
                    5 => '2000.00',
                    6 => 'SISEGA HOME ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            93 =>
                array(
                    0 => 'Erick Martinez Perez',
                    1 => ' ',
                    2 => '3000.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '3000.00',
                    6 => 'PREPA SAN JOSE DEL VALLE ',
                    7 => 'BBVA Bancomer ',
                    8 => '744465641515780000 ',
                    9 => '121640151578099 ',
                    10 => '75937745950 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            94 =>
                array(
                    0 => 'Luis Humberto Ruiz Flores',
                    1 => ' ',
                    2 => '2500.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2500.00',
                    6 => 'PREPA SAN JOSE DEL VALLE ',
                    7 => 'BBVA Bancomer ',
                    8 => '1572760078 ',
                    9 => '012180015727600783 ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            95 =>
                array(
                    0 => 'Pedro Rivera Gonzalez',
                    1 => ' ',
                    2 => '2000.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2000.00',
                    6 => 'PREPA SAN JOSE DEL VALLE ',
                    7 => 'BANCO AZTECA ',
                    8 => '95960160834722 ',
                    9 => '127320001608347222 ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            96 =>
                array(
                    0 => 'Jose Francisco Ramirez Gonzalez',
                    1 => ' ',
                    2 => '2000.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2000.00',
                    6 => 'PREPA SAN JOSE DEL VALLE ',
                    7 => 'HSBC ',
                    8 => '6514823058 ',
                    9 => '021320065148230588 ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            97 =>
                array(
                    0 => 'Luis Daniel Sanchez Castañeda',
                    1 => ' ',
                    2 => '2000.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2000.00',
                    6 => 'PREPA SAN JOSE DEL VALLE ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            98 =>
                array(
                    0 => 'Fernando Tomas Cardenas Miramontes',
                    1 => ' ',
                    2 => '2000.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2000.00',
                    6 => 'PREPA SAN JOSE DEL VALLE ',
                    7 => 'BANCOPPEL ',
                    8 => '10436622347 ',
                    9 => '137320104366223478 ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            99 =>
                array(
                    0 => 'Martin Garcia Ortiz',
                    1 => ' ',
                    2 => '1800.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '1800.00',
                    6 => 'PREPA SAN JOSE DEL VALLE ',
                    7 => 'BBVA BANCOMER ',
                    8 => '1536698670 ',
                    9 => '012180015366986703 ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            100 =>
                array(
                    0 => 'Mauricio Ramirez Martinez',
                    1 => ' ',
                    2 => '1800.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '1800.00',
                    6 => 'PREPA SAN JOSE DEL VALLE ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            101 =>
                array(
                    0 => 'Ana Rosa Sandoval Bautista',
                    1 => ' ',
                    2 => '1800.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '1800.00',
                    6 => 'PREPA SAN JOSE DEL VALLE ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            102 =>
                array(
                    0 => 'Sergio Guillen Gutierrez',
                    1 => ' ',
                    2 => '2300.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2300.00',
                    6 => 'PREPA SAN JOSE DEL VALLE ',
                    7 => 'Banorte ',
                    8 => '1042286159 ',
                    9 => '072320010422861596 ',
                    10 => '54776039197 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            103 =>
                array(
                    0 => 'Maria Trinidad Garcia Angel',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 167,
                    4 => '0.00',
                    5 => '2167.00',
                    6 => 'PREPA SAN JOSE DEL VALLE ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '3167710486 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            104 =>
                array(
                    0 => 'Adolfo Garcia Rodriguez',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 167,
                    4 => '0.00',
                    5 => '2167.00',
                    6 => 'PREPA SAN JOSE DEL VALLE ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '75018301053 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            105 =>
                array(
                    0 => 'Rocio Enriquez Rodriguez',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 167,
                    4 => '0.00',
                    5 => '2167.00',
                    6 => 'PREPA SAN JOSE DEL VALLE ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '4069075994 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            106 =>
                array(
                    0 => 'Rodolfo Enriquez Muñoz',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 882,
                    4 => '0.00',
                    5 => '2882.00',
                    6 => 'PREPA SAN JOSE DEL VALLE ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '4897122737 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            107 =>
                array(
                    0 => 'Tito Lopez Roy',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 83,
                    4 => '0.00',
                    5 => '2083.00',
                    6 => 'PREPA SAN JOSE DEL VALLE ',
                    7 => 'BBVA Bancomer ',
                    8 => '1547136320 ',
                    9 => '012320015471363204 ',
                    10 => '19149684193 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            108 =>
                array(
                    0 => 'Jose Francisco Flores Orozco',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 1500,
                    4 => '0.00',
                    5 => '3500.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '4957814702 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => 'FINIQUITO ',
                ),
            109 =>
                array(
                    0 => 'Guadalupe Juanpedro Orozco',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 1500,
                    4 => '0.00',
                    5 => '3500.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '4109401671 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => 'FINIQUITO ',
                ),
            110 =>
                array(
                    0 => 'Faustina Enriquez Muñoz',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 500,
                    4 => '0.00',
                    5 => '2500.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '04098479333 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => 'FINIQUITO ',
                ),
            111 =>
                array(
                    0 => 'Rodolfo Soto Uribe',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '20703.00',
                    6 => 'PREPA SAN JOSE DEL VALLE ',
                    7 => 'BANCO AZTECA ',
                    8 => '98421349515247 ',
                    9 => '127320013495152472 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => ' ',
                ),
            112 =>
                array(
                    0 => 'Alondra Jaqueline Enriquez Muñoz',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 500,
                    4 => '0.00',
                    5 => '2500.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '46210388644 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => 'FINIQUITO ',
                ),
            113 =>
                array(
                    0 => 'Rigoberto Renteria Castilo',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '31669.00',
                    6 => 'PREPA SAN JOSE DEL VALLE ',
                    7 => 'BANCO AZTECA ',
                    8 => '41971394857293 ',
                    9 => '127320013948572936 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => ' ',
                ),
            114 =>
                array(
                    0 => 'Gabriel Ramirez',
                    1 => ' ',
                    2 => '2000.00',
                    3 => 1500,
                    4 => '0.00',
                    5 => '3500.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '4816345112 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => 'FINIQUITO ',
                ),
            115 =>
                array(
                    0 => 'Cynthia Esther Guillén Venegas',
                    1 => ' ',
                    2 => '2000.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2000.00',
                    6 => 'GAP GDL ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            116 =>
                array(
                    0 => 'Citlalli Soriano Rodriguez',
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
            117 =>
                array(
                    0 => 'Jose Isidro Gomez Ortega',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '3125.00',
                    6 => 'GAP GDL ',
                    7 => 'BBVA BANCOMER ',
                    8 => '1542771036 ',
                    9 => '012180015427710368 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => '1 HORA EXTRA ',
                ),
            118 =>
                array(
                    0 => 'Hector Javier Soto Castro C.C.',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '23123.00',
                    6 => 'GAP GDL ',
                    7 => 'BBVA Bancomer ',
                    8 => '1580884564 ',
                    9 => '012771015808845643 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => 'SEMANA 29/11 - 04/12/2021: SELLADOR PARA MARMOL: $450, PEGAMENTO NO MAS CLAVOS: $246, CONECTORES: $366, FOTOCELDA, CINTAS Y BASES PARA FOTOCELDA: $386, PAQUETE DE CUBREBOCAS: $200, SELLADOR MARMOL: $325, GASOLINA: $500, DESARMADOR Y CLAVIJA: $74, BICARBONATO: $48, BIDÓN DE AGUA: $30, CARTA POLICIA CHONTAL: $70, BROCHES MARIPOSA: $140, CAMIÓN CHONTAL: $413, PINTURA EPOXICA: $483, INTERNET OFICINA: $441

SEMANA 22/11 - 27/11/2021: CARPETAS DE ARGOLLA Y PROTECTORES DE HOJA: $661, PERFILES DE ALUMINIO: $1036, COMIDA SÁBADO: $1400, NOCHE BUENAS Y TIERRA: $1365, CARDA Y FELPA: $125, GASOLINA: $350, NOCHE BUENAS: $1540, CHALECOS: $1200, NOCHE BUENAS: $900, TIERRA VEGETAL: $700, ÁCIDO: $903, AEROSOL: $195, TOPES: $952, CUTTER: $66, CENA LUNES: $1738, UBERS GENTE: $839, ÁCIDO; $477, SELLADOR: $1205, CENA MARTES: $1833, REFRESCOS CENA MARTES: $189, PARES DE GUANTES: $260, PLACAS CIEGAS: $40, LLENADO DE CISTERNAS: $120, UBER MATERIAL ELECTRICO: $164, ACIDO MURIATICO: $320, SILICON: $373 ',
                ),
            119 =>
                array(
                    0 => 'Jose Omar Garcia Anaya C.C.',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '7971.00',
                    6 => 'PREPA SAN JOSE DEL VALLE ',
                    7 => 'Santander ',
                    8 => '20009049022 ',
                    9 => '014320200090490222 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => 'CAJA CHICA $3,970.80
SALARIO DE GERARDO BAUTISTA $ 2000
SALARIO DE MARIA GUADALUPE GONZALEZ $2000
(PERSONAL DE GAP) ',
                ),
            120 =>
                array(
                    0 => 'Manuel de Jesus Cuevas Gomez',
                    1 => ' ',
                    2 => '2700.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2700.00',
                    6 => 'SISEGA HOME ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '26179809038 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            121 =>
                array(
                    0 => 'Angel Uriel Gomez Vega',
                    1 => ' ',
                    2 => '2300.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2300.00',
                    6 => 'SISEGA HOME ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '11111111111 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            122 =>
                array(
                    0 => 'Jose Moises Martinez Esqueda',
                    1 => ' ',
                    2 => '2700.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2700.00',
                    6 => 'SISEGA HOME ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '11111111111 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            123 =>
                array(
                    0 => 'Yesenia Martinez Hernandez',
                    1 => ' ',
                    2 => '2300.00',
                    3 => 2000,
                    4 => '0.00',
                    5 => '4300.00',
                    6 => 'SISEGA HOME ',
                    7 => 'BBVA Bancomer ',
                    8 => '1514468772 ',
                    9 => '012180015144687729 ',
                    10 => '4069050260 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => 'DESCUENTO POR PRESTAMO, MAS VACACIONES ',
                ),
            124 =>
                array(
                    0 => 'Eugenio Ramirez Hidalgo',
                    1 => ' ',
                    2 => '3200.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '3200.00',
                    6 => 'SISEGA HOME ',
                    7 => 'Banamex ',
                    8 => '7128236 ',
                    9 => '002320701671282360 ',
                    10 => '04028561407 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            125 =>
                array(
                    0 => 'Oscar de Jesus Cano Vargas',
                    1 => ' ',
                    2 => '2000.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2000.00',
                    6 => 'SISEGA HOME ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            126 =>
                array(
                    0 => 'Esther Medina Robles',
                    1 => ' ',
                    2 => '2000.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2000.00',
                    6 => 'SISEGA HOME ',
                    7 => 'BBVA Bancomer ',
                    8 => '1528652573 ',
                    9 => '012320015286525732 ',
                    10 => '02198440832 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            127 =>
                array(
                    0 => 'Miguel Axicalli Ruvalcaba Castro',
                    1 => ' ',
                    2 => '2200.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2200.00',
                    6 => 'SISEGA HOME ',
                    7 => 'BBVA Bancomer ',
                    8 => '1563394669 ',
                    9 => '012180015633946696 ',
                    10 => '04129429306 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            128 =>
                array(
                    0 => 'Enrique Montes De Oca Sanchez',
                    1 => ' ',
                    2 => '1900.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '1900.00',
                    6 => 'SISEGA HOME ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '18210165041 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            129 =>
                array(
                    0 => 'Lourdes Abigail Ruvalcaba Jimenez',
                    1 => ' ',
                    2 => '2200.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2200.00',
                    6 => 'SISEGA HOME ',
                    7 => 'BBVA Bancomer ',
                    8 => '1549832087 ',
                    9 => '012320015498320877 ',
                    10 => '75978103705 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            130 =>
                array(
                    0 => 'Brayan Giovanny Diaz Avalos',
                    1 => ' ',
                    2 => '1400.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '1400.00',
                    6 => 'SISEGA HOME ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '05219921367 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            131 =>
                array(
                    0 => 'Monica Armenta Anaya',
                    1 => ' ',
                    2 => '2000.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2000.00',
                    6 => 'SISEGA HOME ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '0401680329 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            132 =>
                array(
                    0 => 'Uriel Nieves Ruvalcaba',
                    1 => ' ',
                    2 => '2200.00',
                    3 => -500,
                    4 => '0.00',
                    5 => '1700.00',
                    6 => 'CUCEI ',
                    7 => 'BBVA Bancomer ',
                    8 => '1585372219 ',
                    9 => '012180015853722195 ',
                    10 => '18170179537 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => 'URIEL NIEVES SE LE REBAJA $500 DE PRESTAMO ',
                ),
            133 =>
                array(
                    0 => 'Diana Laura Rodriguez Rivas',
                    1 => ' ',
                    2 => '2000.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2000.00',
                    6 => 'CUCEI ',
                    7 => ' ',
                    8 => ' ',
                    9 => ' ',
                    10 => '19179706882 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            134 =>
                array(
                    0 => 'Dilan Jahel Gamboa Loredo',
                    1 => ' ',
                    2 => '1600.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '1600.00',
                    6 => 'CUCEI ',
                    7 => 'Banorte ',
                    8 => '1157892070 ',
                    9 => '072691011578920705 ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            135 =>
                array(
                    0 => 'Jose Flores Alegria',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '8420.00',
                    6 => 'CUCEI ',
                    7 => 'Banamex ',
                    8 => '84645 ',
                    9 => '002320453400846445 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => 'TRABAJOS DE CABLEADO A CENTRO DE CARGA ',
                ),
            136 =>
                array(
                    0 => 'Luis Enrique Muñoz Jimenez C.C.',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '25000.00',
                    6 => 'GAP AGS ',
                    7 => 'Banorte ',
                    8 => '0359473940 ',
                    9 => '072320003594739408 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => 'GASTOS PARA OBRA, COMIDAS Y DESPENSA DE LOS TRABAJADORES QUE ANDAN DE NOCHE. ',
                ),
            137 =>
                array(
                    0 => 'Javier Jimenez Reyes',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '21700.00',
                    6 => 'CUTLAJO ',
                    7 => 'Azteca ',
                    8 => '99941374060913 ',
                    9 => '127320013740609133 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => 'HABILITADO ACERO LOSA 2 ',
                ),
            138 =>
                array(
                    0 => 'Javier Jimenez Reyes',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '48000.00',
                    6 => 'CUTLAJO ',
                    7 => 'Azteca ',
                    8 => '99941374060913 ',
                    9 => '127320013740609133 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => 'HABILITADO DE CIMBRA APARENTE LOSA 2 ',
                ),
            139 =>
                array(
                    0 => 'Daniel Sigala Medina C.C.',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '10717.00',
                    6 => 'GAP AGS ',
                    7 => 'Banorte ',
                    8 => '0431963392 ',
                    9 => '072320004319633924 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => ' ',
                ),
            140 =>
                array(
                    0 => 'Angelina Sanchez Hernandez',
                    1 => ' ',
                    2 => '2800.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2800.00',
                    6 => 'GENERAL ',
                    7 => 'Bancoppel ',
                    8 => ' ',
                    9 => '137320103423118281 ',
                    10 => '02209668769 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            141 =>
                array(
                    0 => 'Alberta Rivas Uribe',
                    1 => ' ',
                    2 => '1000.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '1000.00',
                    6 => 'GENERAL ',
                    7 => 'Banorte ',
                    8 => '0241012976 ',
                    9 => '072320002410129766 ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            142 =>
                array(
                    0 => 'Monica Liliana Morfin Ramirez',
                    1 => ' ',
                    2 => '2500.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2500.00',
                    6 => 'GENERAL ',
                    7 => 'Bancoppel ',
                    8 => '10358745372 ',
                    9 => '137320103587453727 ',
                    10 => '52119512920 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            143 =>
                array(
                    0 => 'Jose Federico Aguirre Enriquez',
                    1 => ' ',
                    2 => '2200.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2200.00',
                    6 => 'GENERAL ',
                    7 => 'BBVA Bancomer ',
                    8 => '1563932679 ',
                    9 => '012180015639326799 ',
                    10 => '54887362397 ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            144 =>
                array(
                    0 => 'Jose Humberto Castorena Ramirez',
                    1 => ' ',
                    2 => '2000.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '2000.00',
                    6 => 'GENERAL ',
                    7 => 'Banorte ',
                    8 => '1080587559 ',
                    9 => '072320010805875594 ',
                    10 => ' ',
                    11 => 'Empleado',
                    12 => '',
                    13 => ' ',
                ),
            145 =>
                array(
                    0 => 'Jorge Armin Lopez Martinez C.C.',
                    1 => ' ',
                    2 => '0.00',
                    3 => NULL,
                    4 => '0.00',
                    5 => '10500.00',
                    6 => 'GENERAL ',
                    7 => 'BBVA Bancomer ',
                    8 => '1529078956 ',
                    9 => '012180015290789568 ',
                    10 => ' ',
                    11 => 'Destajista',
                    12 => '',
                    13 => ' ',
                ),
        );
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
