<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    /**
     * Teacher: aafule upload gareko sabai notes/files list garne
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'teacher') {
            return response()->json(['message' => 'Only teachers can access this.'], 403);
        }

        $teacher = Teacher::where('user_id', $user->id)->first();

        $materials = Material::where('teacher_id', $teacher->id)
            ->latest()
            ->get();

        $materials->each(function ($m) {
            $m->file_url = asset('storage/' . $m->file_path);
        });

        return response()->json($materials);
    }

    /**
     * Teacher: naya file/note upload garne
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'teacher') {
            return response()->json(['message' => 'Only teachers can upload notes.'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'class' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png',
        ]);

        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            return response()->json(['message' => 'Teacher profile not found.'], 404);
        }

        $file = $request->file('file');
        $path = $file->store('materials', 'public');

        $material = Material::create([
            'school_id' => $user->school_id,
            'teacher_id' => $teacher->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'class' => $validated['class'] ?? null,
            'subject' => $validated['subject'] ?? null,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
        ]);

        $material->file_url = asset('storage/' . $path);

        return response()->json([
            'message' => 'Note uploaded successfully.',
            'material' => $material,
        ], 201);
    }

    /**
     * Teacher: euta note/file delete garne
     */
    public function destroy(Request $request, Material $material): JsonResponse
    {
        $user = $request->user();
        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher || $material->teacher_id !== $teacher->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        Storage::disk('public')->delete($material->file_path);
        $material->delete();

        return response()->json(['message' => 'Note deleted.']);
    }

    /**
     * Student: aafno class ko notes/files herne
     */
    public function myMaterials(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'student') {
            return response()->json(['message' => 'Only students can access this.'], 403);
        }

        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json(['message' => 'Student profile not found.'], 404);
        }

        $materials = Material::where('school_id', $user->school_id)
            ->where('class', $student->class)
            ->latest()
            ->get(['id', 'title', 'description', 'subject', 'file_path', 'file_name', 'created_at']);

        $materials->each(function ($m) {
            $m->file_url = asset('storage/' . $m->file_path);
        });

        return response()->json($materials);
    }
}