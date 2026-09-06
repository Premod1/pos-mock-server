<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Terminal extends Model
{
    protected $fillable = ['machine_ip', 'machine_port', 'dont_save_data'];

    protected $casts = [
        'dont_save_data' => 'boolean',
    ];
}
