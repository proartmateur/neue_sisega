<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $table = 'employees';
    protected $fillable = ['photography', 'name','last_name', 'type', 'birthdate', 'cell_phone','direction', 'imss_number', 'imss', 'curp',
        'rfc', 'stall', 'salary_week', 'public_works_id', 'registration_date', 'status', 'bank', 'clabe', 'account'];
    public static function fileAttribute($file,$employee_id){

        if(is_file($file)){
            $folder = public_path().'/images/employee/';

            $ext = $file->getClientOriginalExtension();
            $fileName = \Str::random(10)."_".time(). "." . $ext;
            $path = '/images/employee/'.$fileName;

            if ($employee_id != null){
                $employee = Employee:: where ('id',$employee_id)->first();

                if($employee->photography != ''){
                    $pathFile = public_path().$employee->photography;

                    if (file_exists($pathFile)){
                        unlink($pathFile);
                    }
                }
            }

            $pathDestination = $folder;
            if(!file_exists($folder)){
                mkdir($folder, 0777, true);
                $file->move($pathDestination, $fileName);
            }else{
                $file->move($pathDestination, $fileName);
            }

            return $path;
        }else{
            return null;
        }
    }
}
