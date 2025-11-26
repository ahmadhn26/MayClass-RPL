<?php

namespace App\Http\Controllers\Tutor;

use App\Models\Package;
use App\Models\Quiz;
use App\Models\Subject;
use App\Models\QuizLevel;
use App\Models\QuizTakeaway;
use App\Support\UnsplashPlaceholder;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class QuizController extends BaseTutorController
{
    public function index(Request $request)
    {
        $search = (string) $request->input('q', '');

        $tableReady = Schema::hasTable('quizzes');

        $quizzes = $tableReady
            ? Quiz::query()
                ->with('subject')
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($inner) use ($search) {
                        $inner->where('title', 'like', "%{$search}%")
                            ->orWhereHas('subject', function ($q) use ($search) {
                                $q->where('name', 'like', "%{$search}%");
                            })
                            ->orWhere('class_level', 'like', "%{$search}%");
                    });
                })
                ->orderByDesc('created_at')
                ->get()
            : collect();

        // AMBIL DATA PAKET UNTUK DROPDOWN MODAL (hanya paket tutor ini)
        $packages = Schema::hasTable('packages')
            ? \Illuminate\Support\Facades\Auth::user()->packages()->orderBy('level')->orderBy('price')->get()
            : collect();

        return $this->render('tutor.quizzes.index', [
            'quizzes' => $quizzes,
            'search' => $search,
            'tableReady' => $tableReady,
            'packages' => $packages, // Kirim ke view
        ]);
    }

    // Method create tidak dipakai lagi (pakai modal), tapi dibiarkan jika ingin fallback
    public function create()
    {
        $packages = Schema::hasTable('packages')
            ? \Illuminate\Support\Facades\Auth::user()->packages()->orderBy('level')->orderBy('price')->get()
            : collect();

        return $this->render('tutor.quizzes.create', [
            'packages' => $packages,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!Schema::hasTable('quizzes')) {
            return redirect()
                ->route('tutor.quizzes.index')
                ->with('alert', __('Tabel quiz belum siap. Jalankan migrasi database terlebih dahulu.'));
        }

        if (!Schema::hasTable('packages')) {
            return redirect()
                ->route('tutor.quizzes.index')
                ->with('alert', __('Tabel paket belum siap. Pastikan migrasi paket sudah dijalankan.'));
        }

        // DEBUG: Log data yang masuk
        Log::info('=== QUIZ STORE ATTEMPT ===');
        Log::info('Request Data:', $request->all());

        $data = $request->validate([
            'package_id' => ['required', 'exists:packages,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'title' => ['required', 'string', 'max:255'],
            'class_level' => ['required', 'string', 'max:120'],
            'summary' => ['required', 'string'],
            'link_url' => ['required', 'url', 'max:255'],
            'duration_label' => ['required', 'string', 'max:120'],
            'question_count' => ['required', 'integer', 'min:1', 'max:200'],
            'levels' => ['nullable', 'array'],
            'levels.*' => ['nullable', 'string', 'max:120'],
            'takeaways' => ['nullable', 'array'],
            'takeaways.*' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            // Validasi sederhana: pastikan subject exists
            $subject = Subject::find($data['subject_id']);

            if (!$subject) {
                Log::warning('Subject tidak ditemukan', [
                    'subject_id' => $data['subject_id']
                ]);

                return back()
                    ->withErrors(['subject_id' => 'Mata pelajaran tidak ditemukan.'])
                    ->withInput();
            }

            $slug = Str::slug($data['title']) ?: 'quiz-' . Str::random(6);
            $uniqueSlug = $slug;
            $counter = 1;
            while (Quiz::where('slug', $uniqueSlug)->exists()) {
                $uniqueSlug = $slug . '-' . $counter++;
            }

            DB::transaction(function () use ($data, $request, $uniqueSlug, $subject) {
                $quiz = Quiz::create([
                    'slug' => $uniqueSlug,
                    'package_id' => $data['package_id'],
                    'subject_id' => $data['subject_id'],
                    'class_level' => $data['class_level'],
                    'title' => $data['title'],
                    'summary' => $data['summary'],
                    'link_url' => $data['link_url'],
                    'thumbnail_url' => UnsplashPlaceholder::quiz($subject->name ?? 'Quiz'),
                    'duration_label' => $data['duration_label'],
                    'question_count' => $data['question_count'],
                ]);

                Log::info('Quiz created successfully:', ['quiz_id' => $quiz->id]);

                $this->syncLevels($quiz, $request->input('levels', []));
                $this->syncTakeaways($quiz, $request->input('takeaways', []));
            });

            return redirect()
                ->route('tutor.quizzes.index')
                ->with('status', __('Quiz baru berhasil dibuat.'));

        } catch (\Exception $e) {
            Log::error('Quiz Store Error: ' . $e->getMessage());
            Log::error('Stack Trace: ' . $e->getTraceAsString());

            return back()
                ->withErrors(['error' => 'Gagal menyimpan quiz: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function edit(Quiz $quiz)
    {
        $quiz->load(['levels', 'takeaways']);

        $packages = Schema::hasTable('packages')
            ? \Illuminate\Support\Facades\Auth::user()->packages()->orderBy('level')->orderBy('price')->get()
            : collect();

        return $this->render('tutor.quizzes.edit', [
            'quiz' => $quiz,
            'packages' => $packages,
        ]);
    }

    public function update(Request $request, Quiz $quiz): RedirectResponse
    {
        if (!Schema::hasTable('quizzes')) {
            return redirect()
                ->route('tutor.quizzes.index')
                ->with('alert', __('Tabel quiz belum siap. Jalankan migrasi database terlebih dahulu.'));
        }

        if (!Schema::hasTable('packages')) {
            return redirect()
                ->route('tutor.quizzes.index')
                ->with('alert', __('Tabel paket belum siap. Pastikan migrasi paket sudah dijalankan.'));
        }

        $data = $request->validate([
            'package_id' => ['required', 'exists:packages,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'title' => ['required', 'string', 'max:255'],
            'class_level' => ['required', 'string', 'max:120'],
            'summary' => ['required', 'string'],
            'link_url' => ['required', 'url', 'max:255'],
            'duration_label' => ['required', 'string', 'max:120'],
            'question_count' => ['required', 'integer', 'min:1', 'max:200'],
            'levels' => ['nullable', 'array'],
            'levels.*' => ['nullable', 'string', 'max:120'],
            'takeaways' => ['nullable', 'array'],
            'takeaways.*' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            // Validasi subject
            $subject = Subject::find($data['subject_id']);

            if (!$subject) {
                return back()
                    ->withErrors(['subject_id' => 'Mata pelajaran tidak ditemukan.'])
                    ->withInput();
            }

            $payload = [
                'package_id' => $data['package_id'],
                'subject_id' => $data['subject_id'],
                'class_level' => $data['class_level'],
                'title' => $data['title'],
                'summary' => $data['summary'],
                'link_url' => $data['link_url'],
                'duration_label' => $data['duration_label'],
                'question_count' => $data['question_count'],
            ];

            if ($quiz->subject_id !== $data['subject_id']) {
                $subjectName = Subject::find($data['subject_id'])?->name ?? 'Quiz';
                $payload['thumbnail_url'] = UnsplashPlaceholder::quiz($subjectName);
            }

            DB::transaction(function () use ($quiz, $payload, $request) {
                $quiz->update($payload);

                $this->syncLevels($quiz, $request->input('levels', []));
                $this->syncTakeaways($quiz, $request->input('takeaways', []));
            });

            return redirect()
                ->route('tutor.quizzes.index')
                ->with('status', __('Quiz berhasil diperbarui.'));

        } catch (\Exception $e) {
            Log::error('Quiz Update Error: ' . $e->getMessage());

            return back()
                ->withErrors(['error' => 'Gagal memperbarui quiz: ' . $e->getMessage()])
                ->withInput();
        }
    }

    // Method untuk AJAX - mengambil subjects berdasarkan package
    public function getPackageSubjects(Package $package)
    {
        try {
            // Asumsi: Package memiliki relasi subjects() yang mengembalikan subjects terkait
            $subjects = $package->subjects()->get(['id', 'name', 'level']);
            return response()->json($subjects);
        } catch (\Exception $e) {
            Log::error('Error getting package subjects: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }

    private function syncLevels(Quiz $quiz, array $levels): void
    {
        // Hapus levels lama
        $quiz->levels()->delete();

        $payloads = collect($levels)
            ->map(fn($value) => trim((string) $value))
            ->filter()
            ->values()
            ->map(fn($label, $index) => [
                'label' => $label,
                'position' => $index + 1,
            ]);

        if ($payloads->isEmpty()) {
            return;
        }

        $payloads->each(fn($attributes) => $quiz->levels()->create($attributes));
    }

    private function syncTakeaways(Quiz $quiz, array $takeaways): void
    {
        // Hapus takeaways lama
        $quiz->takeaways()->delete();

        $payloads = collect($takeaways)
            ->map(fn($value) => trim((string) $value))
            ->filter()
            ->values()
            ->map(fn($description, $index) => [
                'description' => $description,
                'position' => $index + 1,
            ]);

        if ($payloads->isEmpty()) {
            return;
        }

        $payloads->each(fn($attributes) => $quiz->takeaways()->create($attributes));
    }
}