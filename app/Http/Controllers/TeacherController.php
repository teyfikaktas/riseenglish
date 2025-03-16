<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Tüm eğitmenleri listele
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teachers = User::role('ogretmen')
                       ->withCount('courses')
                       ->with(['courses' => function($query) {
                           $query->where('is_active', true)
                                 ->orderBy('created_at', 'desc')
                                 ->take(3);
                       }])
                       ->paginate(12);
        
        return view('teachers', compact('teachers'));
    }
    
    /**
     * Eğitmen detayını göster
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $teacher = User::role('ogretmen')
                      ->withCount('courses')
                      ->with(['courses' => function($query) {
                          $query->where('is_active', true)
                                ->orderBy('created_at', 'desc');
                      }])
                      ->findOrFail($id);
        
        return view('teacher-detail', compact('teacher'));
    }
}