@forelse ($students as $student)
    <tr data-student-id="{{ $student['id'] }}">
        <td>
            <div class="student-profile">
                {{-- Avatar - Show photo if exists, otherwise show initial --}}
                @if(!empty($student['avatar_path']))
                    <div class="student-avatar" style="padding: 0; background: transparent; overflow: hidden;">
                        <img src="{{ asset('storage/' . $student['avatar_path']) }}" alt="{{ $student['name'] }}"
                            style="width: 100%; height: 100%; object-fit: cover; display: block;"
                            onerror="this.parentElement.innerHTML='{{ substr($student['name'], 0, 1) }}';">
                    </div>
                @else
                    <div class="student-avatar">
                        {{ substr($student['name'], 0, 1) }}
                    </div>
                @endif
                <div class="student-info">
                    <span class="student-name">{{ $student['name'] }}</span>
                    <span class="student-email">{{ $student['email'] }}</span>
                </div>
            </div>
        </td>
        <td>
            <span class="student-id-badge">
                {{ $student['student_id'] ?? 'N/A' }}
            </span>
        </td>
        <td>
            @if(!empty($student['package']))
                <span style="font-weight: 600; color: var(--text-main);">{{ $student['package'] }}</span>
            @else
                <span style="color: var(--text-muted); font-style: italic;">Tidak ada paket</span>
            @endif
        </td>
        <td>
            <span class="status-pill" data-state="{{ $student['status_state'] ?? 'inactive' }}">
                {{ $student['status'] }}
            </span>
        </td>
        <td>
            <div style="display: flex; align-items: center; gap: 6px; color: var(--text-muted); font-size: 0.85rem;">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                {{ $student['ends_at'] ?? '-' }}
            </div>
        </td>
        <td style="text-align: center;">
            @if($student['parent_phone'])
                @php
                    $cleaned = preg_replace('/[^0-9]/', '', $student['parent_phone']);
                    $whatsappNumber = str_starts_with($cleaned, '08')
                        ? '62' . substr($cleaned, 1)
                        : $cleaned;
                    $message = urlencode('Halo, saya Admin MayClass. Ingin berdiskusi mengenai ' . $student['name']);
                @endphp
                <a href="https://wa.me/{{ $whatsappNumber }}?text={{ $message }}" class="btn-whatsapp" target="_blank"
                    rel="noopener" title="Chat dengan Orang Tua" onclick="event.stopPropagation();">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                    </svg>
                    <span>Konseling</span>
                </a>
            @else
                <span style="color: var(--text-muted); font-size: 0.85rem; font-style: italic;">
                    Belum ada nomor
                </span>
            @endif
        </td>
        <td style="text-align: right;">
            <button type="button" class="btn-toggle-status" data-id="{{ $student['id'] }}"
                data-name="{{ $student['name'] }}" data-active="{{ $student['status_state'] === 'active' ? '1' : '0' }}"
                style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 6px; border: 1px solid; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: all 0.2s; {{ $student['status_state'] === 'active' ? 'background: #fef3c7; color: #d97706; border-color: #fbbf24;' : 'background: #d1fae5; color: #059669; border-color: #34d399;' }}"
                title="{{ $student['status_state'] === 'active' ? 'Nonaktifkan' : 'Aktifkan' }} Paket"
                onclick="event.stopPropagation();"
                onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                @if($student['status_state'] === 'active')
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                        </path>
                    </svg>
                    <span>Nonaktifkan</span>
                @else
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Aktifkan</span>
                @endif
            </button>
            <button type="button" class="btn-delete" data-id="{{ $student['id'] }}" data-name="{{ $student['name'] }}"
                data-active="{{ $student['status_state'] }}" onclick="event.stopPropagation();">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                    </path>
                </svg>
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8">
            <div class="empty-state">
                <svg style="width: 48px; height: 48px; margin-bottom: 16px; color: #cbd5e1;" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
                <p>Belum ada data siswa yang tersedia.</p>
            </div>
        </td>
    </tr>
@endforelse