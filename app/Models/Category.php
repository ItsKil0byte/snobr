<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /*
     * Получить все посты категории
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
