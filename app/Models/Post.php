<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'author', 'slug', 'body'];

    // eager loading by default
    protected $with = ['category', 'author'];

    // relasi dimana satu post hanya boleh di miliki oleh satu user
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }



    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when(
            $filters['search'] ?? false, fn ($query, $search) =>
            $query->where('title', 'like', '%'.$search.'%')
        )->when(
            $filters['category'] ?? false, fn ($query, $category) =>
            $query
            //melakukan query terhadap relasi category
            ->whereHas('category', fn ($query) =>
                $query->where('slug', $category)
            )
        )->when(
            $filters['author'] ?? false, fn ($query, $author) =>
            $query
            //melakukan query terhadap relasi author
            ->whereHas('author', fn ($query) =>
                $query->where('username', $author)
            )
        );
    }
}
