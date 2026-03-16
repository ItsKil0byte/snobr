<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{

    /**
     * Атрибуты, что можно массово заполнять.
     *
     * Подробнее - https://laravel.su/docs/12.x/eloquent#mass-assignment
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'slug'];

    /**
     * Получить все посты категории
     *
     * @return HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
