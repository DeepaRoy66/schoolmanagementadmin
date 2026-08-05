<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\TimetableImage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TimetableImageController extends Controller
{
    /**
     * Latest image per class-section, plus how many versions exist.
     */
    public function index(): View
    {
        $latestPerClassSection = TimetableImage::with(['schoolClass:id,name', 'section:id,name'])
            ->where('school_id', auth()->user()->school_id)
            ->latest()
            ->get()
            ->unique(fn ($t) => $t->class_id . '-' . $t->section_id)
            ->values();

        return view('school-admin.timetable-images.index', compact('latestPerClassSection'));
    }

    public function create(): View
    {
        $classes = SchoolClass::with('sections')->orderBy('name')->get();

        return view('school-admin.timetable-images.create', compact('classes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'image' => 'required|file|max:10240|mimes:jpg,jpeg,png,pdf',
        ]);

        $file = $request->file('image');
        $path = $file->store('timetables', 'public');

        TimetableImage::create([
            'school_id' => auth()->user()->school_id,
            'class_id' => $validated['class_id'],
            'section_id' => $validated['section_id'],
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()->route('school-admin.timetable-images.index')
            ->with('status', 'Timetable image uploaded.');
    }

    /**
     * View full upload history for one class-section.
     */
    public function history(int $classId, int $sectionId): View
    {
        $images = TimetableImage::with(['schoolClass:id,name', 'section:id,name'])
            ->where('school_id', auth()->user()->school_id)
            ->where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->latest()
            ->get();

        return view('school-admin.timetable-images.history', compact('images'));
    }

    public function destroy(TimetableImage $timetableImage): RedirectResponse
    {
        \Illuminate\Support\Facades\Storage::disk('public')->delete($timetableImage->file_path);
        $timetableImage->delete();

        return redirect()->back()->with('status', 'Old upload removed.');
    }
}