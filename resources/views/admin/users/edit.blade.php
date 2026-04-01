<x-admin.layout>
    <h1 class="text-2xl font-bold mb-4">Edit User</h1>

    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-semibold mb-1">Name</label>
            <input type="text"
                   name="name"
                   value="{{ old('name', $user->name) }}"
                   class="border p-2 w-full">
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Role</label>
            <select name="role" class="border p-2 w-full">
                <option value="user"  @selected($user->role->value === 'user')>User</option>
                <option value="moderator" @selected($user->role->value === 'moderator')>Moderator</option>
                <option value="admin" @selected($user->role->value === 'admin')>Admin</option>
            </select>
        </div>

        <button class="bg-blue-600 text-black px-4 py-2 rounded">
            Save
        </button>
    </form>
</x-admin.layout>