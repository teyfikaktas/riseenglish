<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LearningPanelController extends Controller
{
    public function index()
    {
        // Token oluştur ve view'a gönder
        $token = auth()->user()->createToken('game-token')->plainTextToken;
        
        return view('student.learning-panel.index', [
            'token' => $token
        ]);
    }
    
    public function questions()
    {
        // Token oluştur ve view'a gönder
        $token = auth()->user()->createToken('game-token')->plainTextToken;
        
        return view('student.learning-panel.questions', [
            'token' => $token
        ]);
    }
}