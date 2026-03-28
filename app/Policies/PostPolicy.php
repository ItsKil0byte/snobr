<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Post $post): bool
    {
        if($post->published){
            return true;
        }

        if(!$user){
            return false;
        }

        return $user->id === $post->user_id
            || $user->role === Role::ADMIN
            || $user->role === Role::MODERATOR;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user !== null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        if(!$user){
            return false;
        }

        if($user->role === Role::ADMIN || $user->role === Role::MODERATOR){
            return true;
        }

        return $user->id === $post->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        if(!$user){
            return false;
        }

        if($user->role === Role::ADMIN || $user->role === Role::MODERATOR){
            return true;
        }

        return $user->id === $post->user_id;
    }
    
    /**
     * Может ли пользователь поставить лайк
     */
    public function like(User $user, Post $post): bool
    {
        if(!$user){
            return false;
        }

        if(!$post->published && $user->id !== $post->user_id){
            return false;
        }

        return true;
    }
}
