<?php

namespace App\Model\App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppSetting extends Model
{
    use SoftDeletes;

    // protected $fillable = [
    //     'auto_review_requests', 'auto_review_gigs'
    // ];

    protected $guarded = [];
}
