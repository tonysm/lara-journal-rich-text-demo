<?php

use App\Models\Attachment;
use App\Models\Post;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Tonysm\GlobalId\Facades\Locator;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('cache.headers:private;max_age=2628000;etag')->get('attachments/{sgid}', function ($sgid) {
    $model = Locator::locateSigned($sgid, [
        'for' => 'rich-text-laravel',
    ]);

    return redirect($model->trixAttachmentUrl());
})->name('attachments.show');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::post('attachments', function () {
        request()->validate([
            'attachment' => ['required', 'file'],
        ]);

        $attachment = auth()->user()->currentTeam->addAttachment(
            request()->file('attachment')
        );

        return [
            'sgid' => $attachment->sgid,
            'url' => $attachment->attachmentUrl(),
            'content' => $attachment->toTrixContent(),
        ];
    })->name('attachments.store');

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

    // Route::post('attachments', function () {
    //     request()->validate([
    //         'attachment' => ['required', 'file'],
    //     ]);

    //     $file = request()->file('attachment');

    //     return [
    //         'url' => Storage::disk('public')->url($file->store('trix-attachments', 'public')),
    //     ];
    // })->name('attachments.store');
});
