@extends('layouts.blog')

@section('title', $category->name . ' - Blog - ' . setting('site_name', 'MrShort'))

@section('content')
<div class="min-h-screen bg-[#050505] relative overflow-hidden">
    <!-- Background Grid -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px]"></div>
    
    <!-- Spotlight Effects -->
    <div class="absolute top-0 left-1/3 w-[600px] h-[600px] bg-bright-magenta/5 rounded-full blur-[150px]"></div>
    <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-electric-blue/5 rounded-full blur-[150px]"></div>

    <div class="relative z-10 py-16 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-16">
                <nav class="mb-6">
                    <ol class="flex items-center justify-center gap-2 text-sm text-gray-400">
                        <li><a href="{{ route('blog.index') }}" class="hover:text-electric-blue transition-colors">Blog</a></li>
                        <li><span class="text-gray-600">/</span></li>
                        <li class="text-white">{{ $category->name }}</li>
                    </ol>
                </nav>
                <div class="inline-flex items-center px-5 py-2 rounded-full border border-bright-magenta/50 bg-bright-magenta/10 backdrop-blur-sm mb-6">
                    <span class="text-sm font-mono text-bright-magenta tracking-wider">CATEGORY</span>
                </div>
                <h1 class="text-5xl md:text-7xl font-bold tracking-tighter text-white mb-6 leading-none">
                    {{ $category->name }}<span class="text-transparent bg-clip-text bg-gradient-to-r from-electric-blue to-bright-magenta">.</span>
                </h1>
                @if($category->description)
                <p class="text-xl text-gray-400 max-w-2xl mx-auto">{{ $category->description }}</p>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar -->
                <div class="lg:col-span-1 order-2 lg:order-1">
                    <!-- Categories -->
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm sticky top-24">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-bright-magenta">category</span>
                            Categories
                        </h3>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('blog.index') }}" class="flex justify-between items-center py-2.5 px-4 rounded-xl text-gray-300 hover:bg-white/5 transition-all">
                                    <span>All Posts</span>
                                </a>
                            </li>
                            @foreach($categories as $cat)
                            <li>
                                <a href="{{ route('blog.category', $cat->slug) }}" class="flex justify-between items-center py-2.5 px-4 rounded-xl {{ $cat->id == $category->id ? 'bg-gradient-to-r from-electric-blue to-bright-magenta text-white' : 'text-gray-300 hover:bg-white/5' }} transition-all">
                                    <span>{{ $cat->name }}</span>
                                    <span class="text-sm opacity-70">{{ $cat->posts_count }}</span>
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
                        <h3 class="text-2xl font-bold text-white mb-2">No posts in this category</h3>
                        <p class="text-gray-400 mb-6">Check back later for new content.</p>
                        <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-electric-blue to-bright-magenta text-white font-bold rounded-full hover:scale-105 transition-transform">
                            <span>View All Posts</span>
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
