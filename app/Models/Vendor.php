<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'title', 'alias', 'type', 'quantity', 'active', 'created_at', 'updated_at'
    ];
}
