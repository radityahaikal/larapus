<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use App\Book;
use Laratrust\LaratrustFacade as Laratrust;
use App\Author;


class GuestController extends Controller
{
   public function index(Request $request, Builder $htmlBuilder)
{
    if ($request->ajax()) {
        // 1. Gunakan join manual agar kolom author jadi kolom tabel biasa, 
        // ini cara paling ampuh untuk PostgreSQL/ Larapus
        $books = Book::join('authors', 'books.author_id', '=', 'authors.id')
            ->select([
                'books.id', 
                'books.title', 
                'books.amount', // Pastikan field stok kamu namanya 'amount'
                'authors.name as author_name' // Buat alias di sini
            ]);

        return Datatables::of($books)
            ->addColumn('action', function($book){
                if (\Laratrust::hasRole('admin')) return '';
                return '<a class="btn btn-xs btn-primary" href="'.route('guest.books.borrow', $book->id).'">Pinjam</a>';
            })
            // Tambahkan filter khusus untuk alias author_name
            ->filterColumn('author_name', function($query, $keyword) {
                $query->where('authors.name', 'ilike', "%$keyword%"); // Gunakan ilike untuk PostgreSQL
            })
            ->make(true);
    }

    $html = $htmlBuilder
        ->addColumn(['data' => 'title', 'name'=>'books.title', 'title'=>'Judul'])
        ->addColumn(['data' => 'amount', 'name'=>'books.amount', 'title'=>'Stok', 'orderable'=>false, 'searchable'=>false])
        // 2. 'data' panggil aliasnya, 'name' panggil kolom asli tabelnya
        ->addColumn(['data' => 'author_name', 'name'=>'authors.name', 'title'=>'Penulis'])
        ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'', 'orderable'=>false, 'searchable'=>false]);

    return view('guest.index')->with(compact('html'));
}
}