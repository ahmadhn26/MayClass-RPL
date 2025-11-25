@extends('student.layouts.app')

@section('title', $material['title'])

@push('styles')
    <style>
        .student-material-detail__layout {
            display: grid;
            gap: clamp(28px, 5vw, 48px);
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            align-items: start;
        }

        .student-material-detail__header {
            display: grid;
            gap: 18px;
        }

        .student-material-detail__actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .student-material-detail__objectives,
        .student-material-detail__chapters {
            display: grid;
            gap: 12px;
        }

        .student-material-detail__objective {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            border-radius: var(--student-radius-md);
            padding: 14px 18px;
            background: rgba(47, 152, 140, 0.08);
            border: 1px solid rgba(47, 152, 140, 0.12);
        }

        .student-material-detail__objective span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 12px;
            background: var(--student-primary);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .student-material-detail__chapter {
            border-radius: var(--student-radius-md);
            padding: 16px 20px;
            background: rgba(95, 106, 248, 0.08);
            border: 1px solid rgba(95, 106, 248, 0.12);
        }

        .student-material-detail__chapter h3 {
            margin: 0 0 6px;
            font-size: 1.05rem;
        }

        .student-material-detail__thumbnail {
            width: 100%;
            border-radius: var(--student-radius-lg);
            object-fit: cover;
            min-height: 220px;
        }

        @media (max-width: 640px) {
            .student-material-detail__actions {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
@endpush

@section('content')
    <section class="student-section">
        <div class="student-material-detail__layout">
            <div class="student-material-detail__header">
                <div class="student-breadcrumbs">
                    <a href="{{ route('student.materials') }}">Materi</a>
                    <span>/</span>
                    <span>{{ $material['title'] }}</span>
                </div>
                <div class="student-chip">{{ $material['subject'] }} • Level {{ $material['level'] }}</div>
                <h1 style="margin: 0; font-size: clamp(2rem, 4vw, 2.8rem);">{{ $material['title'] }}</h1>
                <p style="margin: 0; color: var(--student-text-muted); font-size: 1rem;">{{ $material['summary'] }}</p>
                <div class="student-material-detail__actions">
                    <a class="student-button student-button--primary" href="{{ $material['view_url'] }}" target="_blank" rel="noopener">Lihat materi</a>
                    <a class="student-button student-button--outline" href="{{ $material['download_url'] }}" target="_blank" rel="noopener" download>
                        Download materi ({{ $material['download_label'] }})
                    </a>
                    <a class="student-button student-button--ghost" href="{{ $materialsLink }}" target="_blank" rel="noopener">Kelola di Google Drive</a>
                </div>
            </div>
            <img class="student-material-detail__thumbnail" src="{{ $material['thumbnail'] }}" alt="Ilustrasi materi {{ $material['title'] }}">
        </div>
    </section>

    <section class="student-section">
        <div class="student-section__header">
            <h2 class="student-section__title">Apa yang akan kamu pelajari</h2>
        </div>
        @if (! empty($material['objectives']))
            <div class="student-material-detail__objectives">
                @foreach ($material['objectives'] as $index => $objective)
                    <div class="student-material-detail__objective">
                        <span>{{ $index + 1 }}</span>
                        <p style="margin: 0;">{{ $objective }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p style="color: var(--student-text-muted);">Tujuan pembelajaran akan segera ditambahkan.</p>
        @endif
    </section>

    <section class="student-section">
        <div class="student-section__header">
            <h2 class="student-section__title">Rangkuman bab</h2>
        </div>
        @if (! empty($material['chapters']))
            <div class="student-material-detail__chapters">
                @foreach ($material['chapters'] as $chapter)
                    <article class="student-material-detail__chapter">
                        <h3>{{ $chapter['title'] }}</h3>
                        <p style="margin: 0; color: var(--student-text-muted);">{{ $chapter['description'] }}</p>
                    </article>
                @endforeach
            </div>
        @else
            <p style="color: var(--student-text-muted);">Rangkuman bab akan hadir setelah materi diperbarui.</p>
        @endif
    </section>
@endsection
