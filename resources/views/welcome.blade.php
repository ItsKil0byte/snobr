
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
            'links' => [
                'upLeft' => 2,
                'up' => 3,
                'upRight' => 4,
                'left' => 5,
                'right' => 2,
                'downLeft' => 3,
                'down' => 4,
                'downRight' => 5,
            ],
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
            'links' => [
                'upLeft' => 3,
                'up' => 4,
                'upRight' => 5,
                'left' => 1,
                'right' => 3,
                'downLeft' => 4,
                'down' => 5,
                'downRight' => 1,
            ],
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
            'links' => [
                'upLeft' => 4,
                'up' => 5,
                'upRight' => 1,
                'left' => 2,
                'right' => 4,
                'downLeft' => 5,
                'down' => 1,
                'downRight' => 2,
            ],
        ],
        [
            'id' => 4,
            'author_photo' => 'https://placehold.co/60x60?text=4',
            'author_name' => 'Имя автора 4',
            'published_at' => 'Время 4',
            'views' => '444',
            'title' => 'Название 4',
            'description' => 'Описание 4',
            'image' => 'https://placehold.co/600x300?text=4',
            'content' => 'Текст статьи 4',
            'links' => [
                'upLeft' => 5,
                'up' => 1,
                'upRight' => 2,
                'left' => 3,
                'right' => 5,
                'downLeft' => 1,
                'down' => 2,
                'downRight' => 3,
            ],
        ],
        [
            'id' => 5,
            'author_photo' => 'https://placehold.co/60x60?text=5',
            'author_name' => 'Имя автора 5',
            'published_at' => 'Время 5',
            'views' => '555',
            'title' => 'Название 5',
            'description' => 'Описание 5',
            'image' => 'https://placehold.co/600x300?text=5',
            'content' => 'Текст статьи 5',
            'links' => [
                'upLeft' => 1,
                'up' => 2,
                'upRight' => 3,
                'left' => 4,
                'right' => 1,
                'downLeft' => 2,
                'down' => 3,
                'downRight' => 4,
            ],
        ],
    ];

    $start = 0;
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
        <div class="side-block">
            <button type="button" class="nav-button" @click="move('left')" x-text="label('left')"></button>
        </div>

        <div class="center-block">
            <article class="post-card">
                <div class="post-meta">
                    <div class="author-block">
                        <img :src="post.author_photo" alt="Фото автора" class="author-photo">

                        <div class="author-info">
                            <div class="meta-label">Имя автора</div>
                            <div x-text="post.author_name"></div>
                        </div>
                    </div>

                    <div class="post-extra">
                        <div>
                            <div class="meta-label">Время</div>
                            <div x-text="post.published_at"></div>
                        </div>

                        <div style="margin-top: 12px;">
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
        </div>

        <div class="side-block">
            <button type="button" class="nav-button" @click="move('right')" x-text="label('right')"></button>
        </div>
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

<div x-show="showMap" class="modal-wrap" style="display: none;">
    <div class="modal-box map-modal-box">
        <div class="map-grid">
            <button
                type="button"
                class="map-node"
                :class="posts[0].id === post.id ? 'current' : (isVisited(posts[0].id) ? 'visited' : 'unknown')"
                :disabled="!isVisited(posts[0].id) && posts[0].id !== post.id"
                @click="openFromMap(posts[0].id)"
            >
                <span x-text="posts[0].id === post.id ? '👁' : (isVisited(posts[0].id) ? '✔' : '?')"></span>
                <small x-text="posts[0].id === post.id ? 'текущий' : (isVisited(posts[0].id) ? 'прочитано' : 'не прочитано')"></small>
            </button>

            <button
                type="button"
                class="map-node"
                :class="posts[1].id === post.id ? 'current' : (isVisited(posts[1].id) ? 'visited' : 'unknown')"
                :disabled="!isVisited(posts[1].id) && posts[1].id !== post.id"
                @click="openFromMap(posts[1].id)"
            >
                <span x-text="posts[1].id === post.id ? '👁' : (isVisited(posts[1].id) ? '✔' : '?')"></span>
                <small x-text="posts[1].id === post.id ? 'текущий' : (isVisited(posts[1].id) ? 'прочитано' : 'не прочитано')"></small>
            </button>

            <button
                type="button"
                class="map-node"
                :class="posts[2].id === post.id ? 'current' : (isVisited(posts[2].id) ? 'visited' : 'unknown')"
                :disabled="!isVisited(posts[2].id) && posts[2].id !== post.id"
                @click="openFromMap(posts[2].id)"
            >
                <span x-text="posts[2].id === post.id ? '👁' : (isVisited(posts[2].id) ? '✔' : '?')"></span>
                <small x-text="posts[2].id === post.id ? 'текущий' : (isVisited(posts[2].id) ? 'прочитано' : 'не прочитано')"></small>
            </button>

            <button
                type="button"
                class="map-node"
                :class="posts[3].id === post.id ? 'current' : (isVisited(posts[3].id) ? 'visited' : 'unknown')"
                :disabled="!isVisited(posts[3].id) && posts[3].id !== post.id"
                @click="openFromMap(posts[3].id)"
            >
                <span x-text="posts[3].id === post.id ? '👁' : (isVisited(posts[3].id) ? '✔' : '?')"></span>
                <small x-text="posts[3].id === post.id ? 'текущий' : (isVisited(posts[3].id) ? 'прочитано' : 'не прочитано')"></small>
            </button>

            <button
                type="button"
                class="map-node"
                :class="posts[4].id === post.id ? 'current' : (isVisited(posts[4].id) ? 'visited' : 'unknown')"
                :disabled="!isVisited(posts[4].id) && posts[4].id !== post.id"
                @click="openFromMap(posts[4].id)"
            >
                <span x-text="posts[4].id === post.id ? '👁' : (isVisited(posts[4].id) ? '✔' : '?')"></span>
                <small x-text="posts[4].id === post.id ? 'текущий' : (isVisited(posts[4].id) ? 'прочитано' : 'не прочитано')"></small>
            </button>
        </div>

        <button type="button" class="post-button map-close" @click="showMap = false">
            Закрыть
        </button>
    </div>
</div>

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
            move(dir) {
                if (this.backDir === dir) {
                    const last = this.history.pop();
                    this.i = last.index;
                    this.showPost = false;
                    return;
                }

                const nextId = this.post.links[dir];
                const nextIndex = this.posts.findIndex(post => post.id === nextId);

                if (nextIndex === -1) {
                    return;
                }

                this.history.push({
                    index: this.i,
                    back: this.backMap[dir],
                });

                this.i = nextIndex;
                this.showPost = false;
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
            isVisited(id) {
                return this.visitedIds().includes(id);
            },
            visitedIds() {
                const ids = this.history.map(item => this.posts[item.index].id);

                if (!ids.includes(this.post.id)) {
                    ids.push(this.post.id);
                }

                return ids;
            },
            openFromMap(id) {
                const nextIndex = this.posts.findIndex(post => post.id === id);

                if (nextIndex === -1) {
                    return;
                }

                this.i = nextIndex;
                this.showMap = false;
                this.showPost = false;
            },

        };
    }
</script>
</body>
</html>
