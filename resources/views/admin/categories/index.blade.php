<x-admin.layout>
    <h1 class="text-2xl font-bold mb-4">Categories</h1>

    <a href="{{ route('admin.categories.create') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">
        Add category
    </a>

    <table class="w-full border">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 border">ID</th>
                <th class="p-2 border">Name</th>
                <th class="p-2 border">Slug</th>
                <th class="p-2 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
                <tr>
                    <td class="p-2 border">{{ $category->id }}</td>
                    <td class="p-2 border">{{ $category->name }}</td>
                    <td class="p-2 border">{{ $category->slug }}</td>
                    <td class="p-2 border">
                        <a href="{{ route('admin.categories.edit', $category) }}"
                           class="text-blue-600">Edit</a>

                        <form action="{{ route('admin.categories.destroy', $category) }}"
                              method="POST" class="inline-block"
                              onsubmit="return confirm('Delete category?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 ml-2">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $categories->links() }}
</x-admin.layout>