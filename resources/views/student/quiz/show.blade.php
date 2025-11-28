@extends('student.layouts.app')

@section('title', $quiz['title'])

@push('styles')
    <style>
        .quiz-page {
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px 24px;
        }

        .quiz-breadcrumb {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 32px;
        }

        .quiz-breadcrumb a {
            color: #0f766e;
            text-decoration: none;
        }

        .quiz-breadcrumb a:hover {
            text-decoration: underline;
        }

        .quiz-header {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 40px;
            align-items: start;
            margin-bottom: 48px;
            padding-bottom: 48px;
            border-bottom: 1px solid #e2e8f0;
        }

        .header-content {
            min-width: 0;
        }

        .quiz-meta {
            display: inline-flex;
            gap: 8px;
            margin-bottom: 16px;
        }

        .meta-badge {
            padding: 4px 12px;
            background: #f1f5f9;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .quiz-title {
            font-size: 2.25rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 16px 0;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .quiz-desc {
            font-size: 1.125rem;
            color: #64748b;
            line-height: 1.7;
            margin: 0 0 24px 0;
        }

        .quiz-stats {
            display: flex;
            gap: 32px;
        }

        .stat {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .stat-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
        }

        .header-action {
            flex-shrink: 0;
        }

        .btn-start {
            display: inline-block;
            padding: 14px 28px;
            background: #0f766e;
            color: white;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9375rem;
            text-decoration: none;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .btn-start:hover {
            background: #115e59;
            transform: translateY(-1px);
        }

        .quiz-levels {
            display: flex;
            gap: 8px;
            margin-top: 24px;
            flex-wrap: wrap;
        }

        .level-badge {
            padding: 6px 14px;
            background: #f1f5f9;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
        }

        .content-section {
            margin-top: 0;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 24px 0;
            letter-spacing: -0.01em;
        }

        .takeaways {
            display: grid;
            gap: 20px;
            max-width: 700px;
        }

        .takeaway {
            padding-left: 16px;
            border-left: 2px solid #e2e8f0;
            transition: border-color 0.2s;
        }

        .takeaway:hover {
            border-left-color: #0f766e;
        }

        .takeaway-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f766e;
            margin-bottom: 6px;
        }

        .takeaway-text {
            color: #475569;
            line-height: 1.6;
            margin: 0;
            font-size: 0.9375rem;
        }

        @media (max-width: 768px) {
            .quiz-header {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .header-action {
                order: -1;
            }

            .btn-start {
                width: 100%;
                text-align: center;
            }

            .quiz-title {
                font-size: 1.75rem;
            }

            .quiz-stats {
                flex-direction: column;
                gap: 16px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="quiz-page">
        <div class="quiz-breadcrumb">
            <a href="{{ route('student.quiz') }}">Quiz</a>
            <span> / </span>
            <span>{{ $quiz['title'] }}</span>
        </div>

        <div class="quiz-header">
            <div class="header-content">
                <div class="quiz-meta">
                    <span class="meta-badge">{{ $quiz['subject'] }}</span>
                    <span class="meta-badge">{{ $quiz['level'] }}</span>
                </div>

                <h1 class="quiz-title">{{ $quiz['title'] }}</h1>
                <p class="quiz-desc">{{ $quiz['summary'] }}</p>

                <div class="quiz-stats">
                    <div class="stat">
                        <span class="stat-label">Durasi</span>
                        <span class="stat-value">{{ $quiz['duration'] }}</span>
                    </div>
                    <div class="stat">
                        <span class="stat-label">Soal</span>
                        <span class="stat-value">{{ number_format($quiz['questions']) }}</span>
                    </div>
                </div>

                @if (!empty($quiz['levels']))
                    <div class="quiz-levels">
                        @foreach ($quiz['levels'] as $label)
                            <span class="level-badge">{{ $label }}</span>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="header-action">
                <a class="btn-start" href="{{ $quiz['link'] }}" target="_blank" rel="noopener">
                    Mulai Latihan
                </a>
            </div>
        </div>

        @if (!empty($quiz['takeaways']))
            <div class="content-section">
                <h2 class="section-title">Fokus Pembelajaran</h2>
                <div class="takeaways">
                    @foreach ($quiz['takeaways'] as $takeaway)
                        <div class="takeaway">
                            <div class="takeaway-label">Poin Utama</div>
                            <p class="takeaway-text">{{ $takeaway }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection