<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Concept extends Model
{
    protected $table = 'concepts';
    protected $fillable = ['order_id', 'concept', 'measurement', 'quantity', 'sisega_price', 'purchase_price'];
}
