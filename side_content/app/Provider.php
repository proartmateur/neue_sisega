<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $table = 'providers';
    protected $fillable = ['type', 'name', 'function', 'bank', 'clabe', 'account', 'bill'];

    /*public function PublicWorks(){
        return $this->belongsToMany('App\PublicWork','providers_public_works','provider_id','public_work_id');
    }*/

    public function Orders(){
        return $this->hasMany('App\Order','provider_id');
    }
}
