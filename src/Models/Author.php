<?php

namespace Stephenjude\FilamentBlog\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Chiiya\FilamentAccessControl\Enumerators\RoleName;

class Author extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'filament_users';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'expires_at',
        'two_factor_code',
        'two_factor_expires_at',
    ];

    /**
     * Check whether the user is a super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole(RoleName::SUPER_ADMIN);
    }

    /**
     * Provides full name of the current author.
     */
    public function getFullNameAttribute(): string
    {
        if (! $this->first_name && ! $this->last_name) {
            return '—';
        }

        $name = $this->first_name ?? '';

        if ($this->last_name) {
            $name .= ' '.$this->last_name;
        }

        return $name;
    }

    public function blogs(): BelongsToMany
    {
        return $this->belongsToMany(Blog::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'filament_user_id');
    }
}
