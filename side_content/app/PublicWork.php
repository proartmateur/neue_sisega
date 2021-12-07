<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class PublicWork extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $table = 'public_works';
    protected $fillable = ['name', 'budget', 'start_date', 'end_date', 'status'];

    public function supervisors(){
        return $this->belongsToMany('App\User','public_work_supervisors','public_work_id','user_id');
    }

    /*public function User(){
        return $this->hasOne('App\User','id','supervisor');
    }*/
}
