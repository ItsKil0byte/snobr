<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index', [
            'users' => User::paginate(20)
        ]);
    }

    public function edit(User $user)
    {
        $this->authorize('updateRole', $user);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('updateRole', $user);
        $request->validate([
            'name' => 'required',
            'role' => 'required|in:user,moderator,admin',
        ]);

        $user->name = $request->name;
        $user->role = Role::from($request->role);
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User updated');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        
        $user->posts()->delete();
        $user->comments()->delete();
        $user->delete();

        return back()->with('success', 'User deleted');
    }
}