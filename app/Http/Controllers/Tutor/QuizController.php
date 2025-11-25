<?php

namespace App\Http\Controllers\Tutor;

use App\Models\Package;
use App\Models\Quiz;
use App\Support\UnsplashPlaceholder;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

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

        return $this->render('tutor.quizzes.index', [
            'quizzes' => $quizzes,
            'search' => $search,
            'tableReady' => $tableReady,
        ]);
    }

    public function create()
    {
        $packages = Schema::hasTable('packages')
            ? Package::orderBy('level')->orderBy('price')->get()
            : collect();

        return $this->render('tutor.quizzes.create', [
            'packages' => $packages,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (! Schema::hasTable('quizzes')) {
            return redirect()
                ->route('tutor.quizzes.index')
                ->with('alert', __('Tabel quiz belum siap. Jalankan migrasi database terlebih dahulu.'));
        }

        if (! Schema::hasTable('packages')) {
            return redirect()
                ->route('tutor.quizzes.index')
                ->with('alert', __('Tabel paket belum siap. Pastikan migrasi paket sudah dijalankan.'));
        }

        $data = $request->validate([
            'package_id' => ['required', 'exists:packages,id'],
            'title' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:120'],
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

        $slug = Str::slug($data['title']) ?: 'quiz-' . Str::random(6);
        $uniqueSlug = $slug;
        $counter = 1;
        while (Quiz::where('slug', $uniqueSlug)->exists()) {
            $uniqueSlug = $slug . '-' . $counter++;
        }

        DB::transaction(function () use ($data, $request, $uniqueSlug) {
            $quiz = Quiz::create([
                'slug' => $uniqueSlug,
                'package_id' => $data['package_id'],
                'subject_id' => $data['subject_id'],
                'class_level' => $data['class_level'],
                'title' => $data['title'],
                'summary' => $data['summary'],
                'link_url' => $data['link_url'],
                'thumbnail_url' => UnsplashPlaceholder::quiz(\App\Models\Subject::find($data['subject_id'])->name ?? 'Quiz'),
                'duration_label' => $data['duration_label'],
                'question_count' => $data['question_count'],
            ]);

            $this->syncLevels($quiz, $request->input('levels', []));
            $this->syncTakeaways($quiz, $request->input('takeaways', []));
        });

        return redirect()
            ->route('tutor.quizzes.index')
            ->with('status', __('Quiz baru berhasil dibuat.'));
    }

    public function edit(Quiz $quiz)
    {
        $quiz->load(['levels', 'takeaways']);

        $packages = Schema::hasTable('packages')
            ? Package::orderBy('level')->orderBy('price')->get()
            : collect();

        return $this->render('tutor.quizzes.edit', [
            'quiz' => $quiz,
            'packages' => $packages,
        ]);
    }

    public function update(Request $request, Quiz $quiz): RedirectResponse
    {
        if (! Schema::hasTable('quizzes')) {
            return redirect()
                ->route('tutor.quizzes.index')
                ->with('alert', __('Tabel quiz belum siap. Jalankan migrasi database terlebih dahulu.'));
        }

        if (! Schema::hasTable('packages')) {
            return redirect()
                ->route('tutor.quizzes.index')
                ->with('alert', __('Tabel paket belum siap. Pastikan migrasi paket sudah dijalankan.'));
        }

        $data = $request->validate([
            'package_id' => ['required', 'exists:packages,id'],
            'title' => ['required', 'string', 'max:255'],
            'subject_id' => ['required', 'exists:subjects,id'],
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
            $subjectName = \App\Models\Subject::find($data['subject_id'])?->name ?? 'Quiz';
            $payload['thumbnail_url'] = UnsplashPlaceholder::quiz($subjectName);
        }

        DB::transaction(function () use ($quiz, $payload, $request) {
            $quiz->update($payload);

            $quiz->levels()->delete();
            $quiz->takeaways()->delete();

            $this->syncLevels($quiz, $request->input('levels', []));
            $this->syncTakeaways($quiz, $request->input('takeaways', []));
        });

        return redirect()
            ->route('tutor.quizzes.index')
            ->with('status', __('Quiz berhasil diperbarui.'));
    }

    private function syncLevels(Quiz $quiz, array $levels): void
    {
        $payloads = collect($levels)
            ->map(fn ($value) => trim((string) $value))
            ->filter()
            ->values()
            ->map(fn ($label, $index) => [
                'label' => $label,
                'position' => $index + 1,
            ]);

        if ($payloads->isEmpty()) {
            return;
        }

        $payloads->each(fn ($attributes) => $quiz->levels()->create($attributes));
    }

    private function syncTakeaways(Quiz $quiz, array $takeaways): void
    {
        $payloads = collect($takeaways)
            ->map(fn ($value) => trim((string) $value))
            ->filter()
            ->values()
            ->map(fn ($description, $index) => [
                'description' => $description,
                'position' => $index + 1,
            ]);

        if ($payloads->isEmpty()) {
            return;
        }

        $payloads->each(fn ($attributes) => $quiz->takeaways()->create($attributes));
    }
}
