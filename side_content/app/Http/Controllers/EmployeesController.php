<?php

namespace App\Http\Controllers;

use App\Bonus;
use App\Payroll;
use App\PublicWork;
use Illuminate\Http\Request;

use App\Http\Requests\EmployeesRequest;

use App\Employee;
use DB;
use Illuminate\Support\Facades\Auth;
use Session;
use Redirect;
use function Webmozart\Assert\Tests\StaticAnalysis\float;

class EmployeesController extends Controller
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
        $employees = Employee::all();
        return view('employees.index',compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->role==1){

            return view('employees.create');
        }else{
            return Redirect('/employees');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeesRequest $request)
    {

        $imss = '';
        if(isset($request['imss'])){
            if($request['imss']=='on'){
                $imss = 1;
            }else{
                $imss = $request['imss'];
            }
        }else{
            $imss = 0;
        }

        //region Validar

//        $employee = [
//            'name' => $request->input('name'),
//            'curp' => $request->input('curp'),
//            'cell_phone' => $request->input('cell_phone'),
//            'birthdate' => $request->input('birthdate'),
//            'clabe' => $request->input('clabe')
//        ];
//
//        $existent_filtered = $this->findExistentEmployeesDb($employee);
//        $duplicated = $this->findDuplicatedEmployee($employee, $existent_filtered);
//        $error_msg = "";
//        if($duplicated['existents'] > 0) {
//            $error_msg = $this->duplicatedEmployeeErrorMessage($duplicated);
//            var_dump($duplicated);
//            return Redirect::back()->with(['creation_error' => $error_msg]);;
//        }


        //endregion


        $employee = new Employee();

        $employee->photography = Employee::fileAttribute($request->file('photography'), null);
        $employee->name = $request->input('name');
        $employee->type = $request->input('type');
        /*$employee->last_name = $request->input('last_name');*/
        $employee->birthdate = $request->input('birthdate');
        $employee->cell_phone = $request->input('cell_phone');
        $employee->direction = $request->input('direction');
        $employee->imss_number = $request->input('imss_number');

        $employee->imss = $imss;

        $employee->curp = $request->input('curp');
        $employee->rfc = $request->input('rfc');

        $employee->stall = $request->input('stall');
        $employee->salary_week = $request->input('salary_week');
        $employee->registration_date = $request->input('registration_date');

        $employee->status = $request->input('status');
        $employee->bank = $request->input('bank');
        $employee->clabe = $request->input('clabe');
        $employee->account = $request->input('account');
        $employee->save();

        /*$public_works = $request->input('public_works_id');

        for ($i = 0; $i < sizeof($public_works); $i++){
            EmployeesPublicWork::create([
                'employee_id' => $employee->id,
                'public_work_id' => $public_works[$i]
            ]);
        }*/

        Session::flash('success','Empleado creado correctamente.');
        return Redirect('/employees');
    }


    private function duplicatedEmployeeErrorMessage(array $duplicated)
    {
        $fields = $duplicated['fields'];
        $total = $duplicated['existents'];
        $result = "Existen $total empleados con la misma información en los siguientes campos: ";

        foreach ($fields as $field) {
            $fname = $this->renameEmployeeEfieldForDisplay($field);
            $result .= "$fname, ";
        }
        $result = rtrim($result, ', ');
        return $result;
    }

    private function renameEmployeeEfieldForDisplay(string $field)
    {
        $result = "";
        switch ($field) {
            case 'name':
                $result = 'Nombre';
                break;

            case 'curp':
                $result = 'CURP';
                break;

            case 'cell_phone':
                $result = 'Celular';
                break;

            case 'birthdate':
                $result = 'Fecha De Nacimiento';
                break;

            case 'clabe':
                $result = 'CLABE';
                break;

            default:
                $result = $field;
                break;
        }
        return $result;
    }

    private function findDuplicatedEmployee(array $employee, $existents)
    {
        $total = count($existents);
        if ($total === 0) {
            return [
                'existents' => 0,
                'fields' => []
            ];
        }
        $fields = [];
        $total = 0;
        foreach ($existents as $existent) {
            $other = $this->employeeToArray($existent);
            $fields = array_merge($fields, $this->compareEmployees($employee, $other));
            if(count($fields) > 0){
                $total += 1;
            }
        }
        return [
            'existents' => $total,
            'fields' => array_unique($fields)
        ];
    }

    private function findExistentEmployeesDb(array $employee)
    {
        return Employee::where('name', 'like', '%' . $employee['name'] . '%')
            ->orWhere('curp', 'like', '%' . $employee['curp'] . '%')
            ->orWhere('birthdate', 'like', '%' . $employee['birthdate'] . '%')
            ->orWhere('cell_phone', 'like', '%' . $employee['cell_phone'] . '%')
            ->get();
    }

    private function compareEmployees(array $employee, array $other)
    {
        $repeated_fields = [];

        foreach ($employee as $key => $value) {
            if ($employee[$key] === $other[$key]) {
                $repeated_fields[] = $key;
            }
        }
        return $repeated_fields;
    }

    private function employeeToArray(Employee $employee)
    {
        return $employee->toArray();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee= Employee::find($id);

        return view('employees.details',compact('employee'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /*if(Auth::user()->role==1){
            $employee = Employee::find($id);
            return view('employees.edit', compact('employee'));
        }else{
            return Redirect('/employees');
        }*/

        $employee = Employee::find($id);
        return view('employees.edit', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EmployeesRequest $request, $id)
    {
        /*echo "<pre>";
        var_dump($request->all());
        echo "</pre>";
        return ;*/

        $employee = Employee::find($id);

        if ($request->hasFile('photography')){
            $employee->photography = Employee::fileAttribute($request->file('photography'), $employee->id);
        }else {
            if($request->input('type')=='2'){
                if($employee->photography != ''){
                    if(file_exists(public_path().$employee->photography)){
                        unlink(public_path().$employee->photography);
                    }
                }

                $employee->photography = null;
            }
        }

        $imss = '';
        if(isset($request['imss'])){
            if($request['imss']=='on'){
                $imss = 0;
            }else{
                $imss = $request['imss'];
            }
        }else{
            $imss = 1;
        }

        $employee->name = $request->input('name');
        $employee->type = $request->input('type');
        /*$employee->last_name = $request->input('last_name');*/
        $employee->birthdate = $request->input('birthdate');
        $employee->cell_phone = $request->input('cell_phone');
        $employee->direction = $request->input('direction');
        $employee->imss_number = $request->input('imss_number');

        $employee->imss = $imss;

        $employee->curp = $request->input('curp');
        $employee->rfc = $request->input('rfc');
        /*$employee->aptitudes = $request->input('aptitudes');*/
        $employee->stall = $request->input('stall');
        $employee->salary_week = $request->input('salary_week');
        $employee->registration_date = $request->input('registration_date');
        /*$employee->phone = $request->input('phone');*/
        $employee->status = $request->input('status');
        $employee->bank = $request->input('bank');
        $employee->clabe = $request->input('clabe');
        $employee->account = $request->input('account');
        $employee->save();

        Session::flash('success','Empleado actualizado correctamente.');
        return Redirect('/employees');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = Employee::find($id);

        if($employee->photography != ''){
            if(file_exists(public_path().$employee->photography)){
                unlink(public_path().$employee->photography);
            }
        }

        $employee->delete();
        Session::flash('success','Empleado eliminado correctamente');
    }

    public function editPieceworker($id)
    {
        $employee = Employee::find($id);
        return view('employees.editPieceworker', compact('employee'));
    }

    public function getPayrolls($employee_id){
        $employee = Employee::find($employee_id);

        $payrolls = Payroll::where('employee_id', $employee_id)->get();

        $total = 0;

        foreach ($payrolls as $payroll){
            $public_work = PublicWork::find($payroll->public_work_id);

            if(!$public_work){
                $payroll->public_work = '';
            }else{
                $payroll->public_work = $public_work->name;
            }

            $total += (float)$payroll->total_salary;

            $payroll->format_total = "$".number_format($payroll->total_salary,'2','.',',');
        }

        $total = "$".number_format($total,'2','.',',');

        return view('employees.payrolls', compact('employee', 'payrolls', 'total'));
    }

    public function createPayroll($employee_id){
        $employee = Employee::find($employee_id);

        $bonuses_array = [];
        /*$bonuses = Bonus::all()->pluck('name', 'id')->toArray();*/
        $bonuses = Bonus::all();
        foreach ($bonuses as $bonus){
            $bonuses_array[$bonus->id] = $bonus->name." - $".$bonus->amount;
        }

        $extra = Bonus::all()->pluck('amount', 'id')->toArray();

        $public_works = PublicWork::where('status', '1')->pluck('name', 'id')->toArray();

        return view('payrolls.create', compact('employee', 'bonuses_array', 'extra', 'public_works'));
    }

    public function createPieceworkerPayroll($employee_id){
        $employee = Employee::find($employee_id);

        $public_works = PublicWork::where('status', '1')->pluck('name', 'id')->toArray();

        return view('payrolls.createPieceworkerPayroll', compact('employee', 'public_works'));
    }

    public function editPayroll($payroll_id){
        $payroll = Payroll::find($payroll_id);

        $employee = Employee::find($payroll->employee_id);

        $bonuses_array = [];
        $bonuses = Bonus::all();
        foreach ($bonuses as $bonus){
            $bonuses_array[$bonus->id] = $bonus->name." - $".$bonus->amount;
        }

        $extra = Bonus::all()->pluck('amount', 'id')->toArray();

        $count = sizeof($payroll->Bonuses);

        $public_works = PublicWork::where('status', '1')->pluck('name', 'id')->toArray();

        $type = 'Employee';

        return view('payrolls.edit', compact('payroll', 'employee', 'bonuses_array', 'extra', 'count', 'public_works', 'type'));
    }

    public function editPieceworkerPayroll($payroll_id){
        $payroll = Payroll::find($payroll_id);

        $employee = Employee::find($payroll->employee_id);

        $public_works = PublicWork::where('status', '1')->pluck('name', 'id')->toArray();

        $type = 'Employee';

        return view('payrolls.editPieceworkerPayroll', compact('payroll', 'employee', 'public_works', 'type'));
    }
}
