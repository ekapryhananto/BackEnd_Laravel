<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Books;
use App\Http\Resources\BooksResource;
use Illuminate\Support\Facades\Validator;

class BooksController extends Controller
{
    public function index(){
        $book = Books::oldest()->get();

        return new BooksResource(true, 'List Data Book', $book);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'image_url' =>  'required',
            'release_year' => 'required|integer|min:1980|max:2021',
            'price' => 'required',
            'total_page' => 'required',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $book = Books::create([
            'title' => $request->title,
            'description' => $request->description,
            'image_url' =>  $request->image_url,
            'release_year' => $request->release_year,
            'price' => $request->price,
            'total_page' => $request->total_page,
            'thickness' => $this->calculateThickness($request->total_page),
            'category_id' => $request->category_id,
        ]);

        return new BooksResource(true, 'Data Books Berhasil Ditambah', $book);
    }

    private function calculateThickness($totalPage){
        if ($totalPage <= 100) {
            return 'tipis';
        } elseif ($totalPage >= 101 && $totalPage <= 200 ){
            return 'sedang';
        } else {
            return 'tebal';
        }
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'image_url' =>  'required',
            'release_year' => 'required|integer|min:1980|max:2021',
            'price' => 'required',
            'total_page' => 'required',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $book = Books::find($id);
        $book->update([
            'title' => $request->title,
            'description' => $request->description,
            'image_url' =>  $request->image_url,
            'release_year' => $request->release_year,
            'price' => $request->price,
            'total_page' => $request->total_page,
            'thickness' => $this->calculateThickness($request->total_page),
            'category_id' => $request->category_id,
        ]);

        return new BooksResource(true, 'Data Berhasil Diubah', $book);
    }

    public function destroy($id){
        $book = Books::find($id);
        $book->delete();

        return new BooksResource(true, 'Data Berhasil diHapus', $book);
    }

    public function filter(Request $request){
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

        $query = Books::query();

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

        return new BooksResource(true, 'Search Results', $book);

    }
}
