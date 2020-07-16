<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    protected $table = 'support_articles';
    protected $fillable = [
        'id', 'title', 'description', 'category_id', 'belongs_to', 'slug'
    ];

    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
