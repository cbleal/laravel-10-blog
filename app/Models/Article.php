<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'img', 'desc', 'views', 'status', 'publish_date', 'category_id'];

    public function Category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
