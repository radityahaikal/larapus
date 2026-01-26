<?php

use Illuminate\Database\Seeder;
use App\Author;
use App\Book;

class BooksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Sample Penulis
        $author1 = Author::create(['name' => 'Ali']);
        $author2 = Author::create(['name' => 'Budi']);
        $author3 = Author::create(['name' => 'Citra']);

        //Sample Buku
        $book1 = Book::create(['title'=>'Tutorial Adobe Illustrator','amount'=>3, 'author_id'=>$author1->id]);
        $book2 = Book::create(['title'=>'Mengenal Laravel','amount'=>4, 'author_id'=>$author2->id]);
        $book3 = Book::create(['title'=>'Panduan Photoshop CS6','amount'=>2, 'author_id'=>$author3->id]);
        $book4 = Book::create(['title'=>'Mahir Codeigniter','amount'=>5, 'author_id'=>$author2->id]);

        //Sample Peminjaman Buku
        $member = User::where('email', 'member@gmail.com')->first();
        BorrowLog::create(['user_id' => $member->id, 'book_id'=>$book1->id, 'is_returned' => 0]);
        BorrowLog::create(['user_id' => $member->id, 'book_id'=>$book2->id, 'is_returned' => 0]);
        BorrowLog::create(['user_id' => $member->id, 'book_id'=>$book3->id, 'is_returned' => 1]);
    }
}
