<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

use App\User;
use App\PublicWork;
use Session;
use Redirect;


class UsersController extends Controller
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
        $users = User::all();
        $public_works = PublicWork::pluck('name','id')->toArray();

        foreach ($users as $user){
            $user_public_works = DB::table('public_work_supervisors')->where('user_id', $user->id)->get();

            $array = [];
            foreach ($user_public_works as $item){
                $public_work = PublicWork::find($item->public_work_id);

                if(!empty($public_work)){
                    array_push($array, $public_work->name);
                }
            }

            $user->public_works = $array;
        }

        return view ('users.index',compact('users', 'public_works'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->role==1){
            return view('users.create');
        }else{
            return Redirect('/users');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $user = new User();


        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password= Hash::make($request->input('password'));
        $user->stall = $request->input('stall');
        $user->role = $request->input('role');
        $user->status = $request->input('status');
        $user->save();

        Session::flash('success','Usuario creado correctamente.');
        return Redirect('/users');
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
            $user = User::find($id);
            return view('users.edit',compact('user'));
        }else{
            return Redirect('/users');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $user = User::find($id);
        $this->validate(request(), [
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)]
        ]);

        $user->name = $request['name'];
        $user->email = $request['email'];

        if ($request['password']!=''){
            $user->password = Hash::make($request['password']);
        }

        $user->stall = $request['stall'];
        $user->role = $request['role'];
        $user->status = $request['status'];
        $user->update();

        Session::flash('message', 'El usuario ha sido actualizado correctamente');
        return redirect('/users');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        Session::flash('success','Usuario eliminado correctamente');
    }

    public function getSupervisors(Request $request){
        $keyWords = $request['q'];
        $data = User::orderBy('name', 'asc')
            ->select('id','name AS text')
            ->where('name','like','%'.$keyWords.'%')
            ->get();

        return response()->json($data);
    }
}
