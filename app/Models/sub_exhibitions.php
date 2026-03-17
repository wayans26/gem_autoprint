<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sub_exhibitions extends Model
{
    //
    protected $connection = 'visitor';
    protected $table = 'tbsubexhibitions';
    // public $incrementing = true;
    protected $primaryKey = 'idsubexhibitions';
    protected $keyType = 'string';
    protected $fillable = [
        'idexhibitions',
        'idsubexhibitions',
        'nama',
        'path',
    ];
}
