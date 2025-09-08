<?php

namespace App\Controllers;

use Core\Controller;
use Core\Session;
use App\Models\Post;
use App\Models\Category;
use App\Models\Page;
use App\Models\Tag;

class BlogController extends Controller
{
    /**
     * Blog dashboard
     */
    public function index(): void
    {
        $stats = [
            'total_posts' => Post::query()->count(),
            'published_posts' => Post::where('status', '=', 'published')->count(),
            'draft_posts' => Post::where('status', '=', 'draft')->count(),
            'total_categories' => Category::query()->count(),
            'total_pages' => Page::query()->count(),
            'published_pages' => Page::where('status', '=', 'published')->count(),
        ];
        
        $recent_posts = Post::query()
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();
        
        $this->render('admin/blog/index', [
            'title' => 'Blog Dashboard',
            'stats' => $stats,
            'recent_posts' => $recent_posts
        ]);
    }
    
    // ==================== POSTS ====================
    
    /**
     * List all posts
     */
    public function posts(): void
    {
        $posts = Post::query()
            ->orderBy('created_at', 'DESC')
            ->get();
        
        $this->render('admin/blog/posts/index', [
            'title' => 'Blog Posts',
            'posts' => $posts
        ]);
    }
    
    /**
     * Show post form (create or edit)
     */
    public function postForm($id = null): void
    {
        $post = null;
        $postTagIds = [];
        
        if ($id) {
            $post = Post::find($id);
            if (!$post) {
                Session::flash('error', 'Post not found.');
                $this->redirect(url('/admin/blog/posts'));
                return;
            }
            $postTags = $post->tags();
            $postTagIds = array_map(fn($tag) => $tag->id, $postTags);
        }
        
        $categories = Category::where('is_active', '=', 1)->get();
        $tags = Tag::query()->get();
        
        $this->render('admin/blog/posts/form', [
            'title' => $id ? 'Edit Post' : 'Create New Post',
            'post' => $post,
            'categories' => $categories,
            'tags' => $tags,
            'postTagIds' => $postTagIds
        ]);
    }
    
    /**
     * Save post (create or update)
     */
    public function savePost($id = null): void
    {
        $post = null;
        if ($id) {
            $post = Post::find($id);
            if (!$post) {
                Session::flash('error', 'Post not found.');
                $this->redirect(url('/admin/blog/posts'));
                return;
            }
        }
        
        $data = [
            'title' => trim($this->request->input('title', '')),
            'slug' => trim($this->request->input('slug', '')),
            'excerpt' => trim($this->request->input('excerpt', '')),
            'content' => $this->request->input('content', ''),
            'featured_image' => trim($this->request->input('featured_image', '')),
            'category_id' => $this->request->input('category_id') ?: null,
            'status' => $this->request->input('status', 'draft'),
            'is_featured' => $this->request->input('is_featured', 0),
            'meta_title' => trim($this->request->input('meta_title', '')),
            'meta_description' => trim($this->request->input('meta_description', '')),
        ];
        
        if (empty($data['title']) || empty($data['content'])) {
            Session::flash('error', 'Title and content are required.');
            $redirectUrl = $id ? '/admin/blog/posts/' . $id . '/edit' : '/admin/blog/posts/create';
            $this->redirect(url($redirectUrl));
            return;
        }
        
        try {
            if ($post) {
                // Update existing post
                if ($data['status'] === 'published' && $post->status !== 'published') {
                    $data['published_at'] = date('Y-m-d H:i:s');
                }
                $post->update($data);
                $message = 'Post updated successfully.';
            } else {
                // Create new post
                $data['author_id'] = Session::get('user_id');
                $data['published_at'] = $data['status'] === 'published' ? date('Y-m-d H:i:s') : null;
                $post = Post::create($data);
                $message = 'Post created successfully.';
            }
            
            // Handle tags
            $tagIds = $this->request->input('tags', []);
            $post->attachTags($tagIds);
            
            Session::flash('success', $message);
            $this->redirect(url('/admin/blog/posts'));
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to save post: ' . $e->getMessage());
            $redirectUrl = $id ? '/admin/blog/posts/' . $id . '/edit' : '/admin/blog/posts/create';
            $this->redirect(url($redirectUrl));
        }
    }
    
    /**
     * Delete post
     */
    public function deletePost($id): void
    {
        $post = Post::find($id);
        
        if (!$post) {
            Session::flash('error', 'Post not found.');
            $this->redirect(url('/admin/blog/posts'));
            return;
        }
        
        try {
            $post->delete();
            Session::flash('success', 'Post deleted successfully.');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to delete post: ' . $e->getMessage());
        }
        
        $this->redirect(url('/admin/blog/posts'));
    }
    
    // ==================== CATEGORIES ====================
    
    /**
     * List all categories
     */
    public function categories(): void
    {
        $categories = Category::query()
            ->orderBy('sort_order', 'ASC')
            ->orderBy('name', 'ASC')
            ->get();
        
        $this->render('admin/blog/categories/index', [
            'title' => 'Categories',
            'categories' => $categories
        ]);
    }
    
    /**
     * Show category form (create or edit)
     */
    public function categoryForm($id = null): void
    {
        $category = null;
        
        if ($id) {
            $category = Category::find($id);
            if (!$category) {
                Session::flash('error', 'Category not found.');
                $this->redirect(url('/admin/blog/categories'));
                return;
            }
        }
        
        $this->render('admin/blog/categories/form', [
            'title' => $id ? 'Edit Category' : 'Create New Category',
            'category' => $category
        ]);
    }
    
    /**
     * Save category (create or update)
     */
    public function saveCategory($id = null): void
    {
        $category = null;
        if ($id) {
            $category = Category::find($id);
            if (!$category) {
                Session::flash('error', 'Category not found.');
                $this->redirect(url('/admin/blog/categories'));
                return;
            }
        }
        
        $data = [
            'name' => trim($this->request->input('name', '')),
            'slug' => trim($this->request->input('slug', '')),
            'description' => trim($this->request->input('description', '')),
            'color' => trim($this->request->input('color', '#007bff')),
            'is_active' => $this->request->input('is_active', 1),
            'sort_order' => (int) $this->request->input('sort_order', 0)
        ];
        
        if (empty($data['name'])) {
            Session::flash('error', 'Category name is required.');
            $redirectUrl = $id ? '/admin/blog/categories/' . $id . '/edit' : '/admin/blog/categories/create';
            $this->redirect(url($redirectUrl));
            return;
        }
        
        try {
            if ($category) {
                $category->update($data);
                $message = 'Category updated successfully.';
            } else {
                Category::create($data);
                $message = 'Category created successfully.';
            }
            
            Session::flash('success', $message);
            $this->redirect(url('/admin/blog/categories'));
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to save category: ' . $e->getMessage());
            $redirectUrl = $id ? '/admin/blog/categories/' . $id . '/edit' : '/admin/blog/categories/create';
            $this->redirect(url($redirectUrl));
        }
    }
    
    /**
     * Delete category
     */
    public function deleteCategory($id): void
    {
        $category = Category::find($id);
        
        if (!$category) {
            Session::flash('error', 'Category not found.');
            $this->redirect(url('/admin/blog/categories'));
            return;
        }
        
        try {
            $category->delete();
            Session::flash('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to delete category: ' . $e->getMessage());
        }
        
        $this->redirect(url('/admin/blog/categories'));
    }
    
    // ==================== PAGES ====================
    
    /**
     * List all pages
     */
    public function pages(): void
    {
        $pages = Page::query()
            ->orderBy('title', 'ASC')
            ->get();
        
        $this->render('admin/blog/pages/index', [
            'title' => 'Pages',
            'pages' => $pages
        ]);
    }
    
    /**
     * Show page form (create or edit)
     */
    public function pageForm($id = null): void
    {
        $page = null;
        
        if ($id) {
            $page = Page::find($id);
            if (!$page) {
                Session::flash('error', 'Page not found.');
                $this->redirect(url('/admin/blog/pages'));
                return;
            }
        }
        
        $this->render('admin/blog/pages/form', [
            'title' => $id ? 'Edit Page' : 'Create New Page',
            'page' => $page
        ]);
    }
    
    /**
     * Save page (create or update)
     */
    public function savePage($id = null): void
    {
        $page = null;
        if ($id) {
            $page = Page::find($id);
            if (!$page) {
                Session::flash('error', 'Page not found.');
                $this->redirect(url('/admin/blog/pages'));
                return;
            }
        }
        
        $data = [
            'title' => trim($this->request->input('title', '')),
            'slug' => trim($this->request->input('slug', '')),
            'content' => $this->request->input('content', ''),
            'excerpt' => trim($this->request->input('excerpt', '')),
            'template' => trim($this->request->input('template', 'default')),
            'status' => $this->request->input('status', 'draft'),
            'is_homepage' => $this->request->input('is_homepage', 0),
            'meta_title' => trim($this->request->input('meta_title', '')),
            'meta_description' => trim($this->request->input('meta_description', '')),
        ];
        
        if (empty($data['title']) || empty($data['content'])) {
            Session::flash('error', 'Title and content are required.');
            $redirectUrl = $id ? '/admin/blog/pages/' . $id . '/edit' : '/admin/blog/pages/create';
            $this->redirect(url($redirectUrl));
            return;
        }
        
        try {
            if ($page) {
                // Update existing page
                if ($data['status'] === 'published' && $page->status !== 'published') {
                    $data['published_at'] = date('Y-m-d H:i:s');
                }
                $page->update($data);
                $message = 'Page updated successfully.';
            } else {
                // Create new page
                $data['author_id'] = Session::get('user_id');
                $data['published_at'] = $data['status'] === 'published' ? date('Y-m-d H:i:s') : null;
                $page = Page::create($data);
                $message = 'Page created successfully.';
            }
            
            // If set as homepage, unset others
            if ($data['is_homepage']) {
                $page->setAsHomepage();
            }
            
            Session::flash('success', $message);
            $this->redirect(url('/admin/blog/pages'));
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to save page: ' . $e->getMessage());
            $redirectUrl = $id ? '/admin/blog/pages/' . $id . '/edit' : '/admin/blog/pages/create';
            $this->redirect(url($redirectUrl));
        }
    }
    
    /**
     * Delete page
     */
    public function deletePage($id): void
    {
        $page = Page::find($id);
        
        if (!$page) {
            Session::flash('error', 'Page not found.');
            $this->redirect(url('/admin/blog/pages'));
            return;
        }
        
        try {
            $page->delete();
            Session::flash('success', 'Page deleted successfully.');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to delete page: ' . $e->getMessage());
        }
        
        $this->redirect(url('/admin/blog/pages'));
    }
}
