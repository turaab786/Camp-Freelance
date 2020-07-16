<?php

namespace App\Model\Gig;

use App\Model\MainModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class GigCategory extends MainModel
{

    use SoftDeletes;

    // protected $fillable = [
    //     'parent_id', 'title', 'description', 'sort_order', 'is_visible', 'is_parent'
    // ];
    protected $guarded = [];

    public function subcategories()
    {
        return $this->hasMany('App\Model\Gig\GigCategory', 'parent_id', 'id');
    }
}
