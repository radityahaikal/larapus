<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Author;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Session;
use App\BorrowLog;
use App\Http\Controllers\Controller;

class StatisticsController extends Controller
{
    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
            $stats = BorrowLog::join('books', 'borrow_logs.book_id', '=', 'books.id')
                ->join('users', 'borrow_logs.user_id', '=', 'users.id')
                ->select([
                    'borrow_logs.*', 
                    'books.title as book_title', 
                    'users.name as user_name'
                ]);

            // --- TAMBAHKAN LOGIKA FILTER DI SINI ---
            return Datatables::of($stats)
                ->filter(function ($query) use ($request) {
                    // Cek apakah ada parameter status dari dropdown
                    if ($request->has('status')) {
                        if ($request->get('status') == 'returned') {
                            $query->where('is_returned', 1); // atau true tergantung tipe data
                        } elseif ($request->get('status') == 'not-returned') {
                            $query->where('is_returned', 0); // atau false
                        }
                    }
                })
                // ---------------------------------------
                ->addColumn('returned_at', function($stat){
                    if ($stat->is_returned) {
                        return $stat->updated_at->format('d/m/Y H:i'); // Tambah format biar cantik
                    }
                    return "Masih dipinjam";
                })
                ->make(true);
        }

        $html = $htmlBuilder
            ->addColumn(['data' => 'book_title', 'name'=>'books.title', 'title'=>'Judul'])
            ->addColumn(['data' => 'user_name', 'name'=>'users.name', 'title'=>'Peminjam'])
            ->addColumn(['data' => 'created_at', 'name'=>'borrow_logs.created_at', 'title'=>'Tanggal Pinjam', 'searchable'=>false])
            ->addColumn(['data' => 'returned_at', 'name'=>'returned_at', 'title'=>'Tanggal Kembali','orderable'=>false, 'searchable'=>false]);

        return view('statistics.index', compact('html'));
    }
}