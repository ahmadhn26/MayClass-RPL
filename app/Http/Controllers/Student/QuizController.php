<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Support\StudentAccess;
use App\Support\SubjectPalette;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $materialsLink = (string) config('mayclass.links.materials_drive');
        $quizLink = (string) config('mayclass.links.quiz_platform');

        $quizzesReady = Schema::hasTable('quizzes');
        $quizLevelsReady = Schema::hasTable('quiz_levels');
        $package = $this->currentPackage();

        if (! $package || ! $quizzesReady) {
            return view('student.quiz.index', [
                'page' => 'quiz',
                'title' => 'Koleksi Quiz',
                'activePackage' => $package,
                'collections' => collect(),
                'stats' => [
                    'total' => 0,
                    'total_questions' => 0,
                    'levels' => [],
                ],
                'materialsLink' => $materialsLink,
                'quizLink' => $quizLink,
            ]);
        }

        $quizzes = Quiz::query()
            ->where('package_id', optional($package)->id)
            ->with('subject')
            ->when($quizLevelsReady, fn ($query) => $query->with(['levels' => fn ($levels) => $levels->orderBy('position')]))
            ->orderBy('subject_id')
            ->orderBy('title')
            ->get();

        $collections = $quizzes
            ->groupBy(fn($quiz) => $quiz->subject->name ?? 'Tanpa Mapel')
            ->map(function ($items, $subject) use ($quizLink, $quizLevelsReady) {
                return [
                    'label' => $subject,
                    'accent' => SubjectPalette::accent($subject),
                    'items' => $items->map(fn ($quiz) => [
                        'slug' => $quiz->slug,
                        'title' => $quiz->title,
                        'summary' => $quiz->summary,
                        'duration' => $quiz->duration_label,
                        'questions' => $quiz->question_count,
                        'levels' => $quizLevelsReady ? $quiz->levels->pluck('label')->all() : [],
                        'link' => $quiz->link ?? $quizLink,
                    ])->values()->all(),
                ];
            })
            ->values();

        $levelSources = $quizzes->pluck('class_level');

        if ($quizLevelsReady) {
            $levelSources = $levelSources->merge($quizzes->flatMap(fn ($quiz) => $quiz->levels->pluck('label')));
        }

        $stats = [
            'total' => $quizzes->count(),
            'total_questions' => $quizzes->sum(fn ($quiz) => (int) $quiz->question_count),
            'levels' => $levelSources->filter()->unique()->values()->all(),
        ];

        return view('student.quiz.index', [
            'page' => 'quiz',
            'title' => 'Koleksi Quiz',
            'activePackage' => $package,
            'collections' => $collections,
            'stats' => $stats,
            'materialsLink' => $materialsLink,
            'quizLink' => $quizLink,
        ]);
    }

    public function show(string $slug): View
    {
        if (! Schema::hasTable('quizzes')) {
            abort(404);
        }

        $levelsReady = Schema::hasTable('quiz_levels');
        $takeawaysReady = Schema::hasTable('quiz_takeaways');

        $package = $this->currentPackage(true);

        $quiz = Quiz::query()
            ->where('slug', $slug)
            ->where('package_id', optional($package)->id)
            ->with('subject')
            ->when($levelsReady, fn ($query) => $query->with('levels'))
            ->when($takeawaysReady, fn ($query) => $query->with('takeaways'))
            ->firstOrFail();

        $platformLink = $quiz->link ?? $this->quizLink();

        return view('student.quiz.show', [
            'page' => 'quiz',
            'title' => $quiz->title,
            'quiz' => [
                'subject' => $quiz->subject->name ?? 'Tanpa Mapel',
                'level' => $quiz->class_level,
                'title' => $quiz->title,
                'summary' => $quiz->summary,
                'thumbnail' => $quiz->thumbnail_asset,
                'duration' => $quiz->duration_label,
                'questions' => (int) $quiz->question_count,
                'levels' => $levelsReady ? $quiz->levels->pluck('label')->filter()->values()->all() : [],
                'takeaways' => $takeawaysReady ? $quiz->takeaways->pluck('description')->filter()->values()->all() : [],
                'link' => $platformLink,
            ],
            'quizLink' => $this->quizLink(),
        ]);
    }

    private function quizLink(): string
    {
        return (string) config('mayclass.links.quiz_platform');
    }

    private function currentPackage(bool $required = false)
    {
        $enrollment = StudentAccess::activeEnrollment(Auth::user());

        if (! $enrollment || ! $enrollment->package) {
            if ($required) {
                abort(403);
            }

            return null;
        }

        return $enrollment->package;
    }
}
