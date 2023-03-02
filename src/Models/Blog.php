<?php

namespace Stephenjude\FilamentBlog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Tags\HasTags;

class Blog extends Model
{
    use HasFactory;
    use HasTags;

    /**
     * @var string
     */
    protected $table = 'blogs';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
    ];

    public function authors(): HasMany
    {
        return $this->HasMany(Author::class);
    }

    public function categories(): HasMany
    {
        return $this->HasMany(Category::class);
    }

    public function posts(): HasMany
    {
        return $this->HasMany(Post::class);
    }
}
