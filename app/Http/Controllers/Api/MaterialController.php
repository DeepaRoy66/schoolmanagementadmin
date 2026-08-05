<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClassTeacherAssignment;
use App\Models\Material;
use App\Models\MaterialFile;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MaterialController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'teacher') {
            return response()->json(['message' => 'Only teachers can access this.'], 403);
        }

        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            return response()->json(['message' => 'Teacher profile not found.'], 404);
        }

        $materials = Material::where('teacher_id', $teacher->id)
            ->with(['schoolClass:id,name', 'files'])
            ->latest()
            ->get();

        $materials->each(function ($m) {
            $m->class_name = $m->schoolClass?->name;

            if ($m->file_path) {
                $m->file_url = asset('storage/' . $m->file_path);
            }

            $m->files->each(function ($f) {
                $f->file_url = asset('storage/' . $f->file_path);
            });
        });

        return response()->json($materials);
    }

   
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'teacher') {
            return response()->json(['message' => 'Only teachers can upload notes.'], 403);
        }

        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            return response()->json(['message' => 'Teacher profile not found.'], 404);
        }

        
        $assignedClassIds = ClassTeacherAssignment::where('teacher_id', $teacher->id)
            ->pluck('class_id')
            ->unique()
            ->values();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'class_id' => [
                'required',
                Rule::in($assignedClassIds),
            ],
            'subject' => 'nullable|string|max:255',
            'files' => 'required|array|min:1',
            'files.*' => 'file|max:10240|mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png',
        ], [
            'class_id.in' => 'You are not assigned to this class.',
        ]);

        $material = Material::create([
            'school_id' => $user->school_id,
            'teacher_id' => $teacher->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'class_id' => $validated['class_id'],
            'subject' => $validated['subject'] ?? null,
        ]);

        foreach ($request->file('files') as $file) {
            $path = $file->store('materials', 'public');

            MaterialFile::create([
                'material_id' => $material->id,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
            ]);
        }

        $material->load(['schoolClass:id,name', 'files']);
        $material->class_name = $material->schoolClass?->name;
        $material->files->each(function ($f) {
            $f->file_url = asset('storage/' . $f->file_path);
        });

        return response()->json([
            'message' => 'Note uploaded successfully.',
            'material' => $material,
        ], 201);
    }

    
    public function destroy(Request $request, Material $material): JsonResponse
    {
        $user = $request->user();
        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher || $material->teacher_id !== $teacher->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        foreach ($material->files as $file) {
            Storage::disk('public')->delete($file->file_path);
            $file->delete();
        }

        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return response()->json(['message' => 'Note deleted.']);
    }

    
    public function myMaterials(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'student') {
            return response()->json(['message' => 'Only students can access this.'], 403);
        }

        $student = Student::with('schoolClass:id,name')->where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json(['message' => 'Student profile not found.'], 404);
        }

        $materials = Material::where('school_id', $user->school_id)
            ->where('class_id', $student->class_id)
            ->with(['schoolClass:id,name', 'files'])
            ->latest()
            ->get();

        $materials->each(function ($m) {
            $m->class_name = $m->schoolClass?->name;

            if ($m->file_path) {
                $m->file_url = asset('storage/' . $m->file_path);
            }

            $m->files->each(function ($f) {
                $f->file_url = asset('storage/' . $f->file_path);
            });
        });

        return response()->json([
            'class_id' => $student->class_id,
            'class_name' => $student->schoolClass?->name,
            'materials' => $materials,
        ]);
    }
}