<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminPostController extends Controller
{
    public function index()
    {
        return view(
            'admin.posts.index',
            ['posts' => Post::paginate(20)]
        );
    }

    public function create()
    {
        return view(
            'admin.posts.create',
        );
    }

    public function store()
    {

        array_merge(
            $this->validatePost(),
            [
                $attributes['user_id'] = auth()->id(),
                $attributes['thumbnail'] = request()->file('thumbnail')->store('thumbnails')
            ]
        );

        Post::create($attributes);

        return redirect('/');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', [
            'post' => $post
        ]);
    }

    public function update(Post $post)
    {
        $attributes = $this->validatePost($post);

        if ($attributes['thumbnail'] ?? false) {

            $attributes['thumbnail'] = request()->file('thumbnail')->store('thumbnails');
        }

        $post->update($attributes);

        return back()->with('Success', 'Post updated');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return back();
    }

    protected function validatePost(?Post $post = null)
    {

        $post ??= new Post();

        return request()->validate([
            'title' => ['required'],
            'slug' => ['required', Rule::unique('posts', 'slug')->ignore($post->id)],
            'excerpt' => ['required'],
            'body' => ['required'],
            'thumbnail' => $post->exists ? ['image'] : ['required', 'image'],
            'category_id' => ['required', Rule::exists('categories', 'id')],
        ]);
    }
}
