<?php

namespace App\Models;

use Core\Model;

class User extends Model 
{
    protected string $table = 'users';

    // public function posts() 
    // {
    //     return $this->hasMany(Post::class, 'user_id');
    // }
}
