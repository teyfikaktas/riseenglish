<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $featuredCourses = Course::where('is_featured', true)
                                ->where('is_active', true)
                                ->orderBy('display_order')
                                ->with(['teacher', 'category'])
                                ->take(100)
                                ->get();
        
        return view('welcome', compact('featuredCourses'));
    }
}