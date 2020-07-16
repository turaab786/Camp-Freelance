<?php

namespace App\Model\Gig;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GigPackage extends Model
{
    use SoftDeletes;
    
    // protected $fillable = [
    //     'gig_id', 'title', 'description', 'price', 'time', 'sort_order', 'is_visible'
    // ];
    protected $guarded = [];
}
