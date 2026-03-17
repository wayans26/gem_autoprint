<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class country extends Model
{
    //
    protected $connection = 'visitor';
    protected $table = 'tbcountries';
    // public $incrementing = true;
    protected $primaryKey = 'idcountry';
    protected $keyType = 'string';
    protected $fillable = [
        'idcountry',
        'country_name',
        'dial_code',
    ];
}
