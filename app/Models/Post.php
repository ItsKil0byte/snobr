<?php

namespace App\Models;

use App\Models\Scopes\PublishedScope;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory;

    use SoftDeletes;

    /**
     * Атрибуты, что можно массово заполнять.
     *
     * Подробнее - https://laravel.su/docs/12.x/eloquent#mass-assignment
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'content',
        'published',
        'views'
    ];

    /**
     * Получить автора поста.
     *
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Получить категорию поста.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Получить комментарии под постом.
     *
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Получить лайки поста.
     *
     * @return BelongsToMany
     */
    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_user', 'post_id', 'user_id');
    }

    /**
     * Возвращает в запросах только опубликованные посты.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new PublishedScope());
    }
}
