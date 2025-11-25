    <div class="tentor-form-card">
        <h2>Informasi Profil Tentor</h2>
        <p>Lengkapi data personal, kontak, hingga headline profesional tentor.</p>

        <div class="tentor-avatar-field">
            <img src="{{ $avatarPreview }}" alt="Foto tentor" id="tentor-avatar-preview">
            <div>
                <label class="btn-secondary" for="avatar">Unggah Foto Profil</label>
                <input id="avatar" type="file" name="avatar" accept="image/*" hidden>
                <p class="helper-text">Format JPG/PNG, ukuran maksimal 5MB.</p>
                @error('avatar')
                    <p class="helper-text" style="color: #dc2626;">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="tentor-form-grid" style="margin-top: 24px;">
            <div class="tentor-form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', optional($tentor)->name) }}" required>
                @error('name')<p class="helper-text" style="color:#dc2626;">{{ $message }}</p>@enderror
            </div>
            <div class="tentor-form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email', optional($tentor)->email) }}" required>
                @error('email')<p class="helper-text" style="color:#dc2626;">{{ $message }}</p>@enderror
            </div>
            <div class="tentor-form-group">
                <label>Username</label>
                <input type="text" name="username" value="{{ old('username', optional($tentor)->username) }}" required>
                <p class="helper-text">Digunakan tentor saat login.</p>
                @error('username')<p class="helper-text" style="color:#dc2626;">{{ $message }}</p>@enderror
            </div>
            <div class="tentor-form-group">
                <label>Nomor Telepon / WhatsApp</label>
                <input type="text" name="phone" value="{{ old('phone', optional($tentor)->phone) }}">
                @error('phone')<p class="helper-text" style="color:#dc2626;">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <div class="tentor-form-card">
        <h2>Profil Profesional</h2>
        <p>Atur headline, jenjang yang diajar, serta highlight pengalaman tentor.</p>

        <div class="tentor-form-grid">
            <div class="tentor-form-group">
                <label>Headline Singkat</label>
                <input type="text" name="headline" value="{{ old('headline', optional($tentorProfile)->headline) }}" placeholder="Contoh: Tentor Matematika dan Sains">
                @error('headline')<p class="helper-text" style="color:#dc2626;">{{ $message }}</p>@enderror
            </div>
            <div class="tentor-form-group">
                <label>Jenjang/Mata Pelajaran</label>
                <input type="text" name="specializations" value="{{ old('specializations', optional($tentorProfile)->specializations) }}" required placeholder="Contoh: Matematika SMA, Fisika SMP">
                @error('specializations')<p class="helper-text" style="color:#dc2626;">{{ $message }}</p>@enderror
            </div>
            <div class="tentor-form-group">
                <label>Pengalaman Mengajar (tahun)</label>
                <input type="number" name="experience_years" min="0" max="60" value="{{ old('experience_years', optional($tentorProfile)->experience_years) }}">
                @error('experience_years')<p class="helper-text" style="color:#dc2626;">{{ $message }}</p>@enderror
            </div>
            <div class="tentor-form-group">
                <label>Pendidikan Terakhir</label>
                <input type="text" name="education" value="{{ old('education', optional($tentorProfile)->education) }}" placeholder="Contoh: S1 Pendidikan Matematika">
                @error('education')<p class="helper-text" style="color:#dc2626;">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="tentor-form-group" style="margin-top:24px;">
            <label>Deskripsi Singkat</label>
            <textarea name="bio" placeholder="Ceritakan metode mengajar, pengalaman lomba, atau pendekatan belajar.">{{ old('bio', optional($tentorProfile)->bio) }}</textarea>
            @error('bio')<p class="helper-text" style="color:#dc2626;">{{ $message }}</p>@enderror
        </div>

        <div class="tentor-status-toggle" style="margin-top:24px;">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', optional($tentor)->is_active ?? true) ? 'checked' : '' }}>
            <label for="is_active" style="margin:0; font-weight:600;">Tentor aktif</label>
        </div>
    </div>

    <div class="tentor-form-card">
        <h2>Keahlian Mengajar</h2>
        <p>Pilih mata pelajaran yang dikuasai oleh tentor ini (minimal 1).</p>

        <div class="subject-selection">
            @foreach(['SD', 'SMP', 'SMA'] as $level)
                @if($subjectsByLevel[$level]->isNotEmpty())
                    <div class="subject-group">
                        <h4>{{ $level }}</h4>
                        <div class="subject-checkboxes">
                            @foreach($subjectsByLevel[$level] as $subject)
                                <label class="checkbox-label">
                                    <input type="checkbox" name="subjects[]" value="{{ $subject->id }}" 
                                        {{ in_array($subject->id, old('subjects', optional($tentor)->subjects->pluck('id')->toArray() ?? [])) ? 'checked' : '' }}>
                                    {{ $subject->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        
        @error('subjects')
            <p class="helper-text" style="color:#dc2626;">{{ $message }}</p>
        @enderror
    </div>

    <div class="tentor-form-card">
        <h2>Keamanan Akun Tentor</h2>
        <p>Atur kata sandi awal atau reset password tentor.</p>

        <div class="tentor-form-grid">
            <div class="tentor-form-group">
                <label>Password {{ ($mode ?? 'create') === 'create' ? '(wajib)' : '(opsional)' }}</label>
                <input type="password" name="password" {{ ($mode ?? 'create') === 'create' ? 'required' : '' }}>
                @if(($mode ?? 'create') !== 'create')
                    <p class="helper-text">Kosongkan bila tidak ingin mengubah password.</p>
                @endif
                @error('password')<p class="helper-text" style="color:#dc2626;">{{ $message }}</p>@enderror
            </div>
            <div class="tentor-form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" {{ ($mode ?? 'create') === 'create' ? 'required' : '' }}>
            </div>
        </div>

        <div class="tentor-form-actions" style="margin-top: 24px;">
            <a href="{{ route('admin.tentors.index') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary">
                {{ ($mode ?? 'create') === 'create' ? 'Simpan Tentor' : 'Perbarui Tentor' }}
            </button>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const input = document.getElementById('avatar');
                const preview = document.getElementById('tentor-avatar-preview');

                if (! input || ! preview) {
                    return;
                }

                input.addEventListener('change', function () {
                    const file = this.files?.[0];

                    if (! file) {
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = (event) => {
                        preview.src = event.target?.result;
                    };
                    reader.readAsDataURL(file);
                });
            });
        </script>
    @endpush
