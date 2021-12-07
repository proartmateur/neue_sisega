<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeesPublicWork extends Model
{
    protected $table = 'employees_public_works';
    protected $fillable = ['employee_id', 'public_work_id'];
}
