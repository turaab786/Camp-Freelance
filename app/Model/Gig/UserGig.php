<?php

namespace App\Model\Gig;

use App\Model\MainModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserGig extends MainModel
{

    use SoftDeletes;
    
    // protected $fillable = [
    //     'user_id', 'category_id', 'subcategory_id', 'service_type_id', 'title', 'slug', 'description', 'hourly_rate', 'tags', 'status', 'gig_type'
    // ];
    
    protected $guarded = [];

    // protected $casts = [
    //     'tags' => 'array'
    // ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function category()
    {
        return $this->hasOne('App\Model\Gig\GigCategory', 'id', 'category_id');
    }

    public function subcategory()
    {
        return $this->hasOne('App\Model\Gig\GigCategory', 'id', 'subcategory_id');
    }

    public function servicetype()
    {
        return $this->hasOne('App\Model\Gig\GigServiceType', 'id', 'service_type_id');
    }

    public function gallery()
    {
        return $this->hasMany('App\Model\Gig\GigGallery', 'gig_id', 'id');
    }

    public function packages()
    {
        return $this->hasMany('App\Model\Gig\GigPackage', 'gig_id', 'id');
    }

    public function requirements()
    {
        return $this->hasMany('App\Model\Gig\GigRequirement', 'gig_id', 'id');
    }

    public function analytics()
    {
        return $this->hasMany('App\Model\Gig\GigAnalytics', 'gig_id', 'id');
    }
}
