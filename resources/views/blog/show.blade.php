@extends('layouts.blog')

@section('title', $post->title . ' - Blog - ' . setting('site_name', 'MrShort'))

@push('head')
<meta name="description" content="{{ $post->excerpt ?: Str::limit(strip_tags($post->content), 160) }}">
@if($post->meta_tags)
    @foreach($post->meta_tags as $name => $content)
    <meta name="{{ $name }}" content="{{ $content }}">
    @endforeach
@endif
<meta property="og:title" content="{{ $post->title }}">
<meta property="og:description" content="{{ $post->excerpt ?: Str::limit(strip_tags($post->content), 160) }}">
@if($post->featured_image)
<meta property="og:image" content="{{ Storage::url($post->featured_image) }}">
@endif
@endpush

@section('content')
<div class="min-h-screen bg-[#050505] relative overflow-hidden">
    <!-- Background Grid -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px]"></div>
    
    <!-- Spotlight Effects -->
    <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-electric-blue/5 rounded-full blur-[200px]"></div>
    <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-bright-magenta/5 rounded-full blur-[150px]"></div>

    <div class="relative z-10 py-16 sm:py-24">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="mb-8">
                <ol class="flex items-center gap-2 text-sm text-gray-400">
                    <li><a href="{{ url('/') }}" class="hover:text-electric-blue transition-colors">Home</a></li>
                    <li><span class="text-gray-600">/</span></li>
                    <li><a href="{{ route('blog.index') }}" class="hover:text-electric-blue transition-colors">Blog</a></li>
                    @if($post->category)
                    <li><span class="text-gray-600">/</span></li>
                    <li><a href="{{ route('blog.category', $post->category->slug) }}" class="hover:text-electric-blue transition-colors">{{ $post->category->name }}</a></li>
                    @endif
                </ol>
            </nav>

            <!-- Article Header -->
            <header class="mb-10">
                @if($post->category)
                <a href="{{ route('blog.category', $post->category->slug) }}" class="inline-block px-4 py-1.5 text-sm font-semibold text-electric-blue bg-electric-blue/10 rounded-full mb-6 hover:bg-electric-blue/20 transition-colors">
                    {{ $post->category->name }}
                </a>
                @endif
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-8 tracking-tight leading-tight">{{ $post->title }}</h1>
                
                <div class="flex flex-wrap items-center gap-6 text-gray-400">
                    @if($post->author)
                    <div class="flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($post->author->name) }}&size=48&background=0D8ABC&color=fff" alt="{{ $post->author->name }}" class="w-12 h-12 rounded-full ring-2 ring-white/10">
                        <div>
                            <span class="block text-white font-medium">{{ $post->author->name }}</span>
                            <span class="text-sm text-gray-500">Author</span>
                        </div>
                    </div>
                    @endif
                    <div class="flex items-center gap-2 bg-white/5 px-4 py-2 rounded-full">
                        <span class="material-symbols-outlined text-sm text-electric-blue">calendar_today</span>
                        <span>{{ $post->published_at->format('F d, Y') }}</span>
                    </div>
                    <div class="flex items-center gap-2 bg-white/5 px-4 py-2 rounded-full">
                        <span class="material-symbols-outlined text-sm text-bright-magenta">schedule</span>
                        <span>{{ $post->reading_time }} min read</span>
                    </div>
                    <div class="flex items-center gap-2 bg-white/5 px-4 py-2 rounded-full">
                        <span class="material-symbols-outlined text-sm text-electric-blue">visibility</span>
                        <span>{{ number_format($post->views) }} views</span>
                    </div>
                </div>
            </header>

            <!-- Featured Image -->
            @if($post->featured_image)
            <div class="mb-12 rounded-2xl overflow-hidden ring-1 ring-white/10">
                <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-auto">
            </div>
            @endif

            <!-- Article Content -->
            <div class="bg-gray-900/80 border border-white/10 rounded-2xl p-8 md:p-12 mb-12 backdrop-blur-sm">
                <article class="prose prose-lg prose-invert max-w-none
                    prose-headings:text-white prose-headings:font-bold 
                    prose-p:text-gray-200 prose-p:leading-relaxed
                    prose-a:text-electric-blue prose-a:no-underline hover:prose-a:underline
                    prose-strong:text-white
                    prose-code:text-electric-blue prose-code:bg-white/10 prose-code:px-2 prose-code:py-0.5 prose-code:rounded
                    prose-pre:bg-gray-950 prose-pre:border prose-pre:border-white/10
                    prose-blockquote:border-electric-blue prose-blockquote:bg-white/5 prose-blockquote:rounded-r-xl
                    prose-img:rounded-xl prose-img:ring-1 prose-img:ring-white/10
                    prose-li:text-gray-200">
                    {!! $post->content !!}
                </article>
            </div>

            <!-- Share Buttons -->
            <div class="border-t border-white/10 pt-8 mb-12">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-electric-blue">share</span>
                    Share this post
                </h3>
                <div class="flex flex-wrap gap-3">
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 px-5 py-2.5 bg-white/5 border border-white/10 hover:bg-white/10 hover:border-electric-blue/50 rounded-xl text-gray-300 transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        <span>Twitter</span>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 px-5 py-2.5 bg-white/5 border border-white/10 hover:bg-white/10 hover:border-electric-blue/50 rounded-xl text-gray-300 transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        <span>Facebook</span>
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($post->title) }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 px-5 py-2.5 bg-white/5 border border-white/10 hover:bg-white/10 hover:border-electric-blue/50 rounded-xl text-gray-300 transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        <span>LinkedIn</span>
                    </a>
                    <button onclick="navigator.clipboard.writeText(window.location.href); this.innerHTML='<span class=\'material-symbols-outlined\'>check</span><span>Copied!</span>'; setTimeout(() => this.innerHTML='<span class=\'material-symbols-outlined\'>link</span><span>Copy Link</span>', 2000)" class="flex items-center gap-2 px-5 py-2.5 bg-white/5 border border-white/10 hover:bg-white/10 hover:border-electric-blue/50 rounded-xl text-gray-300 transition-all cursor-pointer">
                        <span class="material-symbols-outlined">link</span>
                        <span>Copy Link</span>
                    </button>
                </div>
            </div>

            <!-- Disqus Comments -->
            @if($commentsEnabled && $disqusShortname)
            <div class="border-t border-white/10 pt-8 mb-12 bg-white/5 rounded-2xl p-8">
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-electric-blue">forum</span>
                    Comments
                </h3>
                <div id="disqus_thread"></div>
                <script>
                    var disqus_config = function () {
                        this.page.url = '{{ url()->current() }}';
                        this.page.identifier = 'blog-post-{{ $post->id }}';
                    };
                    (function() {
                        var d = document, s = d.createElement('script');
                        s.src = 'https://{{ $disqusShortname }}.disqus.com/embed.js';
                        s.setAttribute('data-timestamp', +new Date());
                        (d.head || d.body).appendChild(s);
                    })();
                </script>
                <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
            </div>
            @endif

            <!-- Related Posts -->
            @if($relatedPosts->count() > 0)
            <div class="border-t border-white/10 pt-12">
                <h3 class="text-2xl font-bold text-white mb-8 flex items-center gap-2">
                    <span class="material-symbols-outlined text-bright-magenta">auto_awesome</span>
                    Related Posts
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedPosts as $related)
                    <a href="{{ route('blog.show', $related->slug) }}" class="group bg-white/5 border border-white/10 rounded-2xl overflow-hidden hover:border-white/20 transition-all hover:scale-[1.02]">
                        @if($related->featured_image)
                        <img src="{{ Storage::url($related->featured_image) }}" alt="{{ $related->title }}" class="w-full h-32 object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                        <div class="w-full h-32 bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center">
                            <span class="material-symbols-outlined text-4xl text-gray-700">article</span>
                        </div>
                        @endif
                        <div class="p-4">
                            <h4 class="text-white font-semibold group-hover:text-electric-blue transition-colors line-clamp-2">{{ $related->title }}</h4>
                            <p class="text-sm text-gray-500 mt-2">{{ $related->published_at->format('M d, Y') }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
