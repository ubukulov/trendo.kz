<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $table = 'user_profiles';

    protected $fillable = [
        'user_id', 'last_name', 'first_name', 'patronymic', 'phone', 'address', 'city_id'
    ];

    protected $dates = ['created_at', 'updated_at'];
}
