<x-app-layout>
    <div class="flex">
        <aside class="w-64 bg-gray-100 min-h-screen p-4">
            <nav class="space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block">Main page</a>
                <a href="{{ route('admin.users.index') }}" class="block">Users</a>
                <a href="{{ route('admin.categories.index') }}" class="block">Categories</a>
                <a href="{{ route('admin.settings.edit') }}" class="block">Settings</a>
            </nav>
        </aside>

        <main class="flex-1 p-6">
            {{ $slot }}
        </main>
    </div>
</x-app-layout>