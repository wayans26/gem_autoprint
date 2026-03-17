<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class exhibitions extends Model
{
    //
    protected $connection = 'visitor';
    protected $table = 'tbexhibitions';
    // public $incrementing = true;
    protected $primaryKey = 'idexhibitions';
    protected $keyType = 'string';
    protected $fillable = [
        'idexhibitions',
        'name',
        'path',
        'all_banner',
        'web_own',
        'status',
        'tanggal',
        'keterangan',
        'event_name',
        'opening_hours',
        'type',
        'custom_tag',
    ];
}
