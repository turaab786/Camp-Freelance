<?php

namespace App\Model\Gig;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GigAnalytics extends Model
{
    use SoftDeletes;
    
    // protected $fillable = [
    //     'gig_id', 'type'
    // ];
    protected $guarded = [];
}
