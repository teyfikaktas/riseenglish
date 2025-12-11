<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentApprovalController extends Controller
{
    public function index()
    {
        // Onay bekleyen öğrenciler (sadece ogrenci rolü olanlar)
        $pendingStudents = User::whereHas('roles', function($query) {
                $query->where('name', 'ogrenci');
            })
            ->where('teacher_approved', false)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Onaylanmış öğrenciler
        $approvedStudents = User::whereHas('roles', function($query) {
                $query->where('name', 'ogrenci');
            })
            ->where('teacher_approved', true)
            ->orderBy('approved_at', 'desc')
            ->paginate(20);
        
        return view('teacher.student-approvals', compact('pendingStudents', 'approvedStudents'));
    }
    
    public function approve($id)
    {
        $student = User::findOrFail($id);
        
        $student->teacher_approved = true;
        $student->approved_at = Carbon::now();
        $student->approved_by = Auth::id();
        $student->save();
        
        // TODO: SMS ile bildirim gönder
        
        return back()->with('success', $student->name . ' onaylandı!');
    }
}