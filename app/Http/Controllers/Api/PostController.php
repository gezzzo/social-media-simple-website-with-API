<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use function Laravel\Prompts\error;

class PostController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $posts=Post::get();
        if ($posts){
            $posts=PostResource::collection($posts);
            return $this->apiresponse($posts,200,'The data is Okay');
            return response()->json([
                'message' => 'The data is Okay',
                'data' => $posts
            ], 200);
        }
        return $this->apiresponse(null,404,'The data is not found');
    }
    public function show(string $id)
    {
        $post=Post::find($id);
        if ($post){
            $post=new PostResource($post);
            return $this->apiresponse($post,200,'The data is Okay');
        }

        return $this->apiresponse(null,404,'The data is not found');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->apiresponse(null,400,$validator->errors());
        }

        $post=Post::create($request->all());
        if ($post){
            $post=new PostResource($post);
            return $this->apiresponse($post,201,'The data is saved');
        }
        return $this->apiresponse(null,400,'The data not saved');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post=Post::find($id);
        if ($post){
            $validator = Validator::make($request->all(), [
                'title' => 'required|max:255',
                'description' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->apiresponse(null,400,$validator->errors());
            }
            $post->update($request->all());
            if ($post){
                $post=new PostResource($post);
                return $this->apiresponse($post,200,'The data is updated');
            }
            else{
                return $this->apiresponse(null,400,'The data is not updated');
            }
        }
        else {
            return $this->apiresponse(null, 404, 'The data is not found');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post=Post::find($id);
        if ($post){
            $post->delete($id);
            if ($post){
                return $this->apiresponse(null,200,'The data is deleted');
            }
            else{
                return $this->apiresponse(null,400,'The data is not deleted');
            }
        }
        else {
            return $this->apiresponse(null, 404, 'The post is not found');
        }
    }
}
