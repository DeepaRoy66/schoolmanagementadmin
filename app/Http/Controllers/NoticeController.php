<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;

class NoticeController extends Controller
{
    public function index(): View
    {
        $notices = Notice::with('targets.schoolClass', 'targets.section')
            ->latest()
            ->paginate(10);

        return view('school-admin.notices.index', compact('notices'));
    }

    public function create(): View
    {
        $classes = SchoolClass::with('sections')
            ->where('school_id', auth()->user()->school_id)
            ->get();

        return view('school-admin.notices.create', compact('classes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'target_type' => 'required|in:all,teacher,student,class_specific',
            'targets' => 'required_if:target_type,class_specific|array',
            'targets.*.class_id' => 'required_with:targets|exists:classes,id',
            'targets.*.section_id' => 'nullable|exists:sections,id',
        ]);

        $notice = Notice::create([
            'school_id' => auth()->user()->school_id,
            'posted_by' => auth()->id(),
            'title' => $validated['title'],
            'message' => $validated['message'],
            'target_type' => $validated['target_type'],
        ]);

        if ($validated['target_type'] === 'class_specific' && !empty($validated['targets'])) {
            foreach ($validated['targets'] as $target) {
                $notice->targets()->create([
                    'class_id' => $target['class_id'],
                    'section_id' => $target['section_id'] ?? null,
                ]);
            }
        }

        $this->sendPushNotification($notice);

        return redirect()->route('school-admin.notices.index')
            ->with('status', 'Notice posted successfully.');
    }

    public function destroy(Notice $notice): RedirectResponse
    {
        $notice->delete();

        return redirect()->route('school-admin.notices.index')
            ->with('status', 'Notice deleted.');
    }

    /**
     * Notice ko target_type anusar sahi users ko OneSignal player_id haru nikalne.
     */
    private function getTargetPlayerIds(Notice $notice): array
    {
        $query = User::where('school_id', $notice->school_id)
            ->whereNotNull('onesignal_player_id');

        if ($notice->target_type === 'teacher') {
            $query->where('role', 'teacher');
        } elseif ($notice->target_type === 'student') {
            $query->where('role', 'student');
        } elseif ($notice->target_type === 'class_specific') {
            $notice->loadMissing('targets');

            $classIds = $notice->targets->pluck('class_id')->unique()->values();
            $sectionIds = $notice->targets->pluck('section_id')->filter()->unique()->values();

            $query->where('role', 'student')
                ->whereHas('student', function ($q) use ($classIds, $sectionIds) {
                    $q->whereIn('class_id', $classIds)
                      ->where(function ($q2) use ($sectionIds) {
                          $q2->whereNull('section_id')
                             ->orWhereIn('section_id', $sectionIds);
                      });
                });
        }
        // target_type == 'all' bhaye kunai extra filter chaindaina (school ko sabai user)

        return $query->pluck('onesignal_player_id')->toArray();
    }

    /**
     * OneSignal REST API bata push notification pathaune.
     */
    private function sendPushNotification(Notice $notice): void
    {
        $playerIds = $this->getTargetPlayerIds($notice);

        if (empty($playerIds)) {
            return;
        }

        Http::withHeaders([
            'Authorization' => 'Basic ' . config('services.onesignal.rest_api_key'),
            'Content-Type' => 'application/json',
        ])->post('https://onesignal.com/api/v1/notifications', [
            'app_id' => config('services.onesignal.app_id'),
            'include_player_ids' => $playerIds,
            'headings' => ['en' => $notice->title],
            'contents' => ['en' => $notice->message],
            'data' => ['notice_id' => $notice->id],
        ]);
    }
}