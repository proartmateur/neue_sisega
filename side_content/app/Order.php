<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $table = 'orders';
    protected $fillable = ['provider_id', 'public_work_id', 'payment', 'budget', 'subtotal', 'iva'];

    public function Concepts(){
        return $this->hasMany('App\Concept','order_id');
    }

    public function Payments(){
        return $this->hasMany('App\Payment','order_id');
    }
}
