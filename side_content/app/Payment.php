<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $fillable = ['date', 'amount', 'comments', 'order_id', 'status', 'pdf'];

    public static function fileAttribute($file, $payment_id){
        if(is_file($file)){
            $folder = public_path().'/pdf/payments/';

            $ext = $file->getClientOriginalExtension();
            $fileName = \Str::random(10)."_".time(). "." . $ext;
            $path = '/pdf/payments/'.$fileName;

            if ($payment_id != null){
                $payment = Payment::find($payment_id);
                $pathFile = public_path().$payment->pdf;

                if (file_exists($pathFile)){
                    unlink($pathFile);
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
