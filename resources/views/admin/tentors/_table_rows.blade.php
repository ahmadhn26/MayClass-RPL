@forelse ($tentors as $tentor)
    <tr>
        <td>
            <div class="profile-col">
                <img src="{{ $tentor['avatar'] }}" alt="Avatar" class="avatar">
                <div class="user-info">
                    <h4>{{ $tentor['name'] }}</h4>
                    <span>{{ $tentor['username'] }}</span>
                </div>
            </div>
        </td>
        <td>
            <div class="contact-info">
                <span>{{ $tentor['email'] }}</span>
                <small>{{ $tentor['phone'] ?: '-' }}</small>
            </div>
        </td>
        <td>
            <span class="spec-badge">{{ $tentor['specializations'] ?? 'Umum' }}</span>
        </td>
        <td>
            <div class="contact-info">
                <span>{{ $tentor['bank_name'] ?: '-' }}</span>
                <small>{{ $tentor['account_number'] ?: '-' }}</small>
            </div>
        </td>
        <td>
            <span class="status-pill {{ $tentor['is_active'] ? 'status-active' : 'status-inactive' }}">
                {{ $tentor['is_active'] ? 'Aktif' : 'Nonaktif' }}
            </span>
        </td>
        <td>
            <div class="action-group">
                <button type="button" class="btn-action btn-edit" onclick="openEditModal({{ $tentor['id'] }})">
                    Edit
                </button>
                {{-- Note: data-action attribute is generated with route() --}}
                <button type="button" class="btn-delete" data-id="{{ $tentor['id'] }}" data-name="{{ $tentor['name'] }}"
                    data-action="{{ route('admin.tentors.destroy', $tentor['id']) }}"
                    data-active="{{ $tentor['is_active'] ? 'true' : 'false' }}">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        <td colspan="6">
            <div class="empty-state">
                <p>Belum ada data tentor yang sesuai dengan pencarian.</p>
            </div>
        </td>
    </tr>
@endforelse