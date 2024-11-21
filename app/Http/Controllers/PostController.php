<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum',except: ['index','show'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        return response()->json([
            'message' => "success",
            'data' => $posts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'caption' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        // Check if an image is uploaded
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('posts'), $imageName);
            $fields['image'] = $imageName;
        } else {
            $fields['image'] = null;  // Set a default value or leave it null
        }

        $post = $request->user()->posts()->create([
            'caption' => $fields['caption'],
            'image' => $fields['image'],
        ]);

        return response()->json(['message' => 'Post created successfully', 'posts' => $post], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'caption' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);
        $data['caption'] = $data['caption'] ?? $post->caption;  // Retain existing caption if not provided

        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            $oldImage = public_path('posts/' . $post->image);
            if (file_exists($oldImage)) {
                unlink($oldImage);
            }

            // Upload new image
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('posts'), $imageName);
            $data['image'] = $imageName;
        } else {
            $data['image'] = $post->image;  // Retain current image if no new image is uploaded
        }

        $post->update($data);

        return response()->json(['message' => 'Post updated successfully', 'posts' => $post], 201);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Gate::authorize('modify', $post);
        $post->delete();
        $oldPath = public_path('posts/' . $post->image);
        if(file_exists($oldPath)){
            unlink($oldPath);
        }
        return response()->json(['message'=> 'Post deleted successfully'], 201);
    }
}
