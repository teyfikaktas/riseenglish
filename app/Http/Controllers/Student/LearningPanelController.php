<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LearningPanelController extends Controller
{
    public function index()
    {
        return view('student.learning-panel.index');
    }
    
    public function questions()
    {
        return view('student.learning-panel.questions');
    }
}