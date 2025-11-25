<!DOCTYPE html>
<html lang="id" data-mode="{{ $mode === 'register' ? 'register' : 'login' }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MayClass - Akses Pembelajaran</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
    
    <style>
        :root {
            --primary-600: #0f766e; 
            --primary-700: #0d655d;
            --neutral-50: #f8fafc;
            --neutral-100: #f1f5f9;
            --neutral-200: #e2e8f0;
            --neutral-400: #94a3b8;
            --neutral-500: #64748b;
            --neutral-900: #0f172a;
            --danger: #ef4444;
            --success: #10b981;
            --radius-md: 12px;
            --radius-lg: 24px;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
                    margin: 0; font-family: 'Plus Jakarta Sans', sans-serif;
                    color: var(--neutral-900);
                    min-height: 100vh; display: flex; align-items: center; justify-content: center;
                    overflow-x: hidden; padding: 20px;
                    
                    background-color: #0f172a;
                    background-image: 
                        linear-gradient(rgba(15, 23, 42, 0.5), rgba(15, 23, 42, 0.7)), 
                        url("{{ asset('images/bg_kelas.jpg') }}"); 
                    background-size: cover;
                    background-position: center;
                    background-attachment: fixed;
                    background-repeat: no-repeat;
                }

        a { text-decoration: none; color: inherit; transition: all 0.2s; }

        /* --- GLASSMORPHISM CARD --- */
        .auth-container {
            width: 100%; max-width: 480px;
            
            /* Efek Kaca / Transparan */
            background: rgba(255, 255, 255, 0.56); /* Sedikit lebih solid agar teks jelas */
            backdrop-filter: blur(16px);           
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            
            border-radius: var(--radius-lg);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3);
            padding: 40px; 
            position: relative;
        }

        body, body * {
            color: #000 !important;
        }


        .back-button {
            position: absolute; top: 24px; left: 24px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 0.9rem; font-weight: 600; color: var(--neutral-500);
            padding: 8px 16px; border-radius: 99px; 
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: all 0.2s;
        }
        .back-button:hover { background: #fff; color: var(--neutral-900); transform: translateY(-1px); }

        .header-text { text-align: center; margin-top: 24px; margin-bottom: 32px; }
        .header-text h2 { font-size: 1.8rem; font-weight: 800; color: var(--neutral-900); margin: 0 0 8px 0; letter-spacing: -0.02em; }
        .header-text p { color: var(--neutral-500); font-size: 0.95rem; margin: 0; line-height: 1.5; }

        .auth-form { display: none; flex-direction: column; gap: 20px; animation: fade-in 0.4s ease-out; }
        @keyframes fade-in { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        html[data-mode="login"] form[data-mode="login"],
        html[data-mode="register"] form[data-mode="register"] { display: flex; }

        .input-group { display: flex; flex-direction: column; gap: 6px; }
        .input-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .label { font-size: 0.875rem; font-weight: 600; color: var(--neutral-900); }
        
        .input-field {
            width: 100%; padding: 12px 16px;
            border: 1px solid var(--neutral-200); border-radius: var(--radius-md);
            background: rgba(255, 255, 255, 0.6); /* Input semi transparan */
            font-family: inherit; font-size: 0.95rem;
            color: var(--neutral-900); transition: all 0.2s;
        }
        .input-field:focus {
            outline: none; border-color: var(--primary-600); background: #fff;
            box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.15);
        }

        .btn-primary {
            background: var(--primary-600); color: white; border: none;
            padding: 14px; border-radius: var(--radius-md);
            font-size: 1rem; font-weight: 600; cursor: pointer;
            transition: all 0.2s; margin-top: 8px;
            box-shadow: 0 4px 12px rgba(15, 118, 110, 0.3);
        }
        .btn-primary:hover { background: var(--primary-700); transform: translateY(-1px); }
        .btn-primary:active { transform: scale(0.98); }

        .btn-primary:disabled {
            background-color: var(--neutral-200);
            color: var(--neutral-500);
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }

        /* Tombol Google */
        .divider { display: flex; align-items: center; gap: 10px; margin: 10px 0 0; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--neutral-200); }
        .divider span { color: var(--neutral-500); font-size: 0.85rem; font-weight: 500; }

        .btn-google {
            display: flex; justify-content: center; align-items: center; gap: 12px;
            width: 100%; padding: 12px; 
            border: 1px solid var(--neutral-200); border-radius: var(--radius-md);
            background: rgba(255,255,255,0.8); color: var(--neutral-900); 
            font-weight: 600; margin-top: 16px; transition: all 0.2s;
        }
        .btn-google:hover { background: #fff; border-color: var(--neutral-400); }

        .error-msg { color: var(--danger); font-size: 0.8rem; margin-top: 2px; }
        
        /* Alerts */
        .alert-success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 12px; border-radius: 8px; font-size: 0.9rem; margin-bottom: 20px; }
        .alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 12px; border-radius: 8px; font-size: 0.9rem; margin-bottom: 20px; }

        /* Links */
        .forgot-pass-wrapper { display: flex; justify-content: flex-end; margin-top: -12px; }
        .forgot-link { font-size: 0.85rem; color: var(--neutral-500); font-weight: 500; }
        .forgot-link span { color: var(--primary-600); font-weight: 600; }
        .forgot-link:hover { color: var(--primary-600); text-decoration: underline; }

        .auth-footer {
            margin-top: 16px; padding-top: 24px;
            border-top: 1px solid var(--neutral-200);
            text-align: center; font-size: 0.9rem; color: var(--neutral-500);
        }
        .switch-link { color: var(--neutral-900); font-weight: 700; margin-left: 4px; }
        .switch-link:hover { color: var(--primary-600); text-decoration: underline; }

        /* Terms Checkbox */
        .terms-trigger-wrapper { display: flex; align-items: flex-start; gap: 10px; margin-top: 4px; }
        .terms-trigger-wrapper input { margin-top: 3px; accent-color: var(--primary-600); width: 18px; height: 18px; }
        .terms-btn { background: none; border: none; padding: 0; font: inherit; color: var(--primary-600); font-weight: 600; cursor: pointer; text-decoration: underline; }

        /* MODAL CSS */
        .terms-modal {
            position: fixed; inset: 0; display: grid; place-items: center;
            background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px);
            z-index: 1000; opacity: 0; pointer-events: none;
            transition: opacity 0.2s ease;
        }
        .terms-modal.is-visible { opacity: 1; pointer-events: auto; }

        .terms-dialog {
            background: #fff; border-radius: 24px;
            width: 90%; max-width: 600px; max-height: 85vh;
            overflow: hidden; display: flex; flex-direction: column;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .terms-header {
            padding: 20px 24px; border-bottom: 1px solid var(--neutral-200);
            display: flex; justify-content: space-between; align-items: center;
            background: #f8fafc;
        }
        .terms-header h3 { margin: 0; font-size: 1.1rem; font-weight: 700; color: var(--neutral-900); }
        .terms-close {
            background: #fff; border: 1px solid var(--neutral-200);
            width: 32px; height: 32px; border-radius: 50%;
            display: grid; place-items: center; cursor: pointer; color: var(--neutral-500);
        }
        .terms-body { padding: 24px; overflow-y: auto; color: var(--neutral-500); font-size: 0.95rem; line-height: 1.6; }
        .terms-body h4 { color: var(--neutral-900); margin: 24px 0 8px; font-size: 1rem; }
        .terms-body h4:first-child { margin-top: 0; }
        .terms-body p { margin: 0 0 12px; }
        .terms-body ul { margin: 0 0 12px 20px; padding: 0; }

        .terms-consent {
            margin-top: 20px; padding: 16px;
            border-radius: 12px; border: 1px solid var(--primary-600);
            background: #f0fdfa; display: flex; gap: 12px; align-items: flex-start;
        }
        .terms-consent input[type="checkbox"] { width: 20px; height: 20px; margin-top: 3px; accent-color: var(--primary-600); }
        .terms-consent label { display: flex; flex-direction: column; gap: 2px; font-size: 0.95rem; color: var(--neutral-900); }
        .terms-consent label small { font-size: 0.85rem; color: var(--neutral-500); font-weight: 400; }
        .terms-consent.is-approved { background: #dcfce7; border-color: #16a34a; }

        @media (max-width: 600px) { .auth-container { padding: 32px 24px; } .input-row { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

    @php($profileData = $profile ?? [])
    
    @if (session('register_success'))
        <div class="terms-modal is-visible" style="z-index: 2000;">
            <div style="background:#fff; padding:32px; border-radius:20px; text-align:center; max-width:320px;">
                <div style="width:56px; height:56px; background:#ecfdf5; color:#10b981; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 16px; font-size:28px;">✓</div>
                <h3 style="margin:0 0 8px; color: var(--neutral-900);">Berhasil!</h3>
                <p style="margin:0; color:var(--neutral-500); font-size:0.9rem;">{{ session('status') ?? 'Akun berhasil dibuat.' }}</p>
            </div>
        </div>
        <script>setTimeout(() => document.querySelector('.terms-modal.is-visible').classList.remove('is-visible'), 2000);</script>
    @endif

    <div class="auth-container">
        {{-- Back Button (No Arrow) --}}
        <a href="{{ url('/') }}" class="back-button">Kembali</a>

        <div class="header-text">
            <h2 data-copy-mode="register">Buat Akun Baru</h2>
            <h2 data-copy-mode="login" style="display: none;">Selamat Datang</h2>
            <p data-copy-mode="register">Lengkapi data diri untuk memulai akses MayClass.</p>
            <p data-copy-mode="login" style="display: none;">Masukan kredensial untuk melanjutkan belajar.</p>
        </div>

        {{-- ALERT SUKSES (HIJAU) --}}
        @if (session('status')) <div class="alert-success">{{ session('status') }}</div> @endif

        {{-- ALERT ERROR (MERAH) --}}
        @if (session('error')) <div class="alert-error">{{ session('error') }}</div> @endif

        {{-- FORM REGISTER --}}
        <form data-mode="register" method="post" action="{{ route('register.details') }}" class="auth-form" novalidate>
            @csrf
            <div class="input-row">
                <div class="input-group">
                    <label class="label">Nama Lengkap</label>
                    <input class="input-field" type="text" name="name" value="{{ old('name', $profileData['name'] ?? '') }}" required>
                    @error('name') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="input-group">
                    <label class="label">Username</label>
                    <input class="input-field" type="text" name="username" value="{{ old('username', $profileData['username'] ?? '') }}" required>
                    @error('username') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="input-row">
                <div class="input-group">
                    <label class="label">Email</label>
                    <input class="input-field" type="email" name="email" value="{{ old('email', $profileData['email'] ?? '') }}" required>
                    @error('email') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="input-group">
                    <label class="label">No. WhatsApp</label>
                    <input class="input-field" type="tel" name="phone" value="{{ old('phone', $profileData['phone'] ?? '') }}" required>
                    @error('phone') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="input-group">
                <label class="label">Jenis Kelamin</label>
                <select class="input-field" name="gender">
                    <option value="" disabled selected>Pilih opsi...</option>
                    <option value="male" @selected(old('gender') == 'male')>Laki-laki</option>
                    <option value="female" @selected(old('gender') == 'female')>Perempuan</option>
                </select>
            </div>

            {{-- Trigger Checkbox --}}
            <div class="terms-trigger-wrapper">
                <input type="checkbox" id="main-terms-check" required onclick="if(!this.checked) { toggleRegisterButton(); return; } event.preventDefault(); toggleTerms(true);">
                <label for="main-terms-check" style="font-size: 0.9rem; color: var(--neutral-500);">
                    Saya menyetujui <button type="button" class="terms-btn" onclick="toggleTerms(true)">Ketentuan Layanan</button>.
                </label>
            </div>

            {{-- BUTTON REGISTER --}}
            <button class="btn-primary" type="submit" id="reg-submit-btn" disabled>Buat Akun</button>
            
            {{-- GOOGLE REGISTER --}}
            <div class="divider"><span>atau daftar dengan</span></div>
            <a href="{{ route('google.login', ['mode' => 'register']) }}" class="btn-google">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M23.52 12.29C23.52 11.46 23.45 10.66 23.32 9.9H12V14.51H18.47C18.18 15.99 17.34 17.25 16.08 18.1L19.94 21.1C22.2 19.01 23.52 15.92 23.52 12.29Z" fill="#4285F4"/>
                    <path d="M12 24C15.24 24 17.96 22.92 19.94 21.1L16.08 18.1C15 18.83 13.62 19.26 12 19.26C8.87 19.26 6.22 17.15 5.28 14.29L1.29 17.38C3.26 21.3 7.31 24 12 24Z" fill="#34A853"/>
                    <path d="M5.28 14.29C5.03 13.43 4.9 12.52 4.9 11.6C4.9 10.68 5.03 9.77 5.28 8.91L1.29 5.82C0.47 7.47 0 9.49 0 11.6C0 13.71 0.47 15.73 1.29 17.38L5.28 14.29Z" fill="#FBBC05"/>
                    <path d="M12 3.93C13.76 3.93 15.34 4.54 16.58 5.73L19.99 2.33C17.95 0.43 15.24 0 12 0C7.31 0 3.26 2.7 1.29 5.82L5.28 8.91C6.22 6.05 8.87 3.93 12 3.93Z" fill="#EA4335"/>
                </svg>
                Google
            </a>
            
            <div class="auth-footer">
                Sudah punya akun MayClass? 
                <a href="#" onclick="switchMode('login'); return false;" class="switch-link">Masuk</a>
            </div>
        </form>

        {{-- FORM LOGIN --}}
        <form data-mode="login" method="post" action="{{ route('login.perform') }}" class="auth-form" novalidate>
            @csrf
            <div class="input-group">
                <label class="label">Username</label>
                <input class="input-field" type="text" name="username" value="{{ old('username') }}" required>
            </div>

            <div class="input-group">
                <label class="label">Password</label>
                <input class="input-field" type="password" name="password" required>
            </div>

            <div class="forgot-pass-wrapper">
                <a href="{{ route('password.request') }}" class="forgot-link">
                    Lupa password? <span>Hubungi admin</span>
                </a>
            </div>

            <button class="btn-primary" type="submit">Masuk</button>

            {{-- GOOGLE LOGIN --}}
            <div class="divider"><span>atau</span></div>
            <a href="{{ route('google.login', ['mode' => 'login']) }}" class="btn-google">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M23.52 12.29C23.52 11.46 23.45 10.66 23.32 9.9H12V14.51H18.47C18.18 15.99 17.34 17.25 16.08 18.1L19.94 21.1C22.2 19.01 23.52 15.92 23.52 12.29Z" fill="#4285F4"/>
                    <path d="M12 24C15.24 24 17.96 22.92 19.94 21.1L16.08 18.1C15 18.83 13.62 19.26 12 19.26C8.87 19.26 6.22 17.15 5.28 14.29L1.29 17.38C3.26 21.3 7.31 24 12 24Z" fill="#34A853"/>
                    <path d="M5.28 14.29C5.03 13.43 4.9 12.52 4.9 11.6C4.9 10.68 5.03 9.77 5.28 8.91L1.29 5.82C0.47 7.47 0 9.49 0 11.6C0 13.71 0.47 15.73 1.29 17.38L5.28 14.29Z" fill="#FBBC05"/>
                    <path d="M12 3.93C13.76 3.93 15.34 4.54 16.58 5.73L19.99 2.33C17.95 0.43 15.24 0 12 0C7.31 0 3.26 2.7 1.29 5.82L5.28 8.91C6.22 6.05 8.87 3.93 12 3.93Z" fill="#EA4335"/>
                </svg>
                Masuk dengan Google
            </a>
            
            <div class="auth-footer">
                Baru di MayClass? 
                <a href="#" onclick="switchMode('register'); return false;" class="switch-link">Daftar</a>
            </div>
        </form>
    </div>

    {{-- MODAL TERMS --}}
    <div class="terms-modal" id="terms-modal" aria-hidden="true">
        <div class="terms-dialog">
            <header class="terms-header">
                <h3>Syarat & Kebijakan Privasi</h3>
                <button class="terms-close" onclick="toggleTerms(false)">&times;</button>
            </header>
            <div class="terms-body">
                <h4>1. Pendaftaran Akun</h4>
                <p>MayClass menyediakan platform belajar terpadu. Anda wajib memberikan nama, username, email, dan nomor telepon yang valid untuk membangun profil pembelajaran.</p>

                <h4>2. Langganan & Pembayaran</h4>
                <p>Setelah akun dibuat, Anda dapat memilih paket belajar. Setiap transaksi checkout akan berstatus <strong>menunggu verifikasi admin</strong>. Akses materi premium aktif setelah validasi.</p>

                <h4>3. Materi & Jadwal</h4>
                <p>Konten belajar tersedia dinamis sesuai paket. Jadwal kelas mengikuti sesi yang dirancang tentor. Kami mencatat progres untuk pengalaman belajar yang konsisten.</p>

                <h4>4. Kewajiban Pengguna</h4>
                <ul>
                    <li>Menjaga kerahasiaan kredensial akun.</li>
                    <li>Tidak membagikan materi berbayar ke pihak luar.</li>
                    <li>Menggunakan fitur hanya untuk belajar pribadi.</li>
                </ul>

                <h4>5. Data Pribadi</h4>
                <p>Kami menggunakan informasi kontak untuk pengingat jadwal dan komunikasi dukungan. Kami tidak menjual data pribadi ke pihak ketiga di luar keperluan layanan inti.</p>

                <h4>6. Keamanan</h4>
                <p>Jika menemukan aktivitas mencurigakan, hubungi dukungan kami melalui email atau WhatsApp resmi.</p>

                <div class="terms-consent" id="terms-consent-box">
                    <input type="checkbox" id="modal-terms-check">
                    <label for="modal-terms-check">
                        <strong>Saya telah membaca dan menyetujui ketentuan di atas.</strong>
                        <small>Popup akan tertutup otomatis dalam 1 detik setelah dicentang.</small>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <script>
        const htmlRoot = document.documentElement;
        const modeElements = document.querySelectorAll('[data-copy-mode]');
        
        function switchMode(mode) {
            htmlRoot.setAttribute('data-mode', mode);
            const url = mode === 'register' ? "{{ route('register') }}" : "{{ route('login') }}";
            history.replaceState(null, '', url);
            modeElements.forEach(el => el.style.display = el.dataset.copyMode === mode ? 'block' : 'none');
        }
        switchMode(htmlRoot.getAttribute('data-mode'));

        // --- TERMS LOGIC UPDATE ---
        const termsModal = document.getElementById('terms-modal');
        const modalCheckbox = document.getElementById('modal-terms-check');
        const mainCheckbox = document.getElementById('main-terms-check');
        const consentBox = document.getElementById('terms-consent-box');
        const regSubmitBtn = document.getElementById('reg-submit-btn');
        let timer = null;

        // Fungsi untuk cek status tombol
        function toggleRegisterButton() {
            if (mainCheckbox.checked) {
                regSubmitBtn.removeAttribute('disabled');
            } else {
                regSubmitBtn.setAttribute('disabled', 'true');
            }
        }

        function toggleTerms(show) {
            if (show) {
                termsModal.classList.add('is-visible');
                modalCheckbox.checked = false;
                consentBox.classList.remove('is-approved');
            } else {
                termsModal.classList.remove('is-visible');
                clearTimeout(timer);
            }
        }

        // Jika dicentang dari Modal
        modalCheckbox.addEventListener('change', function() {
            if (this.checked) {
                consentBox.classList.add('is-approved');
                
                // Centang checkbox di form utama
                mainCheckbox.checked = true;
                // Aktifkan tombol submit
                toggleRegisterButton();

                timer = setTimeout(() => {
                    toggleTerms(false);
                }, 1000);
            } else {
                consentBox.classList.remove('is-approved');
                mainCheckbox.checked = false;
                toggleRegisterButton();
                clearTimeout(timer);
            }
        });

        // Jika user uncheck manual dari luar modal
        mainCheckbox.addEventListener('change', function() {
            toggleRegisterButton();
        });

        termsModal.addEventListener('click', (e) => {
            if (e.target === termsModal) toggleTerms(false);
        });
    </script>
</body>
</html>