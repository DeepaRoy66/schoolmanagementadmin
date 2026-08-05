<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GalleryController extends Controller
{
    /**
     * Class wise gallery photos fetch garne.
     * ?class_id=5 pathaye tyo class ko matra, natra sabai school ko photos.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = Gallery::with(['schoolClass:id,name', 'uploader:id,name'])
            ->where('school_id', $user->school_id)
            ->latest();

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->query('class_id'));
        }

        // Student login vaeko bhaye aafno class ko matra dekhaune (agar class_id specify garena bhane)
        if ($user->role === 'student' && !$request->filled('class_id')) {
            $student = \App\Models\Student::where('user_id', $user->id)->first();
            if ($student) {
                $query->where('class_id', $student->class_id);
            }
        }

        $photos = $query->paginate(20);

        return response()->json($photos);
    }

    /**
     * Naya photo upload garne (Teacher / School Admin matra).
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!in_array($user->role, ['teacher', 'school_admin'])) {
            return response()->json(['message' => 'You are not authorized to upload photos.'], 403);
        }

        $validated = $request->validate([
            'class_id' => 'nullable|exists:classes,id',
            'caption' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $path = $request->file('image')->store('gallery', 'public');

        $gallery = Gallery::create([
            'school_id' => $user->school_id,
            'class_id' => $validated['class_id'] ?? null,
            'uploaded_by' => $user->id,
            'caption' => $validated['caption'] ?? null,
            'image_path' => $path,
        ]);

        return response()->json([
            'message' => 'Photo uploaded successfully.',
            'gallery' => $gallery,
        ], 201);
    }

    /**
     * Photo delete garne (uploader afai ya school_admin matra).
     */
    public function destroy(Request $request, Gallery $gallery): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'school_admin' && $gallery->uploaded_by !== $user->id) {
            return response()->json(['message' => 'You are not authorized to delete this photo.'], 403);
        }

        \Illuminate\Support\Facades\Storage::disk('public')->delete($gallery->image_path);
        $gallery->delete();

        return response()->json(['message' => 'Photo deleted successfully.']);
    }
}