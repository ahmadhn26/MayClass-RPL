@forelse ($packages as $package)
<tr>
    <td>
        <span class="pkg-name">{{ $package->detail_title }}</span>
        <span class="pkg-price-label">{{ $package->detail_price_label }}</span>
    </td>
    <td>
        <span class="pkg-level">{{ \App\Support\PackagePresenter::stageLabel($package->level) }}</span>
        @if ($package->grade_range)
            <span class="pkg-grades">{{ $package->grade_range }}</span>
        @endif
    </td>
    <td>
        <span class="pkg-price">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
    </td>
    <td>
        @php($quota = $package->quotaSnapshot())
        @if ($quota['limit'] === null)
            <span class="tag-pill tag-default">Tak terbatas</span>
        @else
            <div style="display: flex; flex-direction: column; gap: 4px;">
                <strong>{{ $quota['remaining'] }} / {{ $quota['limit'] }} kursi tersisa</strong>
                <small style="color: var(--text-muted);">
                    Aktif: {{ $quota['active_enrollments'] }}, Checkout terkunci:
                    {{ $quota['checkout_holds'] }}
                </small>
            </div>
        @endif
    </td>
    <td>
        @if($package->subjects->isNotEmpty())
            <div class="subject-pills">
                @foreach($package->subjects->take(3) as $subject)
                    <span class="subject-pill">{{ $subject->name }}</span>
                @endforeach
                @if($package->subjects->count() > 3)
                    <span class="subject-pill-more">+{{ $package->subjects->count() - 3 }} lainnya</span>
                @endif
            </div>
        @else
            <span class="text-muted">Belum ada</span>
        @endif
    </td>
    <td>
        @if($package->tutors->isNotEmpty())
            <div class="subject-pills">
                @foreach($package->tutors->take(3) as $tutor)
                    <span class="subject-pill"
                        style="background: #e0f2fe; color: #0369a1; border-color: #bae6fd;">{{ $tutor->name }}</span>
                @endforeach
                @if($package->tutors->count() > 3)
                    <span class="subject-pill-more"
                        style="background: #f1f5f9; color: #64748b;">+{{ $package->tutors->count() - 3 }}</span>
                @endif
            </div>
        @else
            <span class="text-muted">Belum ada</span>
        @endif
    </td>
    <td>
        <div class="action-group">
            <button type="button" class="btn-icon" title="Edit Paket"
                onclick="openEditPackageModal({{ $package->id }})">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                    </path>
                </svg>
            </button>

            <button type="button" class="btn-icon delete btn-delete" title="Hapus Paket" data-id="{{ $package->id }}"
                data-name="{{ $package->detail_title }}" data-action="{{ route('admin.packages.destroy', $package) }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                    </path>
                </svg>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="7">
        <div class="empty-state">
            <svg style="width: 48px; height: 48px; margin-bottom: 16px; color: #cbd5e1;" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            <p>Belum ada paket belajar yang tersedia.</p>
        </div>
    </td>
</tr>
@endforelse