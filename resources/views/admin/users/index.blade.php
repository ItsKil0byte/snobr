<x-admin.layout>
    <h1 class="text-2xl font-bold mb-4">Users</h1>

    <table class="w-full border">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 border">ID</th>
                <th class="p-2 border">Name</th>
                <th class="p-2 border">Email</th>
                <th class="p-2 border">Role</th>
                <th class="p-2 border">Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach($users as $user)
                <tr>
                    <td class="p-2 border">{{ $user->id }}</td>
                    <td class="p-2 border">{{ $user->name }}</td>
                    <td class="p-2 border">{{ $user->email }}</td>
                    <td class="p-2 border">{{ $user->role->value }}</td>

                    <td class="p-2 border">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="text-blue-600">Edit</a>

                        <form action="{{ route('admin.users.destroy', $user) }}"
                              method="POST"
                              class="inline-block ml-2"
                              onsubmit="return confirm('Delete user?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</x-admin.layout>