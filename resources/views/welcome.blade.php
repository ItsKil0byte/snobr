
@php
    $posts = [
        [
            'id' => 1,
            'author_photo' => 'https://placehold.co/60x60?text=1',
            'author_name' => 'Имя автора 1',
            'published_at' => 'Время 1',
            'views' => '111',
            'title' => 'Название 1',
            'description' => 'Описание 1',
            'image' => 'https://placehold.co/600x300?text=1',
            'content' => 'Текст статьи 1',
        ],
        [
            'id' => 2,
            'author_photo' => 'https://placehold.co/60x60?text=2',
            'author_name' => 'Имя автора 2',
            'published_at' => 'Время 2',
            'views' => '222',
            'title' => 'Название 2',
            'description' => 'Описание 2',
            'image' => 'https://placehold.co/600x300?text=2',
            'content' => 'Текст статьи 2',
        ],
        [
            'id' => 3,
            'author_photo' => 'https://placehold.co/60x60?text=3',
            'author_name' => 'Имя автора 3',
            'published_at' => 'Время 3',
            'views' => '333',
            'title' => 'Название 3',
            'description' => 'Описание 3',
            'image' => 'https://placehold.co/600x300?text=3',
            'content' => 'Текст статьи 3',
        ],
    ];

    $start = rand(0, count($posts) - 1);
@endphp

    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SNOBR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data='page(@json($posts), {{ $start }})' class="index-page">
<header class="topbar">
    <div class="logo">SNOBR</div>

    <div class="topbar-actions">
        <button type="button" class="topbar-button" @click="showMap = true">Карта</button>

        @if (Route::has('login'))
            <a href="{{ route('login') }}" class="topbar-link">Вход</a>
        @endif
    </div>
</header>

<main class="page">
    <div class="nav-row">
        <button type="button" class="nav-button" @click="move('upLeft')" x-text="label('upLeft')"></button>
        <button type="button" class="nav-button" @click="move('up')" x-text="label('up')"></button>
        <button type="button" class="nav-button" @click="move('upRight')" x-text="label('upRight')"></button>
    </div>

    <div class="content-row">
        <button type="button" class="nav-button side-button" @click="move('left')" x-text="label('left')"></button>

        <article class="post-card">
            <div class="post-meta">
                <div class="author-block">
                    <img :src="post.author_photo" alt="Фото автора" class="author-photo">

                    <div>
                        <div class="meta-label">Имя автора</div>
                        <div x-text="post.author_name"></div>
                    </div>
                </div>

                <div class="post-extra">
                    <div>
                        <div class="meta-label">Время</div>
                        <div x-text="post.published_at"></div>
                    </div>

                    <div>
                        <div class="meta-label">Просмотры</div>
                        <div x-text="post.views"></div>
                    </div>
                </div>
            </div>

            <div class="post-body">
                <div class="meta-label">Название</div>
                <h1 class="post-title" x-text="post.title"></h1>

                <div class="meta-label">Описание</div>
                <p class="post-description" x-text="post.description"></p>

                <div class="meta-label">Картинка</div>
                <img :src="post.image" alt="Картинка" class="post-image">

                <button type="button" class="post-button" @click="showPost = true">Просмотреть пост</button>
            </div>
        </article>

        <button type="button" class="nav-button side-button" @click="move('right')" x-text="label('right')"></button>
    </div>

    <div class="nav-row">
        <button type="button" class="nav-button" @click="move('downLeft')" x-text="label('downLeft')"></button>
        <button type="button" class="nav-button" @click="move('down')" x-text="label('down')"></button>
        <button type="button" class="nav-button" @click="move('downRight')" x-text="label('downRight')"></button>
    </div>
</main>

<template x-if="showPost">
    <div class="modal-wrap">
        <div class="modal-box">
            <div class="meta-label">Полный текст</div>
            <h2 class="modal-title" x-text="post.title"></h2>
            <p x-text="post.content"></p>
            <button type="button" class="post-button" @click="showPost = false">Закрыть</button>
        </div>
    </div>
</template>

<template x-if="showMap">
    <div class="modal-wrap">
        <div class="modal-box">
            <div class="meta-label">Карта</div>
            <p>Карта пути</p>
            <button type="button" class="post-button" @click="showMap = false">Закрыть</button>
        </div>
    </div>
</template>

<script>
    function page(posts, start) {
        return {
            posts: posts,
            i: start,
            showPost: false,
            showMap: false,
            history: [],
            names: {
                upLeft: '↖',
                up: '↑',
                upRight: '↗',
                left: '←',
                right: '→',
                downLeft: '↙',
                down: '↓',
                downRight: '↘',
            },
            backMap: {
                upLeft: 'downRight',
                up: 'down',
                upRight: 'downLeft',
                left: 'right',
                right: 'left',
                downLeft: 'upRight',
                down: 'up',
                downRight: 'upLeft',
            },
            get post() {
                return this.posts[this.i];
            },
            get backDir() {
                if (!this.history.length) {
                    return null;
                }

                return this.history[this.history.length - 1].back;
            },
            label(dir) {
                if (this.backDir === dir) {
                    return 'Вернуться';
                }

                return this.names[dir];
            },
            move(dir) {
                if (this.backDir === dir) {
                    const last = this.history.pop();
                    this.i = last.index;
                    this.showPost = false;
                    return;
                }

                const next = this.rand();

                this.history.push({
                    index: this.i,
                    back: this.backMap[dir],
                });

                this.i = next;
                this.showPost = false;
            },
            rand() {
                if (this.posts.length < 2) {
                    return this.i;
                }

                let next = this.i;

                while (next === this.i) {
                    next = Math.floor(Math.random() * this.posts.length);
                }

                return next;
            },
        };
    }
</script>
</body>
</html>
