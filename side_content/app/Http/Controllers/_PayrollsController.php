<?php

namespace App\Http\Controllers;

use App\Bonus;
use App\Employee;
use App\Exports\PayrollReport;
use App\Http\Requests\PayrollsRequest;
use App\Payroll;
use App\PublicWork;
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

        /*$public_works = PublicWork::where('status', '1')->pluck('name', 'id')->toArray();*/
        $public_works = PublicWork::all()->pluck('name', 'id')->toArray();

        /*$total = ceil($total);

        $total = "$".number_format($total,'2','.',',');*/

        return view ('payrolls.index', compact('public_works'));
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
     * @param  \Illuminate\Http\Request  $request
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

        $bonuses = $request['bonuses_list'];

        if(!empty($bonuses)){
            foreach ($bonuses as $key => $item){
                /*$payroll->Bonuses()->attach($bonuses[$key]['bonus_id'], ['date' => $bonuses[$key]['bonus_date']]);*/
                if($bonuses[$key]['bonus_id'] != null){
                    $payroll->Bonuses()->attach($bonuses[$key]['bonus_id']);
                }
            }
        }

        Session::flash('success','Empleado creado correctamente.');
        return Redirect::to('/employees/getPayrolls/'.$request['employee_id']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Auth::user()->role==1){
            $payroll = Payroll::find($id);

            $employee = Employee::find($payroll->employee_id);

            $bonuses_array = [];
            /*$bonuses = Bonus::all()->pluck('name', 'id')->toArray();*/
            $bonuses = Bonus::all();
            foreach ($bonuses as $bonus){
                $bonuses_array[$bonus->id] = $bonus->name." - $".$bonus->amount;
            }

            $extra = Bonus::all()->pluck('amount', 'id')->toArray();

            $count = sizeof($payroll->Bonuses);

            $public_works = PublicWork::where('status', '1')->pluck('name', 'id')->toArray();

            $type = 'Payroll';

            return view('payrolls.edit', compact('payroll', 'employee', 'bonuses_array', 'extra', 'count', 'public_works', 'type'));
        }else{
            return Redirect('/payrolls');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
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

        if(!empty($bonuses)){
            for($i = 0; $i < count($bonuses); $i++){
                /*$array[$bonuses[$i]['bonus_id']] = ['date' => $bonuses[$i]['bonus_date']];*/
                if($bonuses[$i]['bonus_id'] != null){
                    array_push($array, $bonuses[$i]['bonus_id']);
                }
            }
        }

        $payroll->Bonuses()->sync($array);

        Session::flash('success', 'Nómina actualizada correctamente.');

        if($request['type'] == 'Employee'){
            return Redirect::to('/employees/getPayrolls/'.$request['employee_id']);
        }else{
            return Redirect::to('/payrolls');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payroll = Payroll::find($id);

        if(!empty($payroll->Bonuses)){
            $payroll->Bonuses()->detach();
        }

        $payroll->delete();

        Session::flash('success', 'Nómina eliminada correctamente');
    }

    public function editPieceworkerPayroll($payroll_id){
        if(Auth::user()->role==1){
            $payroll = Payroll::find($payroll_id);

            $employee = Employee::find($payroll->employee_id);

            $public_works = PublicWork::where('status', '1')->pluck('name', 'id')->toArray();

            $type = 'Payroll';

            return view('payrolls.editPieceworkerPayroll', compact('payroll', 'employee', 'public_works', 'type'));
        }else{
            return Redirect('/payrolls');
        }
    }

    public function search_payrolls(Request $request, Payroll $payroll){
        $query = $payroll->newQuery();

        $dates = explode(" / ", $request['range']);
        $start_date = explode("-", $dates[0]);
        $end_date = explode("-", $dates[1]);

        $start = $start_date[2]."-".$start_date[1]."-".$start_date[0];
        $end = $end_date[2]."-".$end_date[1]."-".$end_date[0];

        $query->select('payrolls.id AS id', 'payrolls.days_worked', 'payrolls.hours_worked', 'payrolls.extra_hours',
            'payrolls.comments', 'payrolls.total_salary', 'payrolls.date', 'employees.id as employee_id',
            'public_works.name AS public_work')
            ->join('employees', 'employees.id', 'payrolls.employee_id')
            ->join('public_works', 'public_works.id', 'payrolls.public_work_id')
            ->whereBetween('payrolls.date', [$start, $end.' 23:59:59']);

        if($request['public_work_id'] != ''){
            $query->where('payrolls.public_work_id', $request['public_work_id']);
        }

        $query = $query->get();

        if(sizeof($query)<1){
            return "";
        }

        $array = [];
        $total = 0;
        foreach ($query as $key){
            $bonuses = [];
            $bonuses_dates = [];

            $employee = Employee::find($key->employee_id);

            /*$public_work = PublicWork::find($key->public_work_id);

            $public_work_name = '';
            if(!empty($public_work)){
                $public_work_name = $public_work->name;
            }*/

            $payment = Payroll::find($key->id);
            foreach ($payment->Bonuses as $bonus){
                array_push($bonuses, $bonus->name);
                array_push($bonuses_dates, $bonus->pivot->date);
            }

            $class = new \stdClass;
            $class->id = [$key->id, $employee->type];
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

            $class->total_salary = "$".number_format(ceil($key->total_salary),'2','.',',');
            $total += (float)$key->total_salary;

            array_push($array, $class);
        }

        $total = "$".number_format(ceil($total),'2','.',',');

        return [$array, $total];
    }

    public function export_pdf(Request $request){
        $dates = explode(" / ", $request['date_range']);
        $start_date = explode("-", $dates[0]);
        $end_date = explode("-", $dates[1]);

        $start = $start_date[2]."-".$start_date[1]."-".$start_date[0];
        $end = $end_date[2]."-".$end_date[1]."-".$end_date[0];

        $fileName = \Str::random(10)."_".time().".pdf";

        if($request['public_work'] != ''){
            $payrolls = Payroll::select('payrolls.id AS id', 'payrolls.days_worked', 'payrolls.hours_worked', 'payrolls.extra_hours',
                'payrolls.total_salary', 'payrolls.date', 'employees.id as employee_id', 'public_works.id as public_work_id',
                'public_works.name AS public_work')
                ->join('employees', 'employees.id', 'payrolls.employee_id')
                ->join('public_works', 'public_works.id', 'payrolls.public_work_id')
                ->whereBetween('payrolls.date', [$start, $end.' 23:59:59'])
                ->where('payrolls.public_work_id', $request['public_work'])->get();

            $array = [];
            $total = 0;

            foreach ($payrolls as $payroll){
                $actual_payroll = Payroll::find($payroll->id);

                $total_bonus = 0;
                foreach ($actual_payroll->Bonuses as $bonus){
                    $total_bonus += (int)$bonus->amount;
                }

                $employee = Employee::find($payroll->employee_id);

                $class = new \stdClass;
                $class->full_name = $employee->name.' '.$employee->last_name;
                $class->total_bonus = $total_bonus;
                $class->extra_hours = $payroll->extra_hours;
                $class->public_work = $payroll->public_work;
                $class->bank = $employee->bank;
                $class->account = $employee->account;
                $class->clabe = $employee->clabe;
                $class->stall = $employee->stall;

                $class->total_salary = "$".number_format(ceil($payroll->total_salary),'2','.',',');
                $total += (float)$payroll->total_salary;

                array_push($array, $class);
            }

            $total = "$".number_format(ceil($total),'2','.',',');

            $public_work = PublicWork::find($request['public_work']);

            $pdf = PDF::loadView('templates.payrolls_filtered_report', compact('array', 'public_work', 'total', 'end'))
                ->setPaper('a4', 'landscape');

            return $pdf->download('reporte_'.$fileName);
        }else{
            $payrolls = Payroll::select('payrolls.id AS id', 'payrolls.days_worked', 'payrolls.hours_worked', 'payrolls.extra_hours',
                'payrolls.total_salary', 'payrolls.date', 'employees.id as employee_id', 'public_works.id as public_work_id',
                'public_works.name AS public_work')
                ->join('employees', 'employees.id', 'payrolls.employee_id')
                ->join('public_works', 'public_works.id', 'payrolls.public_work_id')
                ->whereBetween('payrolls.date', [$start, $end.' 23:59:59'])->get();

            $array = [];
            $total = 0;
            $public_works_array = [];

            foreach ($payrolls as $payroll){
                $actual_payroll = Payroll::find($payroll->id);

                $total_bonus = 0;
                foreach ($actual_payroll->Bonuses as $bonus){
                    $total_bonus += (int)$bonus->amount;
                }

                $employee = Employee::find($payroll->employee_id);

                $class = new \stdClass;
                $class->full_name = $employee->name.' '.$employee->last_name;
                $class->total_bonus = $total_bonus;
                $class->extra_hours = $payroll->extra_hours;
                $class->public_work = $payroll->public_work;
                $class->bank = $employee->bank;
                $class->account = $employee->account;
                $class->clabe = $employee->clabe;
                $class->stall = $employee->stall;

                $class->total_salary = "$".number_format(ceil($payroll->total_salary),'2','.',',');
                $total += (float)$payroll->total_salary;

                array_push($array, $class);

                if (!in_array($payroll->public_work_id, $public_works_array)) {
                    array_push($public_works_array, $payroll->public_work_id);
                }
            }

            $total = "$".number_format(ceil($total),'2','.',',');

            $public_works = [];

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

            /*$view = \View::make('templates.payrolls_report',compact('array', 'public_works', 'total', 'end'));
            $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8');
            $html2pdf->writeHTML($view);
            $html2pdf->output('reporte_'.$fileName,'D');*/

            $size = (sizeof($public_works))*45;

            $pdf = PDF::loadView('templates.payrolls_report', compact('array', 'public_works', 'total', 'end', 'size'))
                ->setPaper('a4', 'landscape');

            return $pdf->download('reporte_'.$fileName);
        }
    }

    public function export_excel(Request $request){
        $dates = explode(" / ", $request['date_range_excel']);
        $start_date = explode("-", $dates[0]);
        $end_date = explode("-", $dates[1]);

        $start = $start_date[2]."-".$start_date[1]."-".$start_date[0];
        $end = $end_date[2]."-".$end_date[1]."-".$end_date[0];

        $fileName = \Str::random(10)."_".time().".pdf";

        if($request['public_work_excel'] != ''){
            $payrolls = Payroll::select('payrolls.id AS id', 'payrolls.days_worked', 'payrolls.hours_worked', 'payrolls.extra_hours',
                'payrolls.total_salary', 'payrolls.date', 'employees.id as employee_id', 'public_works.id as public_work_id',
                'public_works.name AS public_work')
                ->join('employees', 'employees.id', 'payrolls.employee_id')
                ->join('public_works', 'public_works.id', 'payrolls.public_work_id')
                ->whereBetween('payrolls.date', [$start, $end.' 23:59:59'])
                ->where('payrolls.public_work_id', $request['public_work'])->get();

            $array = [];

            $data = [];

            array_push($data, ["Nombre completo", "Bono", "Horas extra", "Total", "Obra", "Banco", "Cuenta", "CLABE", "Puesto", "Firma"]);

            foreach ($payrolls as $payroll){
                $employee = Employee::find($payroll->employee_id);

                $actual_payroll = Payroll::find($payroll->id);

                $total_bonus = 0;
                foreach ($actual_payroll->Bonuses as $bonus){
                    $total_bonus += (int)$bonus->amount;
                }

                array_push($data, [
                    $employee->name.' '.$employee->last_name,
                    $total_bonus,
                    $payroll->extra_hours,
                    "$".number_format(ceil($payroll->total_salary),'2','.',','),
                    $payroll->public_work,
                    $employee->bank,
                    $employee->account,
                    $employee->clabe,
                    $employee->stall,
                    ""
                ]);

                $export = new PayrollReport($data);

                return Excel::download($export, 'Reporte.xlsx');
            }
        }else{
            $payrolls = Payroll::select('payrolls.id AS id', 'payrolls.days_worked', 'payrolls.hours_worked', 'payrolls.extra_hours',
                'payrolls.total_salary', 'payrolls.date', 'employees.id as employee_id', 'public_works.id as public_work_id',
                'public_works.name AS public_work')
                ->join('employees', 'employees.id', 'payrolls.employee_id')
                ->join('public_works', 'public_works.id', 'payrolls.public_work_id')
                ->whereBetween('payrolls.date', [$start, $end.' 23:59:59'])->get();

            $array = [];

            $data = [];

            array_push($data, ["Nombre completo", "Bono", "Horas extra", "Total", "Obra", "Banco", "Cuenta", "CLABE", "Puesto", "Firma"]);

            foreach ($payrolls as $payroll){
                $employee = Employee::find($payroll->employee_id);

                $actual_payroll = Payroll::find($payroll->id);

                $total_bonus = 0;
                foreach ($actual_payroll->Bonuses as $bonus){
                    $total_bonus += (int)$bonus->amount;
                }

                array_push($data, [
                    $employee->name.' '.$employee->last_name,
                    $total_bonus,
                    $payroll->extra_hours,
                    "$".number_format(ceil($payroll->total_salary),'2','.',','),
                    $payroll->public_work,
                    $employee->bank,
                    $employee->account,
                    $employee->clabe,
                    $employee->stall,
                    ""
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
            }

            // Aquí se genera el excel
            $export = new PayrollReport($data);

            return Excel::download($export, 'Reporte.xlsx');
        }
    }
}
