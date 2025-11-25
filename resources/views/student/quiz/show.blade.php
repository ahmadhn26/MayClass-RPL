@extends('student.layouts.app')

@section('title', $quiz['title'])

@push('styles')
    <style>
        .student-quiz-detail__layout {
            display: grid;
            gap: clamp(28px, 5vw, 48px);
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            align-items: center;
        }

        .student-quiz-detail__header {
            display: grid;
            gap: 18px;
        }

        .student-quiz-detail__meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            color: var(--student-text-muted);
            font-size: 0.92rem;
        }

        .student-quiz-detail__levels {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .student-quiz-detail__takeaways {
            display: grid;
            gap: 12px;
        }

        .student-quiz-detail__takeaway {
            border-radius: var(--student-radius-md);
            padding: 16px 20px;
            background: rgba(95, 106, 248, 0.08);
            border: 1px solid rgba(95, 106, 248, 0.12);
            display: grid;
            gap: 6px;
        }

        .student-quiz-detail__thumbnail {
            width: 100%;
            border-radius: var(--student-radius-lg);
            object-fit: cover;
            min-height: 220px;
        }

        @media (max-width: 640px) {
            .student-quiz-detail__levels {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
@endpush

@section('content')
    <section class="student-section">
        <div class="student-quiz-detail__layout">
            <div class="student-quiz-detail__header">
                <div class="student-breadcrumbs">
                    <a href="{{ route('student.quiz') }}">Quiz</a>
                    <span>/</span>
                    <span>{{ $quiz['title'] }}</span>
                </div>
                <div class="student-chip">{{ $quiz['subject'] }} • Level {{ $quiz['level'] }}</div>
                <h1 style="margin: 0; font-size: clamp(2rem, 4vw, 2.8rem);">{{ $quiz['title'] }}</h1>
                <p style="margin: 0; color: var(--student-text-muted); font-size: 1rem;">{{ $quiz['summary'] }}</p>
                <div class="student-quiz-detail__meta">
                    <span>{{ $quiz['duration'] }}</span>
                    <span>{{ number_format($quiz['questions']) }} soal</span>
                </div>
                @if (! empty($quiz['levels']))
                    <div class="student-quiz-detail__levels">
                        @foreach ($quiz['levels'] as $label)
                            <span class="student-chip" style="background: rgba(95, 106, 248, 0.12); color: var(--student-accent);">{{ $label }}</span>
                        @endforeach
                    </div>
                @endif
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <a class="student-button student-button--primary" href="{{ $quiz['link'] }}" target="_blank" rel="noopener">Mulai latihan</a>
                    <a class="student-button student-button--outline" href="{{ $quizLink }}" target="_blank" rel="noopener">Lihat semua quiz</a>
                </div>
            </div>
            <img class="student-quiz-detail__thumbnail" src="{{ $quiz['thumbnail'] }}" alt="Ilustrasi quiz {{ $quiz['title'] }}">
        </div>
    </section>

    <section class="student-section">
        <div class="student-section__header">
            <h2 class="student-section__title">Yang akan kamu kuasai</h2>
        </div>
        @if (! empty($quiz['takeaways']))
            <div class="student-quiz-detail__takeaways">
                @foreach ($quiz['takeaways'] as $takeaway)
                    <article class="student-quiz-detail__takeaway">
                        <span style="font-weight: 600; color: var(--student-accent);">Fokus latihan</span>
                        <p style="margin: 0; color: var(--student-text-muted);">{{ $takeaway }}</p>
                    </article>
                @endforeach
            </div>
        @else
            <p style="color: var(--student-text-muted);">Highlight latihan akan segera hadir.</p>
        @endif
    </section>
@endsection
