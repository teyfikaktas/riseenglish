<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupDailyReportController extends Controller
{
    public function index()
    {
        $groups = Group::where('is_active', true)
            ->with(['students', 'teacher'])
            ->withCount('students')
            ->orderBy('name')
            ->get();

        return view('reports.group-daily', compact('groups'));
    }
}