@extends('student.layouts.app')

@section('title', $material['title'])

@push('styles')
    <style>
        .material-page {
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px 24px;
        }

        .material-breadcrumb {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 32px;
        }

        .material-breadcrumb a {
            color: #0f766e;
            text-decoration: none;
        }

        .material-breadcrumb a:hover {
            text-decoration: underline;
        }

        .material-header {
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

        .material-meta {
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

        .material-title {
            font-size: 2.25rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 16px 0;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .material-desc {
            font-size: 1.125rem;
            color: #64748b;
            line-height: 1.7;
            margin: 0;
        }

        .header-action {
            flex-shrink: 0;
        }

        .btn-view {
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

        .btn-view:hover {
            background: #115e59;
            transform: translateY(-1px);
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 48px;
        }

        .content-section {
            min-width: 0;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 24px 0;
            letter-spacing: -0.01em;
        }

        .objectives {
            display: grid;
            gap: 16px;
        }

        .objective {
            display: flex;
            gap: 12px;
        }

        .objective-num {
            min-width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0f766e;
            color: white;
            border-radius: 4px;
            font-weight: 700;
            font-size: 0.8125rem;
            flex-shrink: 0;
        }

        .objective-text {
            color: #47556;
            line-height: 1.6;
            margin: 0;
            font-size: 0.9375rem;
        }

        .chapters {
            display: grid;
            gap: 20px;
        }

        .chapter {
            padding-left: 16px;
            border-left: 2px solid #e2e8f0;
            transition: border-color 0.2s;
        }

        .chapter:hover {
            border-left-color: #0f766e;
        }

        .chapter-title {
            font-size: 1rem;
            font-weight: 600;
            color: #0f172a;
            margin: 0 0 6px 0;
        }

        .chapter-desc {
            color: #64748b;
            line-height: 1.6;
            margin: 0;
            font-size: 0.9375rem;
        }

        @media (max-width: 768px) {
            .material-header {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .header-action {
                order: -1;
            }

            .btn-view {
                width: 100%;
                text-align: center;
            }

            .material-title {
                font-size: 1.75rem;
            }

            .content-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="material-page">
        <div class="material-breadcrumb">
            <a href="{{ route('student.materials') }}">Materi</a>
            <span> / </span>
            <span>{{ $material['title'] }}</span>
        </div>

        <div class="material-header">
            <div class="header-content">
                <div class="material-meta">
                    <span class="meta-badge">{{ $material['subject'] }}</span>
                    <span class="meta-badge">{{ $material['level'] }}</span>
                </div>

                <h1 class="material-title">{{ $material['title'] }}</h1>
                <p class="material-desc">{{ $material['summary'] }}</p>
            </div>

            <div class="header-action">
                <a class="btn-view" href="{{ $material['view_url'] }}" target="_blank" rel="noopener">
                    Buka Materi
                </a>
            </div>
        </div>

        <div class="content-grid">
            @if (!empty($material['objectives']))
                <div class="content-section">
                    <h2 class="section-title">Tujuan Pembelajaran</h2>
                    <div class="objectives">
                        @foreach ($material['objectives'] as $index => $objective)
                            <div class="objective">
                                <div class="objective-num">{{ $index + 1 }}</div>
                                <p class="objective-text">{{ $objective }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (!empty($material['chapters']))
                <div class="content-section">
                    <h2 class="section-title">Rangkuman Bab</h2>
                    <div class="chapters">
                        @foreach ($material['chapters'] as $chapter)
                            <div class="chapter">
                                <h3 class="chapter-title">{{ $chapter['title'] }}</h3>
                                <p class="chapter-desc">{{ $chapter['description'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection