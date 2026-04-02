<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SNOBR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
    x-data='page({
        posts: @json($posts ?? []),
        start: {{ $start ?? 0 }},
        isAuthenticated: @json(auth()->check()),
        loginUrl: @json(route('login')),
        currentUserName: @json(auth()->user()?->name),
        oldCommentPostId: @json(old('post_id')),
        oldCommentContent: @json(old('content')),
        oldCommentError: @json($errors->first('content')),
    })'
    x-init="init()"
    class="index-page"
>
<header class="topbar">
    <div class="logo">SNOBR</div>

    <div class="topbar-actions">
        <a href="{{ route('home') }}" class="topbar-link">Главная</a>
        <button type="button" class="topbar-button" @click="showMap = true">Карта</button>

        @auth
            <a href="{{ route('posts.create') }}" class="topbar-link">Создать пост</a>
            <a href="{{ route('profile.edit') }}" class="topbar-link">Профиль</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="topbar-button">Выход</button>
            </form>
        @else
            @if (Route::has('login'))
                <a href="{{ route('login') }}" class="topbar-link">Вход</a>
            @endif
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="topbar-link">Регистрация</a>
            @endif
        @endauth
    </div>
</header>

<main class="page">
    @if (session('success'))
        <div class="flash-box">{{ session('success') }}</div>
    @endif

    @if (($postCount ?? 0) === 0)
        <article class="post-card">
            <p>Посты не найдены. Нужны данные в базе.</p>
        </article>
    @else
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
                            <img :src="post.author_photo" alt="author" class="author-photo">

                            <div class="author-info">
                                <div class="author-name" x-text="post.author_name"></div>
                                <div class="meta-line" x-text="post.published_at"></div>
                            </div>
                        </div>

                        <div class="post-category" x-text="post.category_name"></div>
                    </div>

                    <div class="post-body">
                        <h1 class="post-title" x-text="post.title"></h1>
                        <p class="post-description" x-text="post.excerpt"></p>
                        <img :src="post.image" alt="image" class="post-image">

                        <div class="post-stats">
                            <span x-text="'Просмотры: ' + post.views"></span>
                            <span x-text="'Лайки: ' + post.likes_count"></span>
                            <span x-text="'Комментарии: ' + post.comments_count"></span>
                        </div>

                        <div class="post-actions">
                            <button type="button" class="post-button" @click="showPost = true">Просмотреть пост</button>
                            <button type="button" class="post-button" @click="toggleLike" :disabled="likePending" x-text="post.liked ? 'Убрать лайк' : 'Поставить лайк'"></button>
                        </div>
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
    @endif
</main>

@if (($postCount ?? 0) > 0)
    <template x-if="showPost">
        <div class="modal-wrap">
            <div class="modal-box">
                <div class="meta-line">
                    <span x-text="post.author_name"></span>
                    <span>•</span>
                    <span x-text="post.published_at"></span>
                </div>
                <h2 class="modal-title" x-text="post.title"></h2>
                <img :src="post.image" alt="image" class="modal-image">
                <div class="modal-article" x-html="formatArticle(post.content)"></div>

                <div class="meta-label comments-label">Комментарии</div>

                <template x-if="!post.comments.length">
                    <p>Пока нет комментариев.</p>
                </template>

                <div class="comments-list">
                    <template x-for="comment in post.comments" :key="comment.id">
                        <div class="comment-item">
                            <div class="comment-head">
                                <span x-text="comment.author_name"></span>
                                <span x-text="comment.created_at"></span>
                            </div>
                            <div x-text="comment.content"></div>
                        </div>
                    </template>
                </div>

                @auth
                    <form method="POST" :action="post.comment_url" class="comment-form" @submit.prevent="submitComment">
                        @csrf
                        <input type="hidden" name="post_id" :value="post.id">
                        <textarea name="content" x-model="commentDrafts[post.id]" @input="commentError = ''"></textarea>

                        <div class="comment-error" x-show="commentError" x-text="commentError"></div>

                        <button type="submit" class="post-button" :disabled="commentPending" x-text="commentPending ? 'Отправка...' : 'Отправить комментарий'"></button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="topbar-link">Войдите, чтобы комментировать</a>
                @endauth

                <button type="button" class="post-button" @click="showPost = false">Закрыть</button>
            </div>
        </div>
    </template>

    <div x-show="showMap" class="modal-wrap" style="display: none;">
        <div class="modal-box map-modal-box">
            <div class="map-grid" style="display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:10px;">
                <template x-for="cellIndex in mapCellCount()" :key="cellIndex">
                    <div>
                        <template x-if="mapCell(cellIndex - 1)">
                            <button
                                type="button"
                                class="map-node"
                                :class="mapNodeClass(mapCell(cellIndex - 1).id)"
                                :disabled="!canOpenFromMap(mapCell(cellIndex - 1).id)"
                                @click="openFromMap(mapCell(cellIndex - 1).id)"
                            >
                                <span x-text="mapNodeIcon(mapCell(cellIndex - 1).id)"></span>
                                <small x-text="mapNodeLabel(mapCell(cellIndex - 1).id)"></small>
                            </button>
                        </template>

                        <template x-if="!mapCell(cellIndex - 1)">
                            <div class="map-node map-empty">
                                <span>.</span>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            <button type="button" class="post-button map-close" @click="showMap = false">
                Закрыть
            </button>
        </div>
    </div>
@endif

<script>
    function page(payload) {
        return {
            posts: payload.posts ?? [],
            i: payload.start ?? 0,
            isAuthenticated: payload.isAuthenticated ?? false,
            loginUrl: payload.loginUrl ?? '/',
            oldCommentPostId: payload.oldCommentPostId,
            oldCommentContent: payload.oldCommentContent ?? '',
            oldCommentError: payload.oldCommentError ?? '',
            currentUserName: payload.currentUserName ?? 'Вы',
            showPost: Boolean(payload.oldCommentPostId),
            showMap: false,
            history: [],
            mapSize: 5,
            likePending: false,
            commentPending: false,
            commentError: '',
            commentDrafts: {},
            names: {
                upLeft: '\u2196',
                up: '\u2191',
                upRight: '\u2197',
                left: '\u2190',
                right: '\u2192',
                downLeft: '\u2199',
                down: '\u2193',
                downRight: '\u2198',
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
            init() {
                if (this.oldCommentPostId) {
                    this.commentDrafts[this.oldCommentPostId] = this.oldCommentContent;

                    if (this.post && String(this.post.id) === String(this.oldCommentPostId) && this.oldCommentError) {
                        this.commentError = this.oldCommentError;
                    }
                }
            },
            get post() {
                return this.posts[this.i] ?? null;
            },
            move(dir) {
                if (!this.post) {
                    return;
                }

                if (this.backDir === dir) {
                    const last = this.history.pop();

                    if (!last) {
                        return;
                    }

                    this.i = last.index;
                    this.showPost = false;
                    return;
                }

                const nextId = this.post.links[dir];

                if (!nextId) {
                    return;
                }

                const nextIndex = this.posts.findIndex(post => post.id === nextId);

                if (nextIndex === -1 || nextIndex === this.i) {
                    return;
                }

                this.history.push({
                    index: this.i,
                    back: this.backMap[dir],
                });

                this.i = nextIndex;
                this.showPost = false;
                this.commentError = '';
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
                const ids = this.history.map(item => this.posts[item.index]?.id).filter(Boolean);

                if (this.post && !ids.includes(this.post.id)) {
                    ids.push(this.post.id);
                }

                return ids;
            },
            mapCell(index) {
                return this.posts[index] ?? null;
            },
            mapCellCount() {
                return this.mapSize * this.mapSize;
            },
            canOpenFromMap(id) {
                return this.post && (this.post.id === id || this.isVisited(id));
            },
            mapNodeClass(id) {
                if (this.post && this.post.id === id) {
                    return 'current';
                }

                if (this.isVisited(id)) {
                    return 'visited';
                }

                return 'unknown';
            },
            mapNodeIcon(id) {
                if (this.post && this.post.id === id) {
                    return '\u{1F441}';
                }

                if (this.isVisited(id)) {
                    return '\u2714';
                }

                return '?';
            },
            mapNodeLabel(id) {
                if (this.post && this.post.id === id) {
                    return 'текущий';
                }

                if (this.isVisited(id)) {
                    return 'прочитано';
                }

                return 'не прочитано';
            },
            openFromMap(id) {
                if (!this.canOpenFromMap(id)) {
                    return;
                }

                const nextIndex = this.posts.findIndex(post => post.id === id);

                if (nextIndex === -1) {
                    return;
                }

                this.i = nextIndex;
                this.showMap = false;
                this.showPost = false;
                this.commentError = '';
            },
            toggleLike() {
                if (!this.post || !this.post.like_url) {
                    return;
                }

                if (!this.isAuthenticated) {
                    window.location.href = this.loginUrl;
                    return;
                }

                if (this.likePending) {
                    return;
                }

                this.likePending = true;
                const prevLiked = Boolean(this.post.liked);
                const prevCount = Number(this.post.likes_count) || 0;
                this.post.liked = !prevLiked;
                this.post.likes_count = Math.max(0, prevCount + (this.post.liked ? 1 : -1));

                window.axios.post(this.post.like_url)
                    .then((response) => {
                        const data = response?.data ?? {};

                        if (typeof data.liked === 'boolean') {
                            this.post.liked = data.liked;
                        }

                        if (typeof data.likesCount === 'number') {
                            this.post.likes_count = data.likesCount;
                        }
                    })
                    .catch((error) => {
                        this.post.liked = prevLiked;
                        this.post.likes_count = prevCount;

                        if (error?.response?.status === 401) {
                            window.location.href = this.loginUrl;
                        }
                    })
                    .finally(() => {
                        this.likePending = false;
                    });
            },
            submitComment() {
                if (!this.post || !this.post.comment_url) {
                    return;
                }

                if (!this.isAuthenticated) {
                    window.location.href = this.loginUrl;
                    return;
                }

                if (this.commentPending) {
                    return;
                }

                const postId = this.post.id;
                const content = String(this.commentDrafts[postId] ?? '').trim();

                if (!content.length) {
                    this.commentError = 'Введите комментарий';
                    return;
                }

                this.commentPending = true;
                this.commentError = '';

                window.axios.post(
                    this.post.comment_url,
                    {
                        content,
                        post_id: postId,
                    },
                    {
                        headers: {
                            Accept: 'application/json',
                        },
                    }
                ).then((response) => {
                    const redirectedUrl = response?.request?.responseURL ?? '';

                    if (redirectedUrl.includes('/login')) {
                        window.location.href = this.loginUrl;
                        return;
                    }

                    this.post.comments.unshift({
                        id: `new-${Date.now()}`,
                        author_name: this.currentUserName,
                        created_at: this.formatDate(new Date()),
                        content,
                    });
                    this.post.comments_count = Number(this.post.comments_count || 0) + 1;
                    this.commentDrafts[postId] = '';
                }).catch((error) => {
                    if (error?.response?.status === 401) {
                        window.location.href = this.loginUrl;
                        return;
                    }

                    if (error?.response?.status === 422) {
                        this.commentError = error?.response?.data?.errors?.content?.[0] ?? 'Ошибка валидации комментария';
                        return;
                    }

                    this.commentError = 'Не удалось отправить комментарий';
                }).finally(() => {
                    this.commentPending = false;
                });
            },
            formatArticle(content) {
                const text = String(content ?? '').trim();

                if (!text.length) {
                    return '';
                }

                return text
                    .split(/\n{2,}/)
                    .map((part) => `<p>${this.escapeHtml(part.trim()).replace(/\n/g, '<br>')}</p>`)
                    .join('');
            },
            escapeHtml(value) {
                return value
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;');
            },
            formatDate(date) {
                const pad = (value) => String(value).padStart(2, '0');

                return `${pad(date.getDate())}.${pad(date.getMonth() + 1)}.${date.getFullYear()} ${pad(date.getHours())}:${pad(date.getMinutes())}`;
            },
        };
    }
</script>
</body>
</html>
