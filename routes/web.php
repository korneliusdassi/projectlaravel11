<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Pagination\Paginator;



Route::get('/', function ()
{
    return view('home', ['title' => 'Home Page']);
});

Route::get('/posts', function ()
{
    return view('posts', [
        'title' => 'Blog' ,
        'posts'=> Post::filter(request([
            'search',
            'category',
            'author']))->latest()->paginate(9)->appends(request()->query())]);
});

Route::get('/posts/{post:slug}', function(Post $post)
{

    return view('post', [
        'title' => 'Single Post',
        'post' => $post
    ]);
});

Route::get('/authors/{user:username}', function(User $user)
{
    // $posts = $user->posts->load(['category', 'author']);

    return view('posts', [
        'title' => count($user->posts) . ' Articles by '. $user->name,
        'posts' => $user->posts
    ]);
});

Route::get('/categories/{category:slug}', function(Category $category)
{
    // $posts = $category->posts->load(['category', 'author']);

    return view('posts', [
        'title' => 'Posts by Category: ' . $category->name,
        'posts' => $category->posts
    ]);
});

Route::get('/contact', function ()
{
    return view('contact', ['title' => 'Contact']);
});

Route::get('/about', function ()
{
    return view('about', ['name' => 'Kornelius Dassi', 'title'=> 'About']);
});
