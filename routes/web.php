<?php

use App\Models\Post;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('posts', function () {
        return view('posts.index', [
            'posts' => auth()->user()->posts()
                ->latest()
                ->get(),
        ]);
    })->name('posts.index');

    Route::get('posts/create', function () {
        return view('posts.create', [
            'post' => auth()->user()->posts()->make(),
        ]);
    })->name('posts.create');

    Route::post('posts', function () {
        $post = auth()->user()->posts()->create(request()->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required'],
        ]));

        return redirect()->route('posts.show', $post);
    })->name('posts.store');

    Route::get('posts/{post}', function (Post $post) {
        return view('posts.show', [
            'post' => $post,
        ]);
    })->name('posts.show');

    Route::get('posts/{post}/edit', function (Post $post) {
        return view('posts.edit', [
            'post' => $post,
        ]);
    })->name('posts.edit');

    Route::put('posts/{post}', function (Post $post) {
        $post->update(request()->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required'],
        ]));

        return redirect()->route('posts.show', $post);
    })->name('posts.update');

    Route::post('attachments', function () {
        request()->validate([
            'attachment' => ['required', 'file'],
        ]);

        $path = request()->file('attachment')->store('trix-attachments', 'public');

        return [
            'image_url' => Storage::disk('public')->url($path),
        ];
    })->name('attachments.store');
});
