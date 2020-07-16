<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    protected $table = 'support_categories';
    protected $fillable = [
        'id', 'name', 'icon', 'parent_id', 'slug'
    ];

    public function parent() {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children() {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function articles() {
        return $this->hasMany(Article::class, 'category_id');
    }

    public function seller_articles() {
        return $this->hasMany(Article::class, 'category_id')->where('belongs_to', 'seller');
    }

    public function buyer_articles() {
        return $this->hasMany(Article::class, 'category_id')->where('belongs_to', 'buyer');
    }
}
