<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class activity_history extends Model
{
    protected $connection = 'visitor';
    protected $table = 'activity_histories';
    public $incrementing = true;
    protected $primaryKey = 'id';
    // protected $keyType = 'string';
    protected $fillable = [
        'Id',
        'CheckedInTime',
        'CheckedInLocation',
        'CheckedBy',
        'registration_id',
    ];
}
