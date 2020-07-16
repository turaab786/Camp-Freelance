<?php

namespace App\Model\Chat;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportMessage extends Model
{
    use SoftDeletes;
    
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function messageSender()
    {
        return $this->hasOne('App\User', 'id', 'message_sender_id');
    }

    public function message()
    {
        return $this->hasOne('App\Modal\Chat\Message', 'id', 'message_id');
    }
}
