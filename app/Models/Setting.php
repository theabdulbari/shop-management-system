<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'system_name',
        'logo',
        'db_backup_path',
        'currency_symbol',
    ];
}