<?php

namespace Noorfarooqy\SalaamEsb\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EncryptionKeys extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    protected $hidden = [
        'is_system_key',
    ];
}
