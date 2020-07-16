<?php

namespace App\Model\Gig;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GigRequirement extends Model
{

    use SoftDeletes;
    
    // protected $fillable = [
    //     'gig_id', 'title', 'description', 'file_name', 'is_required'
    // ];
    protected $guarded = [];
}
