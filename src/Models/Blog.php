<?php

namespace Stephenjude\FilamentBlog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Tags\HasTags;

class Blog extends Model
{
    use HasFactory, HasTags, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'blogs';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function filamentUsers(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'blog_filament_user', 'blog_id', 'filament_user_id');
    }

    public function authors(): BelongsToMany
    {
        //dd($this->filamentUsers()->getQuery()->get());
        return $this->filamentUsers();
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
