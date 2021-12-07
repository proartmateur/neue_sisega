<?php

namespace App\Http\Controllers;
use App\Employee;
use Illuminate\Http\Request;
use App\Http\Requests\PublicWorkRequest;
use App\User;
use App\PublicWork;
use Illuminate\Support\Facades\Auth;
use Session;
use Redirect;

class PublicWorksController extends Controller
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
        $today = date("Y-m-d");
        $next_week = date("Y-m-d", strtotime($today."+ 1 week"));

        //$public_works = PublicWork::orderBy('end_date', 'desc')->where('status', '1')->whereDate('public_works.end_date','>=', $today)->get();

        $public_works = PublicWork::orderBy('end_date', 'desc')->where('status', '1')->get();

        foreach ($public_works as $public_work){
            if(($public_work->end_date >= $today) && ($public_work->end_date <= $next_week)){
                $public_work->mark = true;
            }elseif (($public_work->end_date <= $today)){
                $public_work->mark = true;
            }else{
                $public_work->mark = false;
            }

            $public_work->format_budget = "$".number_format(ceil($public_work->budget),'2','.',',');
        }

        return view('public_works.index', compact('public_works'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->role==1){
            $supervisors = User::orderBy('name', 'asc')->select('id','name')->get();
            return view('public_works.create', compact('supervisors'));
        }else{
            return Redirect('/public-works');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PublicWorkRequest $request)
    {
        /*echo "<pre>";
        var_dump($request->all());
        echo "</pre>";
        return ;*/

        $public_work = new PublicWork();

        $public_work->name = $request->input('name');
        $public_work->budget = $request->input('budget');
        /*$public_work->supervisor = $request->input('supervisor');*/
        $public_work->start_date = $request->input('start_date');
        $public_work->end_date = $request->input('end_date');
        $public_work->status = $request->input('status');
        $public_work->save();

        $supervisors = $request['supervisors'];

        if(!empty($supervisors)){
            $public_work->supervisors()->attach($supervisors);

            /*foreach ($bonuses as $key => $item){
                if($bonuses[$key]['bonus_id'] != null){
                    $payroll->Bonuses()->attach($bonuses[$key]['bonus_id']);
                }
            }*/
        }

        Session::flash('success', 'Obra creada correctamente.');
        return Redirect('/public-works');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /*$public_work_details = PublicWork::select('name', 'budget', 'supervisor', 'end_date')
            ->where('public_works.id', '=',  $id)
            ->get();*/

        $public_work = PublicWork::find($id);

        return view('public_works.details',compact('public_work'));
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
            $public_work = PublicWork::find($id);
            $supervisors = User::orderBy('name', 'asc')->select('id','name')->get();
            return view('public_works.edit', compact('public_work', 'supervisors'));
        }else{
            return Redirect('/public-works');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PublicWorkRequest $request, $id)
    {
        $public_work = PublicWork::find($id);

        $public_work->update([
            'name' => $request['name'],
            'budget' => $request['budget'],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            'status' => $request['status'],
        ]);

        $supervisors = $request['supervisors'];

        if(!empty($supervisors)){
            $public_work->supervisors()->sync($supervisors);
        }

        Session::flash('success', 'Obra actualizada correctamente.');
        return Redirect::to('/public-works');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $public_works = PublicWork::find($id);
        $public_works->status = 3;
        $public_works->save();
        //$public_works->delete();
        Session::flash('success', 'Obra eliminada correctamente');
    }

    public function borrarObra($id)
    {
        $public_works = PublicWork::find($id);
        $public_works->status = 3;
        $public_works->save();
        //$public_works->delete();
        Session::flash('success', 'Obra eliminada correctamente');
    }

    public function search_public_works(Request $request, PublicWork $publicWork){
        $query = $publicWork->newQuery();

        $status = $request ['status'] == 'true' ? 1 : 0;

        $today = date("Y-m-d");
        $next_week = date("Y-m-d", strtotime($today."+ 1 week"));

        $query->orderBy('public_works.end_date', 'desc')->select('public_works.id AS id', 'public_works.name AS name',
            'public_works.budget AS budget', 'public_works.start_date AS start_date', 'public_works.end_date AS end_date', 'public_works.status AS status');

        /*if($request ['status'] != 'true'){
            $query->where('public_works.status', '1')->whereDate('public_works.end_date','>=', $today);
        }else{
            $query->where('public_works.status', '2')->orWhereDate('public_works.end_date','<', $today);
        }*/

        if($request ['status'] != 'true'){
            $query->where('public_works.status', '1');
        }else{
            $query->where('public_works.status', '2');
        }

        $query = $query->get();

        if(sizeof($query)<1){
            return "";
        }

        $array = [];

        foreach ($query as $key){
            $public_work = PublicWork::find($key->id);

            $buttons = '';
            $list = '';
            $mark = '';

            if(Auth::user()->role==1){
                $buttons = '<a href="/public-works/'.$key->id.'" title="Detalles" class="btn btn-info "><i class="flaticon flaticon-eye"></i><span></span></a>
                <a href="/public-works/'.$key->id.'/edit" title="Editar" class="btn btn-success" id="button"><i class="flaticon flaticon-edit"></i><span></span></a>
                <a href="#myModal" data-toggle="modal" click=modalDelete title="Eliminar" class="btn btn-danger openBtn" onclick="setModal('.$key->id.')">
                <i class="flaticon flaticon-delete"></i><span></span></a>';
            }else{
                $buttons = '<a href="/public-works/'.$key->id.'" title="Detalles" class="btn btn-info "><i class="flaticon flaticon-eye"></i><span></span></a>';
            }

            if(($key->end_date >= $today) && ($key->end_date <= $next_week)){
                $mark = '<span style="color: red">'.$key->end_date.'</span>';
            }elseif (($public_work->end_date <= $today)){
                $mark = '<span style="color: red">'.$key->end_date.'</span>';
            }else{
                $mark = '<span>'.$key->end_date.'</span>';
            }

            foreach ($public_work->supervisors as $supervisor){
                $list = $list.'<li>'.$supervisor->name.'</li>';
            }

            $class = new \stdClass;
            $class->buttons = $buttons;
            $class->name = $key->name;
            $class->budget = "$".number_format(ceil($key->budget),'2','.',',');
            $class->supervisors = $list;
            $class->end_date = $mark;

            array_push($array, $class);
        }

        return [$array];
    }
}
