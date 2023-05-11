<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ArticleResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::select(
            "articles.id",
            "articles.image",
            "articles.title",
            "articles.content",
            "users.name"
        )
        ->join("users", "users.id", "=", "articles.user_id")
        ->paginate(5);

        return new ArticleResource(true, 'List Data Article', $articles);
    }

    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'image'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title'     => 'required',
            'content'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/images', $image->hashName());

        //create article
        $article = Article::create([
            'image'     => $image->hashName(),
            'title'     => $request->title,
            'content'   => $request->content,
            'user_id'   => $request->user_id,
        ]);

        //return response
        return new ArticleResource(true, 'Data artikel berhasil ditambahkan!', $article);
    }

    public function show(Article $article)
    {
        //return single article as a resource
        return new ArticleResource(true, 'Data artikel ditemukan!', $article);
    }

    public function update(Request $request, Article $article)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'title'     => 'required',
            'content'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //check if image is not empty
        if ($request->hasFile('image')) {

            //upload image
            $image = $request->file('image');
            $image->storeAs('public/images', $image->hashName());

            //delete old image
            Storage::delete('public/images/'.$article->image);

            //update article with new image
            $article->update([
                'image'     => $image->hashName(),
                'title'     => $request->title,
                'content'   => $request->content,
                'user_id'   => $request->user_id,
            ]);

        } else {

            //update article without image
            $article->update([
                'title'     => $request->title,
                'content'   => $request->content,
                'user_id'   => $request->user_id,
            ]);
        }

        //return response
        return new ArticleResource(true, 'Data article berhasil diupdate!', $article);
    }

    public function destroy(Article $article)
    {
        //delete image
        Storage::delete('public/images/'.$article->image);

        //delete article
        $article->delete();

        //return response
        return new ArticleResource(true, 'Data artikel berhasil dihapus!', null);
    }
}
