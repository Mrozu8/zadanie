<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use External\Bar\Movies\MovieService as MoviesBar;
use External\Baz\Movies\MovieService as MoviesBaz;
use External\Foo\Movies\MovieService as MoviesFoo;

use External\Bar\Exceptions\ServiceUnavailableException as ExceptionBar;
use External\Baz\Exceptions\ServiceUnavailableException as ExceptionBaz;
use External\Foo\Exceptions\ServiceUnavailableException as ExceptionFoo;


class MovieController extends Controller
{
    public $titles = [];


    public function getTitles(Request $request): JsonResponse
    {
//      Mechanizm powtarzania

//        do {
//            $x = $this->getBarMovies();
//        } while ($x == false);
//
//        do {
//            $x = $this->getBazMovies();
//        } while ($x == false);
//
//        do {
//            $x = $this->getFooMovies();
//        } while ($x == false);


        if (!$this->getBarMovies() || !$this->getBazMovies() || !$this->getFooMovies()) {
            return response()->json(["status" => "failure"]);
        }

        return response()->json([$this->titles]);
    }

    protected function getBarMovies()
    {
        $barClass = new MoviesBar();

        try {
            $elements = $barClass->getTitles();

            foreach ($elements['titles'] as $element) {
                array_push($this->titles, $element['title']);
            }
            return true;
        } catch (ExceptionBar $e) {
            return false;
        }
    }

    protected function getBazMovies()
    {
        $bazClass = new MoviesBaz();

        try {
            $elements = $bazClass->getTitles();
            foreach ($elements['titles'] as $element) {
                array_push($this->titles, $element);
            }
            return true;
        } catch (ExceptionBaz $e) {
            return false;
        }
    }

    protected function getFooMovies()
    {
        $fooClass = new MoviesFoo();

        try {
            $elements = $fooClass->getTitles();
            foreach ($elements as $element) {
                array_push($this->titles, $element);
            }
            return true;
        } catch (ExceptionFoo $e) {
            return false;
        }
    }
}
