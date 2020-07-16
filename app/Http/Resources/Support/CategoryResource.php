<?php

namespace App\Http\Resources\Support;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'icon' => $this->icon,
            'parent_id' => $this->parent_id,
            'slug' => $this->slug,
            'articles_count' => ($request->segment(4) == 'seller') ? $this->seller_articles_count : $this->buyer_articles_count,
            'articles' => ($request->segment(4) == 'seller') ? ArticleResource::collection($this->seller_articles) : ArticleResource::collection($this->buyer_articles),
        ];
    }
}
