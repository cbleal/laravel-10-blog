<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $article = Article::with('Category')->latest()->get();

            return DataTables::of($article)
                ->addIndexColumn()
                ->addColumn('category_id', function ($article) {
                    return $article->Category->name;
                })
                ->addColumn('status', function ($article) {
                    if ($article->status == 0) {
                        return '<span class="badge bg-danger">Privado</span>';
                    } else {
                        return '<span class="badge bg-success">Publicado</span>';
                    }
                })
                ->addColumn('button', function ($article) {
                    return '<div class="text-center">
                                    <a href="articles/' . $article->id . '" class="btn btn-primary btn-sm">
                                        Detalhes
                                    </a>

                                    <a href="articles/' . $article->id . '/edit" class="btn btn-secondary btn-sm">
                                        Editar
                                    </a>

                                    <a href="#" onclick="deleteArticle(this)" data-id="' . $article->id . '" class="btn btn-danger btn-sm">
                                        Excluir
                                    </a>
                                </div>';
                })
                ->rawColumns(['category_id', 'status', 'button'])
                ->make();
        }

        # mandar para view
        return view('back.article.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('back.article.create', [
            'categories' => Category::get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleRequest $request)
    {
        // dd($request);

        # valida os dados do request e atribui a um array
        $data = $request->validated();
        # recupera o file do request
        $file = $request->file('img');
        // dd($file);
        # renomeia o arquivo com sua extensão original
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
        // dd($fileName);
        # armazena o arquivo
        $file->storeAs('public/back', $fileName);
        # atribui o nome do arquivo ao array de dados com a chave 'img'
        $data['img'] = $fileName;
        # cria o slug
        $data['slug'] = Str::slug($data['title']);
        // dd($data);
        # salva os dados
        Article::create($data);
        # redireciona para a página index de articles
        return redirect(url('articles'))->with('success', 'Artigo criado com sucesso...');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('back.article.show', ['article' => Article::find($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        return view('back.article.update', [
            'article' => Article::find($id),
            'categories' => Category::get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, string $id)
    {
        $data = $request->validated();

        if ($request->hasFile('img')) {
            # recupera o file do request
            $file = $request->file('img');
            # renomeia o arquivo com sua extensão original
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            # armazena o arquivo
            $file->storeAs('public/back', $fileName);
            # apaga o arquivo img antigo
            Storage::delete('public/back/' . $request->oldImg);
            # atribui o nome do arquivo ao array de dados com a chave 'img'
            $data['img'] = $fileName;
        } else {
            $data['img'] = $request->oldImg;
        }

        # cria o slug
        $data['slug'] = Str::slug($data['title']);
        # atualiza os dados
        Article::find($id)->update($data);
        # redireciona para a página index de articles
        return redirect(url('articles'))->with('success', 'Artigo editado com sucesso...');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Article::find($id);

        if ($data) {
            Storage::delete('public/back/' . $data->img);

            $data->delete();

            return response()->json(['success' => 'Artigo removido com sucesso...']);
        } else {
            return response()->json(['error' => 'Artigo não encontrado.'], 404);
        }
    }
}
