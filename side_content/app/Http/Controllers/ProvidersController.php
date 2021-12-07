<?php

namespace App\Http\Controllers;

use App\Concept;
use App\Http\Requests\OrdersRequest;
use App\Http\Requests\PaymentsRequest;
use App\Http\Requests\ProvidersRequest;
use App\Order;
use App\Payment;
use App\Provider;
use App\PublicWork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class ProvidersController extends Controller
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
        $providers = Provider::all();

        foreach ($providers as $provider){
            $count = 0;
            $amount = 0;

            $all_orders = Order::where('provider_id', $provider->id)->get();

            $public_works_array = [];
            foreach ($all_orders as $item){
                $old_public_work = PublicWork::find($item->public_work_id);

                if(!empty($old_public_work)){
                    foreach ($old_public_work->supervisors as $supervisor){
                        if($supervisor->id == Auth::user()->id){
                            if (!in_array($old_public_work->id, $public_works_array)) {
                                array_push($public_works_array, $old_public_work->id);
                            }
                        }
                    }
                }
            }

            $orders = Order::where('provider_id', $provider->id)->whereIn('public_work_id', $public_works_array)->get();

            foreach ($orders as $order){
                $payments = Payment::where('order_id', $order->id)->get();

                foreach ($payments as $payment){
                    if($payment->status == '1'){
                        $count += 1;
                        $amount += $payment->amount;
                    }
                }
            }

            /*$provider->format_bill = "$".number_format($provider->bill,'2','.',',');*/
            /*$provider->format_bill = $count;*/
            $provider->format_bill = "$".number_format($amount,'2','.',',');
        }

        return view ('providers.index', compact('providers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->role==1){
            /*$public_works = PublicWork::select('id', 'name')->get();*/
            return view('providers.create');
        }else{
            return Redirect('/providers');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProvidersRequest $request)
    {
        $provider = Provider::create([
            'type' => $request['type'],
            'name' => $request['name'],
            'function' => $request['function'],
            /*'surnames' => $request['surnames'],
            'company' => $request['company'],*/
            'bank' => $request['bank'],
            'clabe' => $request['clabe'],
            'account' => $request['account']
        ]);

        /*$public_works = $request['public_works_id'];
        $provider->PublicWorks()->attach($public_works);*/

        Session::flash('success','Proveedor creado correctamente.');
        return Redirect::to('/providers');
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
        /*if(Auth::user()->role==1){
            $provider = Provider::find($id);

            return view('providers.edit', compact('provider'));
        }else{
            return Redirect('/providers');
        }*/

        $provider = Provider::find($id);

        return view('providers.edit', compact('provider'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProvidersRequest $request, $id)
    {
        $provider = Provider::find($id);

        $provider->update([
            'type' => $request['type'],
            'name' => $request['name'],
            'function' => $request['function'],
            /*'surnames' => $request['surnames'],
            'company' => $request['company'],*/
            'bank' => $request['bank'],
            'clabe' => $request['clabe'],
            'account' => $request['account']
        ]);

        /*$public_works = $request['public_works_id'];
        $provider->PublicWorks()->sync($public_works);*/

        Session::flash('success', 'Proveedor actualizado correctamente.');
        return Redirect::to('/providers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $provider = Provider::find($id);
        $orders = Order::where('provider_id', $provider->id)->get();

        foreach ($orders as $order){
            $order->Concepts()->delete();
        }

        /*$provider->PublicWorks()->detach();*/
        $provider->Orders()->delete();
        $provider->delete();

        Session::flash('success', 'Proveedor eliminado correctamente');
    }

    public function getOrders($provider_id){
        $provider = Provider::find($provider_id);
        $all_orders = Order::where('provider_id', $provider_id)->get();

        $order_array = [];
        foreach ($all_orders as $order){
            $payments = Payment::where('order_id', $order->id)->get();

            $amount = 0;
            foreach ($payments as $payment){
                if($payment->status == '1'){
                    $amount += $payment->amount;
                }
            }

            if($amount > 0){
                array_push($order_array, $order->id);
            }
        }

        $orders = Order::where('provider_id', $provider_id)->whereNotIn('id', $order_array)->get();

        foreach ($orders as $order){
            $public_work = PublicWork::find($order->public_work_id);
            $order->public_work = $public_work->name;

            $remaining = (float)((float)$order->budget-(float)$order->payment);
            $order->format_payment = "$".number_format($order->payment,'2','.',',');
            $order->format_budget = "$".number_format($order->budget,'2','.',',');
            $order->remaining = "$".number_format($remaining,'2','.',',');
            $order->format_subtotal = "$".number_format($order->subtotal,'2','.',',');
            $order->format_iva = "$".number_format($order->iva,'2','.',',');

            $payments = Payment::where('order_id', $order->id)->get();

            $amount = 0;
            foreach ($payments as $payment){
                if($payment->status == '1'){
                    $amount += $payment->amount;
                }
            }

            $order->format_bill = "$".number_format($amount,'2','.',',');
        }

        return view('orders.index', compact('provider', 'orders'));
    }

    public function createOrder($provider_id){
        $array = [];

        $provider = Provider::find($provider_id);
        /*foreach ($provider->PublicWorks as $publicWork){
            array_push($array, $publicWork->id);
        }*/

        $public_works = PublicWork::where('status', '1')->pluck('name', 'id')->toArray();

        return view('orders.create', compact('provider', 'public_works'));
    }

    public function storeOrder(OrdersRequest $request){
        $order = Order::create([
            'provider_id' => $request['provider_id'],
            'public_work_id' => $request['public_work_id']
        ]);

        $concepts = $request['concepts_list'];

        $subtotal = 0;
        if(!empty($concepts)){
            foreach ($concepts as $key => $item){
                $concept = Concept::create([
                    'order_id' => $order->id,
                    'concept' => $concepts[$key]['concept'],
                    'measurement' => $concepts[$key]['measurement'],
                    'quantity' => $concepts[$key]['quantity'],
                    /*'sisega_price' => $concepts[$key]['sisega_price'] != '' ? $concepts[$key]['sisega_price'] : 0,*/
                    'purchase_price' => $concepts[$key]['purchase_price']
                ]);

                $concept_amount = (float)((float)($concept->quantity)*(float)($concept->purchase_price));

                $subtotal += $concept_amount;
            }
        }

        $iva = 0;

        if($request->has('iva')){
            $iva = (float)$subtotal*(0.16);
        }

        $total = (float)((float)$subtotal + (float)$iva);

        $order->budget = $total;
        $order->subtotal = $subtotal;
        $order->iva = $iva;
        $order->save();

        $amount = $this->updateAmout($request['provider_id']);

        $provider = Provider::find($request['provider_id']);
        $provider->bill = $amount;
        $provider->save();

        Session::flash('success', 'Orden registrada correctamente.');
        return Redirect::to('/providers/getOrders/'.$request['provider_id']);
    }

    public function editOrder($order_id){
        $order = Order::find($order_id);

        $array = [];

        $provider = Provider::find($order->provider_id);
        /*foreach ($provider->PublicWorks as $publicWork){
            array_push($array, $publicWork->id);
        }*/

        $public_works = PublicWork::where('status', '1')->pluck('name', 'id')->toArray();

        $concepts = Concept::where('order_id', $order->id)->get();

        return view('orders.edit', compact('order', 'provider', 'public_works', 'concepts'));
    }

    public function updateOrder(OrdersRequest $request){
        $order = Order::find($request['order_id']);

        $order->update([
            'public_work_id' => $request['public_work_id']
        ]);

        $concepts = $request['concepts_list'];
        $delete_concepts = [];

        if(!empty($concepts)){
            foreach ($concepts as $key => $item){
                if($concepts[$key]['concept_id'] != null){
                    $concept = Concept::find($concepts[$key]['concept_id']);

                    $concept->update([
                        'concept' => $concepts[$key]['concept'],
                        'measurement' => $concepts[$key]['measurement'],
                        'quantity' => $concepts[$key]['quantity'],
                        /*'sisega_price' => $concepts[$key]['sisega_price'],*/
                        'purchase_price' => $concepts[$key]['purchase_price']
                    ]);

                    array_push($delete_concepts, $concept->id);
                }else{
                    $concept = Concept::create([
                        'order_id' => $order->id,
                        'concept' => $concepts[$key]['concept'],
                        'measurement' => $concepts[$key]['measurement'],
                        'quantity' => $concepts[$key]['quantity'],
                        /*'sisega_price' => $concepts[$key]['sisega_price'],*/
                        'purchase_price' => $concepts[$key]['purchase_price']
                    ]);

                    array_push($delete_concepts, $concept->id);
                }
            }
        }

        Concept::where('order_id', $order->id)->whereNotIn('id', $delete_concepts)->delete();

        $subtotal = 0;
        foreach ($order->Concepts as $concept){
            $concept_amount = (float)((float)($concept->quantity)*(float)($concept->purchase_price));

            $subtotal += $concept_amount;
        }

        $iva = 0;

        if($request->has('iva')){
            $iva = (float)$subtotal*(0.16);
        }

        $total = (float)((float)$subtotal + (float)$iva);

        $order->budget = $total;
        $order->subtotal = $subtotal;
        $order->iva = $iva;
        $order->save();

        $amount = $this->updateAmout($order->provider_id);

        $provider = Provider::find($order->provider_id);
        $provider->bill = $amount;
        $provider->save();

        Session::flash('success', 'Orden actualizada correctamente.');
        return Redirect::to('/providers/getOrders/'.$order->provider_id);
    }

    public function deleteOrder($order_id){
        $order = Order::find($order_id);
        $order->Concepts()->delete();
        $order->Payments()->delete();
        $order->delete();

        $amount = $this->updateAmout($order->provider_id);

        $provider = Provider::find($order->provider_id);
        $provider->bill = $amount;
        $provider->save();

        Session::flash('success', 'Orden eliminada correctamente');
    }

    public function search_orders(Request $request, Order $order){
        $all_orders = Order::where('provider_id', $request['provider_id'])->get();

        $public_works_array = [];
        foreach ($all_orders as $item){
            $old_public_work = PublicWork::find($item->public_work_id);

            foreach ($old_public_work->supervisors as $supervisor){
                if($supervisor->id == Auth::user()->id){
                    if (!in_array($old_public_work->id, $public_works_array)) {
                        array_push($public_works_array, $old_public_work->id);
                    }
                }
            }
        }

        $orders = Order::where('provider_id', $request['provider_id'])->whereIn('orders.public_work_id', $public_works_array)->get();

        $orders_array = [];
        $orders_paid = [];
        foreach ($orders as $item){
            $payments = Payment::where('order_id', $item->id)->get();

            if(sizeof($payments)==0){
                array_push($orders_array, $item->id);
            }else{
                $flag = true;

                foreach ($payments as $payment){
                    if($payment->status == '1'){
                        $flag = false;

                        break;
                    }
                }

                if(!$flag){
                    array_push($orders_array, $item->id);
                }else{
                    array_push($orders_paid, $item->id);
                }
            }
        }

        $query = $order->newQuery();

        if($request['status'] == 'true'){
            $query->select('orders.id AS id', 'orders.public_work_id AS public_work_id', 'orders.payment AS payment', 'orders.budget AS budget',
                'orders.subtotal AS subtotal', 'orders.iva AS iva')
                ->where('provider_id', $request['provider_id'])
                ->whereIn('orders.id', $orders_paid);
        }else{
            $query->select('orders.id AS id', 'orders.public_work_id AS public_work_id', 'orders.payment AS payment', 'orders.budget AS budget',
                'orders.subtotal AS subtotal', 'orders.iva AS iva')
                ->whereIn('orders.id', $orders_array);
        }

        $query = $query->get();

        if(sizeof($query)<1){
            return "";
        }

        $array = [];

        foreach ($query as $key){
            $public_work = PublicWork::find($key->public_work_id);

            $buttons = '';
            $bill = 0;

            $payments = Payment::where('order_id', $key->id)->get();

            foreach ($payments as $payment){
                if($payment->status == '1'){
                    $bill += $payment->amount;
                }
            }

            if(Auth::user()->role==1){
                $buttons = '<a href="/providers/getPayments/'.$key->id.'" title="Pagos" class="btn btn-primary"><i class="fa fa-dollar-sign"></i><span></span></a>
                    <a href="/providers/getConcepts/'.$key->id.'" title="Conceptos" class="btn btn-success"><i class="flaticon flaticon-interface-11"></i><span></span></a>
                    <a href="/providers/editOrder/'.$key->id.'" title="Editar" class="btn btn-success" id="button"><i class="flaticon flaticon-edit"></i><span></span></a>
                    <a href="#mySpecialModal" data-toggle="modal" click=modalDelete title="Eliminar" class="btn btn-danger openBtn" onclick="setModal('.$key->id.')">
                    <i class="flaticon flaticon-delete"></i><span></span></a>';
            }else{
                $buttons = '<a href="/providers/getPayments/'.$key->id.'" title="Pagos" class="btn btn-primary"><i class="fa fa-dollar-sign"></i><span></span></a>
                    <a href="/providers/getConcepts/'.$key->id.'" title="Conceptos" class="btn btn-success"><i class="flaticon flaticon-interface-11"></i><span></span></a>
                    <a href="/providers/editOrder/'.$key->id.'" title="Editar" class="btn btn-success" id="button"><i class="flaticon flaticon-edit"></i><span></span></a>';
            }

            $class = new \stdClass;
            $class->buttons = $buttons;
            $class->public_work = $public_work->name;
            $class->payment = "$".number_format($key->payment,'2','.',',');
            $class->budget = "$".number_format($key->budget,'2','.',',');
            $class->bill = "$".number_format($bill,'2','.',',');
            $class->subtotal = "$".number_format($key->subtotal,'2','.',',');
            $class->iva = "$".number_format($key->iva,'2','.',',');
            $class->orders_array = $orders_array;

            array_push($array, $class);

            /*if($request['status'] != 'true'){
                if($bill > 0){
                    $class = new \stdClass;
                    $class->buttons = $buttons;
                    $class->public_work = $public_work->name;
                    $class->payment = "$".number_format($key->payment,'2','.',',');
                    $class->budget = "$".number_format($key->budget,'2','.',',');
                    $class->bill = "$".number_format($bill,'2','.',',');
                    $class->subtotal = "$".number_format($key->subtotal,'2','.',',');
                    $class->iva = "$".number_format($key->iva,'2','.',',');

                    array_push($array, $class);
                }
            }else{
                $class = new \stdClass;
                $class->buttons = $buttons;
                $class->public_work = $public_work->name;
                $class->payment = "$".number_format($key->payment,'2','.',',');
                $class->budget = "$".number_format($key->budget,'2','.',',');
                $class->bill = "$".number_format($bill,'2','.',',');
                $class->subtotal = "$".number_format($key->subtotal,'2','.',',');
                $class->iva = "$".number_format($key->iva,'2','.',',');

                array_push($array, $class);
            }*/
        }

        return [$array];
    }

    public function getConcepts($order_id){
        $order = Order::find($order_id);
        $concepts = Concept::where('order_id', $order_id)->get();

        $subtotal = 0;
        foreach ($concepts as $concept){
            $difference = 0;

            if($concept->sisega_price!=0){
                $difference = (float)((float)($concept->sisega_price)-(float)($concept->purchase_price));
            }

            $concept->format_sisega_price = "$".number_format($concept->sisega_price,'2','.',',');
            $concept->format_purchase_price = "$".number_format($concept->purchase_price,'2','.',',');

            $concept->difference = "$".number_format($difference,'2','.',',');

            $amount = (float)((float)($concept->quantity)*(float)($concept->purchase_price));
            $concept->amount = "$".number_format($amount,'2','.',',');

            $subtotal += $amount;
        }

        $total = (float)((float)$order->subtotal + (float)$order->iva);

        $subtotal = "$".number_format($subtotal,'2','.',',');
        $iva = "$".number_format($order->iva,'2','.',',');
        $total = "$".number_format($total,'2','.',',');

        return view('concepts.index', compact('order', 'concepts', 'subtotal', 'iva', 'total'));
    }

    public function getPayments($order_id){
        $order = Order::find($order_id);

        $payments = Payment::where('order_id', $order_id)->get();

        $count = 1;
        foreach ($payments as $payment){
            $payment->consecutive = $count;
            $payment->format_amount = "$".number_format($payment->amount,'2','.',',');

            $count++;
        }

        return view('payments.index', compact('order', 'payments'));
    }

    public function createPayment($order_id){
        $order = Order::find($order_id);

        /*$debt = (float)((float)$order->budget-(float)$order->payment);*/
        $paid = 0;
        foreach ($order->Payments as $payment){
            $paid += (float)$payment->amount;
        }
        $debt = (float)((float)$order->budget-(float)$paid);

        return view('payments.create', compact('order', 'debt'));
    }

    public function storePayment(PaymentsRequest $request){
        $payment = Payment::create([
            'date' => $request['date'],
            'pdf' => isset($request['pdf']) ? Payment::fileAttribute($request['pdf'], null) : null,
            'amount' => $request['amount'],
            'comments' => $request['comments'],
            'order_id' => $request['order_id'],
            'status' => '1'
        ]);

        $order = Order::find($request['order_id']);
        $provider = Provider::find($order->provider_id);
        $public_work = PublicWork::find($order->public_work_id);

        $data = new \stdClass();
        $data->public_work = $public_work->name;
        $data->provider = $provider->name;
        $data->provider_bank = $provider->bank;
        $data->provider_account = $provider->account;
        $data->provider_clabe = $provider->clabe;
        $data->user = Auth::user()->name;
        $data->date = $payment->date;
        $data->amount = "$".number_format($payment->amount,'2','.',',');
        $data->comments = $payment->comments;

        $hostName = $_SERVER['HTTP_HOST'];

        $protocol = 'http://';

        $file_path = $protocol.$hostName.$payment->pdf;

        if($payment->pdf != null){
            Mail::send('emails.payment',compact('data'), function ($m) use ($data, $file_path){
                $m->from('contacto@sisega.app');
                $m->to('contacto@sisega.app')->subject("O.C. ".$data->public_work." - ".$data->provider);
                $m->attach($file_path);
            });
        }else{
            Mail::send('emails.payment',compact('data'), function ($m) use ($data){
                $m->from('contacto@sisega.app');
                $m->to('contacto@sisega.app')->subject("O.C. ".$data->public_work." - ".$data->provider);
            });
        }

        if($request['mail']=="true"){
            $notification = new \stdClass();
            $notification->public_work = $public_work->name;
            $notification->provider = $provider->name;
            $notification->date = $payment->date;
            $notification->amount = "$".number_format($payment->amount,'2','.',',');
            $notification->comments = $payment->comments;

            foreach ($public_work->supervisors as $supervisor){
                if($payment->pdf != null){
                    Mail::send('emails.debt_settled',compact('notification'), function ($m) use ($notification, $supervisor, $file_path){
                        $m->from('contacto@sisega.app');
                        $m->to($supervisor->email)->subject("C.P. ".$notification->public_work." - ".$notification->provider);
                        $m->attach($file_path);
                    });
                }else{
                    Mail::send('emails.debt_settled',compact('notification'), function ($m) use ($notification, $supervisor){
                        $m->from('contacto@sisega.app');
                        $m->to($supervisor->email)->subject("C.P. ".$notification->public_work." - ".$notification->provider);
                    });
                }
            }
        }

        Session::flash('success', 'Pago registrado correctamente.');
        return Redirect::to('/providers/getPayments/'.$request['order_id']);
    }

    public function editPayment($payment_id){
        $payment = Payment::find($payment_id);

        $order = Order::find($payment->order_id);

        /*$debt = 0;

        if($payment->status == '2'){
            $debt = (float)(((float)$order->budget-(float)$order->payment)+(float)$payment->amount);
        }else{
            $debt = (float)(((float)$order->budget-(float)$order->payment));
        }*/

        $paid = 0;
        foreach ($order->Payments as $order_payment){
            $paid += (float)$order_payment->amount;
        }
        $debt = (float)(((float)$order->budget-(float)$paid)+(float)$payment->amount);

        return view('payments.edit', compact('payment', 'order', 'debt'));
    }

    public function updatePayment(PaymentsRequest $request){
        $payment = Payment::find($request['payment_id']);

        $payment->update([
            'date' => $request['date'],
            'pdf' => isset($request['pdf']) ? Payment::fileAttribute($request['pdf'], null) : null,
            'amount' => $request['amount'],
            'comments' => $request['comments']
        ]);

        $order = Order::find($payment->order_id);

        $payment_total = 0;
        foreach ($order->Payments as $order_payment){
            if($order_payment->status != '1'){
                $payment_total += (float)$order_payment->amount;
            }
        }

        $order->payment = $payment_total;
        $order->save();

        $amount = $this->updateAmout($order->provider_id);

        $provider = Provider::find($order->provider_id);
        $provider->bill = $amount;
        $provider->save();

        $public_work = PublicWork::find($order->public_work_id);

        $hostName = $_SERVER['HTTP_HOST'];
        $protocol = 'http://';
        $file_path = $protocol.$hostName.$payment->pdf;

        if($request['mail']=="true"){
            $notification = new \stdClass();
            $notification->public_work = $public_work->name;
            $notification->provider = $provider->name;
            $notification->date = $payment->date;
            $notification->amount = "$".number_format($payment->amount,'2','.',',');
            $notification->comments = $payment->comments;

            foreach ($public_work->supervisors as $supervisor){
                if($payment->pdf != null){
                    Mail::send('emails.debt_settled',compact('notification'), function ($m) use ($notification, $supervisor, $file_path){
                        $m->from('contacto@sisega.app');
                        $m->to($supervisor->email)->subject("C.P. ".$notification->public_work." - ".$notification->provider);
                        $m->attach($file_path);
                    });
                }else{
                    Mail::send('emails.debt_settled',compact('notification'), function ($m) use ($notification, $supervisor){
                        $m->from('contacto@sisega.app');
                        $m->to($supervisor->email)->subject("C.P. ".$notification->public_work." - ".$notification->provider);
                    });
                }
            }
        }

        Session::flash('success', 'Pago actualizado correctamente.');
        return Redirect::to('/providers/getPayments/'.$order->id);
    }

    public function deletePayment($payment_id){
        $payment = Payment::find($payment_id);
        $payment->delete();

        $order = Order::find($payment->order_id);

        $payment_total = 0;
        foreach ($order->Payments as $order_payment){
            if($order_payment->status != '1'){
                $payment_total += (float)$order_payment->amount;
            }
        }

        $order->payment = $payment_total;
        $order->save();

        $amount = $this->updateAmout($order->provider_id);

        $provider = Provider::find($order->provider_id);
        $provider->bill = $amount;
        $provider->save();

        Session::flash('success', 'Orden eliminada correctamente');
    }

    public function change_status(Request $request)
    {
        $status = $request ['status'] == 'true' ? '2' : '1';
        $payment = Payment::find($request['id']);
        $payment->status = $status;
        $payment-> update();

        $order = Order::find($request['order_id']);

        $payment_total = 0;
        foreach ($order->Payments as $order_payment){
            if($order_payment->status != '1'){
                $payment_total += (float)$order_payment->amount;
            }
        }

        $order->payment = $payment_total;
        $order->save();

        $amount = $this->updateAmout($order->provider_id);

        $provider = Provider::find($order->provider_id);
        $provider->bill = $amount;
        $provider->save();

        Session::flash('success', 'Estatus cambiado correctamente.');
    }

    public function updateAmout($provider_id){
        $provider = Provider::find($provider_id);

        $payment_total = 0;
        $budget_total = 0;
        foreach ($provider->Orders as $order){
            foreach ($order->Payments as $payment){
                if($payment->status != '1'){
                    $payment_total += (float)$payment->amount;
                }
            }

            $budget_total += (float)$order->budget;
        }

        $amount = (float)((float)$budget_total-(float)$payment_total);

        return $amount;
    }
}
