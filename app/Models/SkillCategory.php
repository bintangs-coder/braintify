<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class SkillCategory extends Model
{
    use HasFactory;

    protected $table = 'skill_categories';

    protected $fillable = [
        'name', 'slug', 'icon', 'description', 'parent_id', 'sort_order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(fn($cat) => $cat->slug = $cat->slug ?? Str::slug($cat->name));
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(SkillCategory::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(SkillCategory::class, 'parent_id')->orderBy('sort_order');
    }

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class, 'category_id');
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeRoot($q)
    {
        return $q->whereNull('parent_id');
    }
}
