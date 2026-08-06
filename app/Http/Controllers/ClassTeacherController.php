<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\ClassTeacher;
use App\Models\SchoolClass;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassTeacherController extends Controller
{
    /**
     * Show the "Assign Class Teacher" page.
     */
    public function index()
    {
        $classes = SchoolClass::with('sections')->orderBy('name')->get();
        $teachers = Teacher::orderBy('full_name')->get();
        $assignments = ClassTeacher::with(['schoolClass', 'section', 'teacher'])->get();

        return view('school-admin.class-teacher.index', compact('classes', 'teachers', 'assignments'));
    }

    /**
     * Assign a teacher as the class teacher of a class/section.
     *
     * Rules:
     *  - Euta section (within a class) ma euta matra class teacher hunuparcha.
     *    Pahile arko teacher assigned vaye, replace garincha.
     *  - Euta teacher euta matra class/section ko class teacher hunuparcha.
     *    Pahile arko class/section ma assigned vaye, tyaha bata hataera
     *    (move) yaha assign garincha.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'class_id'   => ['required', 'exists:school_classes,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
        ]);

        // TODO: yedi section euta class ko matra ho vane, yo relation check
        // thap garnus taki arko class ko section id nadinos:
        // $validated['section_id'] lai $validated['class_id'] sanga belongsTo confirm garnus.

        DB::transaction(function () use ($validated) {
            // 1) Yo teacher pahile kunai arko class/section ko class teacher
            //    thiyo vane, tyo purano assignment hataune (move).
            ClassTeacher::where('teacher_id', $validated['teacher_id'])
                ->where(function ($q) use ($validated) {
                    $q->where('class_id', '!=', $validated['class_id'])
                      ->orWhere('section_id', '!=', $validated['section_id']);
                })
                ->delete();

            // 2) Yo class/section ko lagi pahile kunai arko teacher assigned
            //    thiyo vane, tyo replace garne.
            ClassTeacher::updateOrCreate(
                [
                    'class_id'   => $validated['class_id'],
                    'section_id' => $validated['section_id'],
                ],
                [
                    'teacher_id' => $validated['teacher_id'],
                ]
            );
        });

        return redirect()
            ->route('school-admin.class-teacher.index')
            ->with('success', 'Class teacher assigned successfully.');
    }

    /**
     * Remove a class teacher assignment.
     */
    public function remove(ClassTeacher $classTeacher): RedirectResponse
    {
        $classTeacher->delete();

        return redirect()
            ->route('school-admin.class-teacher.index')
            ->with('success', 'Class teacher removed successfully.');
    }
}