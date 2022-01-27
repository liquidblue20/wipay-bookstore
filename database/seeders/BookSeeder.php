<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $max = 100;
        $min = 1;
        $books = [
            ['author' => 'auth1',
            'title' => 'title1',
            'isbn' => '123321',
            'quantity' => rand($min,$max),
            'price' => mt_rand ($min*10, $max*10) / 10
            ],
            ['author' => 'auth2',
            'title' => 'title2',
            'isbn' => '234432',
            'quantity' => rand(),
            'price' => mt_rand ($min*10, $max*10) / 10
            ],['author' => 'auth3',
            'title' => 'title3',
            'isbn' => '345543',
            'quantity' => rand(),
            'price' => mt_rand ($min*10, $max*10) / 10
            ],['author' => 'auth4',
            'title' => 'title4',
            'isbn' => '456654',
            'quantity' => rand(),
            'price' => mt_rand ($min*10, $max*10) / 10
            ]
        ];
        foreach ($books as $book) {
            Book::create($book);
    }
}
}
