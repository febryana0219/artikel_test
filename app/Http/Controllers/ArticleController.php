<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{

    // public function index()
    // {
    //     //get articles
    //     $articles = Article::latest()->paginate(5);

    //     //render view with articles
    //     return view('articles.index', compact('articles'));
    // }

    public function index(){
        $articles = Article::select(
            "articles.id",
            "articles.image",
            "articles.title",
            "articles.content",
            "users.name"
        )
        ->join("users", "users.id", "=", "articles.user_id")
        ->paginate(5);

        return view('articles.index', ['articles' => $articles]);
    }

    public function create()
    {
        return view('articles.create');
    }

    public function store(Request $request)
    {
        //validate form
        $this->validate($request, [
            'image'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title'     => 'required|min:5',
            'content'   => 'required|min:10'
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/images', $image->hashName());

        //create article
        Article::create([
            'image'     => $image->hashName(),
            'title'     => $request->title,
            'content'   => $request->content,
            'user_id'   => 1
        ]);

        //redirect to index
        return redirect()->route('articles.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    public function edit(Article $article)
    {
        return view('articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        //validate form
        $this->validate($request, [
            'image'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title'     => 'required|min:5',
            'content'   => 'required|min:10'
        ]);

        //check if image is uploaded
        if ($request->hasFile('image')) {

            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/images', $image->hashName());

            //delete old image
            Storage::delete('public/images/'.$article->image);

            //update article with new image
            $article->update([
                'image'     => $image->hashName(),
                'title'     => $request->title,
                'content'   => $request->content
            ]);

        } else {

            //update article without image
            $article->update([
                'title'     => $request->title,
                'content'   => $request->content
            ]);
        }

        //redirect to index
        return redirect()->route('articles.index')->with(['success' => 'Data berhasil diupdate!']);
    }

    public function destroy(Article $article)
    {
        //delete image
        Storage::delete('public/images/'. $article->image);

        //delete article
        $article->delete();

        //redirect to index
        return redirect()->route('articles.index')->with(['success' => 'Data berhasil dihapus!']);
    }
}
