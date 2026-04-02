<x-admin.layout>
    <h1 class="text-2xl font-bold mb-4">Create Category</h1>

    <form method="POST" action="{{ route('admin.categories.store') }}">
        @csrf

        <label class="block mb-2">Name</label>
        <input type="text" name="name" class="border p-2 w-full mb-4">

        <button class="bg-blue-600 text-black px-4 py-2 rounded">Create</button>
    </form>
</x-admin.layout>