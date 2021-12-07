<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $table = 'payrolls';
    protected $fillable = ['employee_id', 'days_worked', 'hours_worked', 'extra_hours', 'comments', 'total_salary', 'date', 'public_work_id', 'clonado'];

    public function Bonuses(){
        /*return $this->belongsToMany('App\Bonus', 'payroll_bonuses', 'payroll_id', 'bonus_id')->withPivot('date');*/
        return $this->belongsToMany('App\Bonus', 'payroll_bonuses', 'payroll_id', 'bonus_id');
    }
}
