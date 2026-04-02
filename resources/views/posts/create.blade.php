<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Создание поста
            </h2>
            <a href="{{ route('home') }}" class="inline-flex items-center rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                К карте
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($categories->isEmpty())
                        <div class="rounded-md border border-red-300 bg-red-50 p-4 text-red-700">
                            Нет категорий для создания поста.
                        </div>
                    @else
                        <form method="POST" action="{{ route('posts.store') }}" class="space-y-5">
                            @csrf

                            <div>
                                <x-input-label for="title" :value="'Заголовок'" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            <div>
                                <x-input-label for="category_id" :value="'Категория'" />
                                <select id="category_id" name="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Выберите категорию</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected((string) old('category_id') === (string) $category->id)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                            </div>

                            <div>
                                <x-input-label for="image" :value="'URL изображения'" />
                                <x-text-input id="image" name="image" type="text" class="mt-1 block w-full" :value="old('image')" />
                                <x-input-error class="mt-2" :messages="$errors->get('image')" />
                            </div>

                            <div>
                                <x-input-label for="content" :value="'Текст поста'" />
                                <textarea id="content" name="content" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="12" required>{{ old('content') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('content')" />
                            </div>

                            <label class="inline-flex items-center gap-2">
                                <input type="hidden" name="published" value="0">
                                <input type="checkbox" name="published" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" @checked(old('published', '1') === '1')>
                                <span class="text-sm text-gray-700">Опубликовать сразу</span>
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('published')" />

                            <div class="flex flex-wrap items-center gap-3">
                                <x-primary-button>
                                    Создать пост
                                </x-primary-button>
                                <a href="{{ route('home') }}" class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Отмена
                                </a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
