<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class AdminGroupController extends Controller
{
    /**
     * Grupları listele
     */
    public function index()
    {
        $groups = Group::with(['teacher', 'students'])
            ->withCount('students')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.groups.index', compact('groups'));
    }

    /**
     * Yeni grup oluşturma formu
     */
    public function create()
    {
        $teachers = User::role('ogretmen')->get();
        return view('admin.groups.create', compact('teachers'));
    }

    /**
     * Yeni grup kaydet
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $group = Group::create([
            'name' => $request->name,
            'description' => $request->description,
            'teacher_id' => $request->teacher_id,
            'is_active' => true,
        ]);

        // AJAX isteği ise JSON döndür
        if ($request->ajax() || $request->wantsJson()) {
            $group->load('teacher');
            
            return response()->json([
                'success' => true,
                'group' => [
                    'id' => $group->id,
                    'name' => $group->name,
                    'teacher_name' => $group->teacher ? $group->teacher->name : null,
                ]
            ]);
        }

        return redirect()->route('admin.groups.index')
            ->with('success', 'Grup başarıyla oluşturuldu.');
    }

    /**
     * Grup detayı
     */
    public function show(Group $group)
    {
        $group->load(['teacher', 'students']);
        
        // Gruba dahil olmayan aktif öğrenciler
        $availableStudents = User::role('ogrenci')
            ->whereDoesntHave('groups', function($query) use ($group) {
                $query->where('groups.id', $group->id);
            })
            ->orderBy('name')
            ->get();
        
        return view('admin.groups.show', compact('group', 'availableStudents'));
    }

    /**
     * Grup düzenleme formu
     */
    public function edit(Group $group)
    {
        $teachers = User::role('ogretmen')->get();
        return view('admin.groups.edit', compact('group', 'teachers'));
    }

    /**
     * Grubu güncelle
     */
    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'teacher_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ]);

        $group->update([
            'name' => $request->name,
            'description' => $request->description,
            'teacher_id' => $request->teacher_id,
            'is_active' => $request->is_active ?? $group->is_active,
        ]);

        return redirect()->route('admin.groups.show', $group)
            ->with('success', 'Grup başarıyla güncellendi.');
    }

    /**
     * Grubu sil
     */
    public function destroy(Group $group)
    {
        $group->delete();
        
        return redirect()->route('admin.groups.index')
            ->with('success', 'Grup başarıyla silindi.');
    }

    /**
     * Gruba öğrenci ekle
     */
    public function addStudent(Request $request, Group $group)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $group->students()->attach($request->user_id, [
            'joined_at' => now()
        ]);

        return back()->with('success', 'Öğrenci gruba başarıyla eklendi.');
    }

    /**
     * Gruptan öğrenci çıkar
     */
    public function removeStudent(Group $group, User $user)
    {
        $group->students()->detach($user->id);
        
        return back()->with('success', 'Öğrenci gruptan çıkarıldı.');
    }
}