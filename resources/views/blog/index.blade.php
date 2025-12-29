@extends('layouts.blog')

@section('title', 'Blog - ' . setting('site_name', 'MrShort'))

@section('content')
<div class="min-h-screen bg-[#050505] relative overflow-hidden">
    <!-- Background Grid -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px]"></div>
    
    <!-- Spotlight Effect -->
    <div class="absolute top-0 left-1/4 w-[600px] h-[600px] bg-electric-blue/5 rounded-full blur-[150px]"></div>
    <div class="absolute bottom-0 right-1/4 w-[600px] h-[600px] bg-bright-magenta/5 rounded-full blur-[150px]"></div>

    <div class="relative z-10 py-16 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-5 py-2 rounded-full border border-gray-800 bg-gray-900/50 backdrop-blur-sm mb-6">
                    <span class="text-sm font-mono text-gray-400 tracking-wider">LATEST NEWS & UPDATES</span>
                </div>
                <h1 class="text-6xl md:text-8xl font-bold tracking-tighter text-white mb-6 leading-none">
                    BLOG<span class="text-transparent bg-clip-text bg-gradient-to-r from-electric-blue to-bright-magenta">.</span>
                </h1>
                <p class="text-xl text-gray-400 max-w-2xl mx-auto">
                    Tips, tutorials, and insights to help you maximize your earnings
                </p>
            </div>

            <!-- Featured Posts -->
            @if($featuredPosts->count() > 0 && !request('search') && !request('category'))
            <div class="mb-16">
                <h2 class="text-2xl font-bold text-white mb-8 flex items-center gap-3">
                    <span class="material-symbols-outlined text-electric-blue">star</span>
                    Featured Posts
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($featuredPosts as $featured)
                    <a href="{{ route('blog.show', $featured->slug) }}" class="group relative bg-white/5 border border-white/10 rounded-2xl overflow-hidden hover:border-electric-blue/50 transition-all duration-300 hover:scale-[1.02]">
                        @if($featured->featured_image)
                        <img src="{{ Storage::url($featured->featured_image) }}" alt="{{ $featured->title }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                        <div class="w-full h-48 bg-gradient-to-br from-electric-blue/20 to-bright-magenta/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-5xl text-white/30">article</span>
                        </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-6">
                            @if($featured->category)
                            <span class="inline-block px-3 py-1 text-xs font-semibold text-electric-blue bg-electric-blue/10 rounded-full mb-3">{{ $featured->category->name }}</span>
                            @endif
                            <h3 class="text-lg font-bold text-white group-hover:text-electric-blue transition-colors line-clamp-2">{{ $featured->title }}</h3>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar -->
                <div class="lg:col-span-1 order-2 lg:order-1">
                    <!-- Search -->
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 mb-6 backdrop-blur-sm">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-electric-blue">search</span>
                            Search
                        </h3>
                        <form action="{{ route('blog.index') }}" method="GET">
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search posts..." 
                                    class="w-full bg-white/5 border border-white/10 rounded-xl py-3 px-4 text-white placeholder-gray-500 focus:ring-2 focus:ring-electric-blue/50 focus:border-electric-blue/50 transition-all">
                                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-electric-blue transition-colors">
                                    <span class="material-symbols-outlined">arrow_forward</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Categories -->
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-bright-magenta">category</span>
                            Categories
                        </h3>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('blog.index') }}" class="flex justify-between items-center py-2.5 px-4 rounded-xl {{ !request('search') && !request('category') ? 'bg-gradient-to-r from-electric-blue to-bright-magenta text-white' : 'text-gray-300 hover:bg-white/5' }} transition-all">
                                    <span>All Posts</span>
                                    <span class="text-sm opacity-70">{{ $posts->total() }}</span>
                                </a>
                            </li>
                            @foreach($categories as $category)
                            <li>
                                <a href="{{ route('blog.category', $category->slug) }}" class="flex justify-between items-center py-2.5 px-4 rounded-xl {{ request('category') == $category->slug ? 'bg-gradient-to-r from-electric-blue to-bright-magenta text-white' : 'text-gray-300 hover:bg-white/5' }} transition-all">
                                    <span>{{ $category->name }}</span>
                                    <span class="text-sm opacity-70">{{ $category->posts_count }}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Posts Grid -->
                <div class="lg:col-span-3 order-1 lg:order-2">
                    @if($posts->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($posts as $post)
                        <article class="group bg-white/5 border border-white/10 rounded-2xl overflow-hidden hover:border-white/20 transition-all duration-300 hover:scale-[1.01]">
                            <a href="{{ route('blog.show', $post->slug) }}">
                                @if($post->featured_image)
                                <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                <div class="w-full h-48 bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-5xl text-gray-700">article</span>
                                </div>
                                @endif
                            </a>
                            <div class="p-6">
                                <div class="flex items-center gap-4 mb-3">
                                    @if($post->category)
                                    <span class="text-xs font-semibold text-electric-blue uppercase tracking-wider">{{ $post->category->name }}</span>
                                    @endif
                                    <span class="text-xs text-gray-500">{{ $post->published_at->format('M d, Y') }}</span>
                                </div>
                                <a href="{{ route('blog.show', $post->slug) }}">
                                    <h2 class="text-xl font-bold text-white mb-3 group-hover:text-electric-blue transition-colors line-clamp-2">{{ $post->title }}</h2>
                                </a>
                                <p class="text-gray-400 text-sm line-clamp-3 mb-4">{{ $post->excerpt ?: Str::limit(strip_tags($post->content), 150) }}</p>
                                <div class="flex items-center justify-between pt-4 border-t border-white/10">
                                    <div class="flex items-center gap-2">
                                        @if($post->author)
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($post->author->name) }}&size=32&background=0D8ABC&color=fff" alt="{{ $post->author->name }}" class="w-8 h-8 rounded-full">
                                        <span class="text-sm text-gray-400">{{ $post->author->name }}</span>
                                        @endif
                                    </div>
                                    <span class="text-xs text-gray-500 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">schedule</span>
                                        {{ $post->reading_time }} min
                                    </span>
                                </div>
                            </div>
                        </article>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-10">
                        {{ $posts->links() }}
                    </div>
                    @else
                    <div class="text-center py-16 bg-white/5 border border-white/10 rounded-2xl">
                        <span class="material-symbols-outlined text-6xl text-gray-700 mb-4">article</span>
                        <h3 class="text-2xl font-bold text-white mb-2">No posts found</h3>
                        <p class="text-gray-400 mb-6">Check back later for new content.</p>
                        <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-electric-blue to-bright-magenta text-white font-bold rounded-full hover:scale-105 transition-transform">
                            <span>Browse All Posts</span>
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
