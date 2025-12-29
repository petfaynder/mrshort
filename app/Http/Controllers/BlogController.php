<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display blog listing page.
     */
    public function index(Request $request)
    {
        // Check if blog is enabled
        if (!setting('blog_enabled', false)) {
            abort(404);
        }

        $perPage = (int) setting('blog_posts_per_page', 10);
        
        $query = BlogPost::published()
            ->with(['category', 'author'])
            ->orderByDesc('published_at');

        // Category filter
        if ($request->has('category')) {
            $category = BlogCategory::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate($perPage);
        $categories = BlogCategory::where('is_active', true)
            ->withCount(['posts' => fn($q) => $q->published()])
            ->orderBy('order')
            ->get();
        
        $featuredPosts = BlogPost::published()
            ->featured()
            ->with(['category', 'author'])
            ->take(3)
            ->get();

        return view('blog.index', compact('posts', 'categories', 'featuredPosts'));
    }

    /**
     * Display a single blog post.
     */
    public function show(string $slug)
    {
        if (!setting('blog_enabled', false)) {
            abort(404);
        }

        $post = BlogPost::where('slug', $slug)
            ->published()
            ->with(['category', 'author'])
            ->firstOrFail();

        // Increment view count
        $post->recordView();

        // Related posts
        $relatedPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->when($post->category_id, function ($query) use ($post) {
                $query->where('category_id', $post->category_id);
            })
            ->limit(3)
            ->get();

        $commentsEnabled = setting('blog_comments_enabled', false);
        $disqusShortname = setting('disqus_shortname', '');

        return view('blog.show', compact('post', 'relatedPosts', 'commentsEnabled', 'disqusShortname'));
    }

    /**
     * Display posts by category.
     */
    public function category(string $slug)
    {
        if (!setting('blog_enabled', false)) {
            abort(404);
        }

        $category = BlogCategory::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $perPage = (int) setting('blog_posts_per_page', 10);
        
        $posts = BlogPost::published()
            ->where('category_id', $category->id)
            ->with(['category', 'author'])
            ->orderByDesc('published_at')
            ->paginate($perPage);

        $categories = BlogCategory::where('is_active', true)
            ->withCount(['posts' => fn($q) => $q->published()])
            ->orderBy('order')
            ->get();

        return view('blog.category', compact('posts', 'category', 'categories'));
    }
}
