<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GalleryController extends Controller
{
    /**
     * List gallery photos/videos for the school admin's school.
     * View-only + moderate (delete). Upload is not available here —
     * teachers and students upload from the mobile app (POST /api/gallery).
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = Gallery::with(['schoolClass:id,name', 'uploader:id,name,role'])
            ->where('school_id', $user->school_id)
            ->latest();

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->query('class_id'));
        }

        if ($request->filled('media_type')) {
            $query->where('media_type', $request->query('media_type'));
        }

        if ($request->filled('role')) {
            $role = $request->query('role');
            $query->whereHas('uploader', function ($q) use ($role) {
                $q->where('role', $role);
            });
        }

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('caption', 'like', "%{$search}%")
                  ->orWhereHas('uploader', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $gallery = $query->paginate(24)->withQueryString();

        // Old records uploaded before video support existed may have a null
        // media_type — treat those as photos so the counts stay accurate.
        $photoCount = Gallery::where('school_id', $user->school_id)
            ->where(function ($q) {
                $q->where('media_type', 'image')->orWhereNull('media_type');
            })->count();

        $videoCount = Gallery::where('school_id', $user->school_id)
            ->where('media_type', 'video')
            ->count();

        $classes = \App\Models\SchoolClass::where('school_id', $user->school_id)->get();

        return view('school-admin.gallery.index', compact('gallery', 'photoCount', 'videoCount', 'classes'));
    }

    /**
     * Delete a photo/video. School admin may delete any item in their school
     * (moderation), regardless of who uploaded it.
     */
    public function destroy(Request $request, Gallery $gallery): RedirectResponse
    {
        $user = $request->user();

        if ($gallery->school_id !== $user->school_id) {
            abort(403);
        }

        Storage::disk('public')->delete($gallery->image_path);
        $gallery->delete();

        return redirect()
            ->route('school-admin.gallery.index')
            ->with('success', 'Photo deleted successfully.');
    }
}