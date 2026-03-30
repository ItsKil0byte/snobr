<x-admin.layout>
    <h1 class="text-2xl font-bold mb-4">Website settings</h1>

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf

        @foreach($settings as $key => $value)
            <div class="mb-4">
                <label class="block font-semibold">{{ $key }}</label>
                <input type="text" name="{{ $key }}" value="{{ $value }}" class="border p-2 w-full">
            </div>
        @endforeach

        <button class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
    </form>
</x-admin.layout>