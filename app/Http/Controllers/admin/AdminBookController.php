<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class AdminBookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Book::all();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:100',
            'isbn' => 'required|max:100',
            'author' => 'required|max:100'
        ]);
        $min = 1;
        $max = 100;
        return $book = Book::create(
            [
                'title' => $request->title,
                'isbn' => $request->isbn,
                'author' => $request->author,
                'price' =>  mt_rand ($min*10, $max*10) / 10,
                'quantity' => rand($min,$max)
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(book $book)
    {
        return $book;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, book $book)
    {
        // $book->find($)
        $book ->update($request->all());
        $book->save();
        return redirect('/api/books/'.$book->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(book $book)
    {
        Book::destroy($book->id);
        return Book::All();
    }

}
