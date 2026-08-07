<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GalleryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = Gallery::with(['schoolClass:id,name', 'uploader:id,name'])
            ->where('school_id', $user->school_id)
            ->latest();

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->query('class_id'));
        }

        if ($user->role === 'student' && !$request->filled('class_id')) {
            $student = \App\Models\Student::where('user_id', $user->id)->first();
            if ($student) {
                $query->where('class_id', $student->class_id);
            }
        }

        $photos = $query->paginate(20);

        return response()->json($photos);
    }

    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'student') {
            return response()->json(['message' => 'You are not authorized to upload photos.'], 403);
        }

        $validated = $request->validate([
            'class_id' => 'nullable|exists:classes,id',
            'caption' => 'nullable|string|max:255',
            'image' => 'required_without:video|nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'video' => 'required_without:image|nullable|mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/webm|max:204800',
        ]);

        // student ko aphno class_id auto-assign garne, unless explicitly nil pathaएको cha
        if (empty($validated['class_id'])) {
            $student = \App\Models\Student::where('user_id', $user->id)->first();
            $validated['class_id'] = $student->class_id ?? null;
        }

        if ($request->hasFile('video')) {
            $path = $request->file('video')->store('gallery/videos', 'public');
            $mediaType = 'video';
        } else {
            $path = $request->file('image')->store('gallery/images', 'public');
            $mediaType = 'image';
        }

        $gallery = Gallery::create([
            'school_id' => $user->school_id,
            'class_id' => $validated['class_id'] ?? null,
            'uploaded_by' => $user->id,
            'caption' => $validated['caption'] ?? null,
            'image_path' => $path,
            'media_type' => $mediaType,
        ]);

        return response()->json([
            'message' => 'Media uploaded successfully.',
            'gallery' => $gallery,
        ], 201);
    }

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