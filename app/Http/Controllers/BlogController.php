<?php

namespace App\Http\Controllers;

use App\Models\Blog\Blog;
use App\Models\Profiles\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $blogs = Blog::all();

        if ($blogs == null) {
            return response([], Response::HTTP_NO_CONTENT);
        }

        $response = [];

        foreach ($blogs as $blog) {
            $imgUrl = asset($blog->thumbnail_url);
            $data = [
                'blogId' => $blog->blog_id,
                'title' => $blog->title,
                'thumbnailUrl' => $imgUrl,
                'description' => $blog->description,
                'authorId' => $blog->author_id
            ];

            array_push($response, $data);
        }

        return response($response, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        if ($request->all() === null) {
            return response('Request cannot be empty', Response::HTTP_BAD_REQUEST);
        }

        $authorProfile = Profile::where('profile_id', '=', $request->input('authorId'))->first();

        if (!$authorProfile) {
            return response("Profile not found!.", Response::HTTP_NOT_FOUND);
        }

        // $authorRoleType = $authorProfile->roleType;

        // if ( intval($authorRoleType)  != -1 || intval($authorRoleType) != 1) {
        //     return response([], Response::HTTP_UNAUTHORIZED);
        // }

        $file = $request->file('image');
        if (!$file->isValid()) {
            return response()->json(['invalid_file_upload'], 400);
        }

        $path = Storage::putFile('public/blog_assets', $file);


        $blog = new Blog([
            'thumbnail_url' => $path,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'author_id' => $request->input('authorId')
        ]);

        $blog->save();

        $response = [
            'blogId' => $blog->blog_id
        ];

        return response($response, Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $blogId)
    {
        //
        $blog = Blog::where('blog_id', '=', $blogId)->first();

        if ($blog == null) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $imgUrl = asset($blog->thumbnail_url);

        $response = [
            "blogId" => $blog->blog_id,
            "title" => $blog->title,
            "description" => $blog->description,
            "thumbnailUrl" => $imgUrl,
            "authorId" => $blog->author_id
        ];
        return response($response, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $authorId, string $blogId, Request $request)
    {
        //

        // if($blogId){
        //     return response([
        //         "authorId"=>$authorId,
        //         "blogId"=>$blogId
        //     ]);
        // }

        $authorProfile = Profile::where('profile_id', '=', $authorId)->first();



        if ($authorProfile == null) {
            return response("Profile not found!.", Response::HTTP_NOT_FOUND);
        }

        $authorRoleType = intval($authorProfile->roleType);

        if($authorRoleType == 0){
            return response("", Response::HTTP_UNAUTHORIZED);
        }

        $blog = Blog::where('blog_id', '=', $blogId)->first();

        if ($blog == null) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $file = $request->file('image');

        if (!$file->isValid()) {
            return response()->json(['invalid_file_upload'], Response::HTTP_BAD_REQUEST);
        }

        $path = Storage::putFile('public', $file);

        $blog->title = $request->input('title');
        $blog->description = $request->input('description');
        $blog->author_id = $authorProfile->profile_id;
        $blog->thumbnail_url = $path;
        $blog->save();

        $response = [
            'blogId' => $blog->blog_id,
            'title' => $blog->title,
        ];

        return response($response, Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $blogId)
    {
        //
        $blog = Blog::where('blog_id', '=', $blogId)->first();

        if (!$blog) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $blog->delete();

        return response([], Response::HTTP_ACCEPTED);
    }
}
