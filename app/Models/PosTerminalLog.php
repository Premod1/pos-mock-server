<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosTerminalLog extends Model
{
    use HasFactory;

    protected $table = 'pos_terminal_logs';

    protected $fillable = [
        'agent_name',
        'level',
        'message',
        'client_timestamp'
    ];
}
