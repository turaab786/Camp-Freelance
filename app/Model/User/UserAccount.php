<?php

namespace App\Model\User;

use App\Model\MainModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAccount extends MainModel
{

    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'user_id', 'balance'
    // ];
    protected $guarded = [];
}
