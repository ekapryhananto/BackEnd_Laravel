<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Http\Resources\CategoryResource;
use App\Models\Books;
use App\Http\Resources\BooksResource;
use Illuminate\Support\Facades\Validator;


class CategoryController extends Controller
{
    public function index(){
        $category = Category::oldest()->get();

        return new CategoryResource(true, 'List Data Category', $category);
    }

    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'name'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $category = Category::create([
            'name'   => $request->name,
        ]);

        return new CategoryResource(true, 'Data category Berhasil Ditambahkan!', $category);
    }
    public function show($id)
    {
        $category = Category::find($id);

        //return single post as a resource
        return new CategoryResource(true, 'Detail Data Post!', $category);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $category = Category::find($id);
        $category->update([
            'name'     => $request->name,
        ]);

        return new CategoryResource(true, 'Data Category Berhasil Diubah!', $category);
    }
    public function destroy($id){
        $category = Category::find($id);
        $category->delete();

        return new CategoryResource(true, 'Data Category Berhasil di Delete', $category);
    }
    public function getBooks($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $books = Books::where('category_id', $id)->get();

        // return new BooksResource(true, 'List Data Books by Category', $books);
        return response()->json(['category' => $category, 'books' => $books], 200);
    }
    public function filter(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'title' => 'string',
            'minYear' => 'nullable|integer|min:1980',
            'maxYear' => 'nullable|integer|max:2021',
            'minPage' => 'nullable|integer|min:1',
            'maxPage' => 'nullable|integer',
            'sortByTitle' => 'nullable|in:asc,desc',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $query = Books::where('category_id', $id);

        if ($request->has('title')) {
            $query->where('title', 'ilike', '%' . $request->input('title') . '%');
        }
        if ($request->has('minYear')) {
            $query->where('release_year', '>=', $request->input('minYear'));
        }
        if ($request->has('maxYear')) {
            $query->where('release_year', '<=', $request->input('maxYear'));
        }
        if ($request->has('minPage')) {
            $query->where('total_pages', '>=', $request->input('minPage'));
        }
        if ($request->has('maxPage')) {
            $query->where('total_pages', '<=', $request->input('maxPage'));
        }
        if ($request->has('sortByTitle')) {
            $sortDirection = $request->input('sortByTitle');
            $query->orderBy('title', $sortDirection);
        }

        $book = $query->get();


        // return new BooksResource(true, 'Search Results', $book);
        return response()->json(['category' => $id, 'books' => $book], 200);

    }
}
