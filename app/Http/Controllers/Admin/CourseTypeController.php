<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseTypeController extends Controller
{
    /**
     * Kurs tipi listesini göster
     */
    public function index()
    {
        $courseTypes = CourseType::orderBy('name')->paginate(10);
        return view('admin.course_types.index', compact('courseTypes'));
    }

    /**
     * Yeni kurs tipi oluşturma formunu göster
     */
    public function create()
    {
        return view('admin.course_types.create');
    }

    /**
     * Yeni kurs tipini kaydet
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:course_types',
            'description' => 'nullable|max:255',
            'is_active' => 'boolean',
        ]);

        CourseType::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.course-types.index')
            ->with('success', 'Kurs tipi başarıyla oluşturuldu.');
    }

    /**
     * Kurs tipi düzenleme formunu göster
     */
    public function edit(CourseType $courseType)
    {
        return view('admin.course_types.edit', compact('courseType'));
    }

    /**
     * Kurs tipini güncelle
     */
    public function update(Request $request, CourseType $courseType)
    {
        $request->validate([
            'name' => 'required|max:255|unique:course_types,name,' . $courseType->id,
            'description' => 'nullable|max:255',
            'is_active' => 'boolean',
        ]);

        $courseType->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.course-types.index')
            ->with('success', 'Kurs tipi başarıyla güncellendi.');
    }

    /**
     * Kurs tipini sil
     */
    public function destroy(CourseType $courseType)
    {
        // İlişkili kursları kontrol etmek güvenli olur
        $courseCount = $courseType->courses()->count();
        
        if ($courseCount > 0) {
            return redirect()->route('admin.course-types.index')
                ->with('error', 'Bu kurs tipine bağlı '.$courseCount.' kurs bulunmaktadır. Silmeden önce kursları başka bir tipe taşıyın.');
        }
        
        $courseType->delete();

        return redirect()->route('admin.course-types.index')
            ->with('success', 'Kurs tipi başarıyla silindi.');
    }
    public function getList()
{
    $types = CourseType::select('id', 'name')->orderBy('name')->get();
    return response()->json($types);
}
}