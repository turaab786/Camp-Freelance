<?php

namespace App\Model\Chat;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatUser extends Model
{
    use SoftDeletes;
    
    protected $guarded = [];

    public function sender()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function reciver()
    {
        return $this->hasOne('App\User', 'id', 'reciver_id');
    }
}
