<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class LandingContentController extends Controller
{
    public function index()
    {
        $contents = \App\Models\LandingContent::orderBy('section')->orderBy('order')->get()
            ->groupBy('section');

        // Fetch active tutors for mentor preview (same data source as landing page)
        $activeTutors = \App\Models\User::where('role', 'tutor')
            ->where('is_active', true)
            ->with('tutorProfile')
            ->limit(10)
            ->get()
            ->map(function ($tutor) {
                $profile = $tutor->tutorProfile;
                return [
                    'id' => $tutor->id,
                    'name' => $tutor->name,
                    'role' => $profile?->headline ?? 'Tentor Spesialis',
                    'quote' => $profile?->bio ? \Illuminate\Support\Str::limit($profile->bio, 80) : 'Siap membimbing kamu meraih impian.',
                    'avatar' => \App\Support\ProfileAvatar::forUser($tutor),
                    'meta' => array_filter([
                        $profile?->education ?? 'Lulusan Terbaik',
                        ($profile && $profile->experience_years ? $profile->experience_years . ' Tahun Pengalaman' : null)
                    ])
                ];
            });

        return view('admin.landing-content.index', compact('contents', 'activeTutors'));
    }

    public function store(Request $request)
    {
        // Handle Bulk Insert for FAQ
        if ($request->input('section') === 'faq' && $request->has('items')) {
            $items = $request->validate([
                'items' => 'required|array',
                'items.*.question' => 'required|string',
                'items.*.answer' => 'required|string',
            ])['items'];

            foreach ($items as $item) {
                \App\Models\LandingContent::create([
                    'section' => 'faq',
                    'content' => $item,
                    'is_active' => true,
                ]);
            }

            return redirect()->back()->with('status', 'FAQ berhasil ditambahkan!');
        }

        $data = $request->validate([
            'section' => 'required|string',
            'title' => 'nullable|string',
            'content' => 'required|array',
            'content.title' => 'nullable|string',
            'content.subtitle' => 'nullable|string',
            'content.description' => 'nullable|string',
            'content.name' => 'nullable|string',
            'content.role' => 'nullable|string',
            'content.quote' => 'nullable|string',
            'content.question' => 'nullable|string',
            'content.answer' => 'nullable|string',
            'content.link' => 'nullable|url',
            'content.meta' => 'nullable|array',
            'content.meta.*' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:10240',
            'order' => 'nullable|integer',
        ]);

        try {
            if ($request->hasFile('image')) {
                $data['image'] = $this->uploadImage($request->file('image'));
            } elseif ($request->input('section') === 'article' && !empty($data['content']['link'])) {
                $data['image'] = $this->fetchOgImage($data['content']['link']);
            }

            \App\Models\LandingContent::create($data);

            return redirect()->back()->with('status', 'Konten berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['image' => 'Upload gagal: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, \App\Models\LandingContent $landingContent)
    {
        // Debug logging
        \Illuminate\Support\Facades\Log::info('LandingContent Update Request', [
            'id' => $landingContent->id,
            'section' => $landingContent->section,
            'has_file' => $request->hasFile('image'),
            'content' => $request->input('content'),
            'all_input' => $request->except(['image', '_token', '_method']),
        ]);

        // Check PHP upload errors BEFORE validation
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $error = $file->getError();

            \Illuminate\Support\Facades\Log::info('File upload details', [
                'error_code' => $error,
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
                'is_valid' => $file->isValid(),
                'php_upload_max' => ini_get('upload_max_filesize'),
                'php_post_max' => ini_get('post_max_size'),
            ]);

            // Check for PHP upload errors
            if ($error !== UPLOAD_ERR_OK) {
                $errorMessages = [
                    UPLOAD_ERR_INI_SIZE => 'File terlalu besar (melebihi upload_max_filesize=' . ini_get('upload_max_filesize') . ')',
                    UPLOAD_ERR_FORM_SIZE => 'File terlalu besar (melebihi MAX_FILE_SIZE)',
                    UPLOAD_ERR_PARTIAL => 'File hanya terupload sebagian',
                    UPLOAD_ERR_NO_FILE => 'Tidak ada file yang diupload',
                    UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary tidak ditemukan di server',
                    UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk (permission issue)',
                    UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh ekstensi PHP',
                ];
                $msg = $errorMessages[$error] ?? 'Error upload tidak diketahui (code: ' . $error . ')';
                return redirect()->back()->withInput()->withErrors(['image' => $msg]);
            }

            // Check if file is valid
            if (!$file->isValid()) {
                return redirect()->back()->withInput()->withErrors(['image' => 'File tidak valid. Coba dengan file yang lebih kecil atau format berbeda.']);
            }
        }

        $data = $request->validate([
            'title' => 'nullable|string',
            'content' => 'required|array',
            'content.title' => 'nullable|string',
            'content.subtitle' => 'nullable|string',
            'content.description' => 'nullable|string',
            'content.name' => 'nullable|string',
            'content.role' => 'nullable|string',
            'content.quote' => 'nullable|string',
            'content.question' => 'nullable|string',
            'content.answer' => 'nullable|string',
            'content.link' => 'nullable|url',
            'content.meta' => 'nullable|array',
            'content.meta.*' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:10240',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        \Illuminate\Support\Facades\Log::info('LandingContent Validated Data', ['data' => $data]);

        try {
            // Check for base64 image first (new method - bypasses PHP upload limits)
            if ($request->filled('image_base64')) {
                \Illuminate\Support\Facades\Log::info('Processing base64 image upload');

                // Hapus gambar lama jika ada
                if ($landingContent->image && !str_starts_with($landingContent->image, 'http')) {
                    $oldPath = public_path($landingContent->image);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }

                $data['image'] = $this->saveBase64Image($request->input('image_base64'));
                \Illuminate\Support\Facades\Log::info('Base64 image saved', ['path' => $data['image']]);
            }
            // Fallback to traditional file upload
            elseif ($request->hasFile('image')) {
                \Illuminate\Support\Facades\Log::info('Processing traditional file upload');
                // Hapus gambar lama jika ada
                if ($landingContent->image && !str_starts_with($landingContent->image, 'http')) {
                    $oldPath = public_path($landingContent->image);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }
                $data['image'] = $this->uploadImage($request->file('image'));
                \Illuminate\Support\Facades\Log::info('Image uploaded', ['path' => $data['image']]);
            } elseif ($landingContent->section === 'article' && !empty($data['content']['link'])) {
                $ogImage = $this->fetchOgImage($data['content']['link']);
                if ($ogImage) {
                    $data['image'] = $ogImage;
                }
            }

            $landingContent->update($data);
            \Illuminate\Support\Facades\Log::info('LandingContent Updated Successfully', ['id' => $landingContent->id]);

            return redirect()->back()->with('status', 'Konten berhasil diperbarui!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('LandingContent Update Failed', [
                'id' => $landingContent->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withInput()->withErrors(['image' => 'Update gagal: ' . $e->getMessage()]);
        }
    }

    public function destroy(\App\Models\LandingContent $landingContent)
    {
        // Hapus file gambar jika ada
        if ($landingContent->image && !str_starts_with($landingContent->image, 'http')) {
            $imagePath = public_path($landingContent->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $landingContent->delete();
        return redirect()->back()->with('status', 'Konten berhasil dihapus!');
    }

    /**
     * Upload image dengan cara yang kompatibel hosting
     * Multiple fallback strategies untuk berbagai environment
     */
    private function uploadImage($file): string
    {
        $uploadDir = public_path('uploads/landing-images');

        // Pastikan folder ada dengan permission yang benar
        if (!File::isDirectory($uploadDir)) {
            try {
                File::makeDirectory($uploadDir, 0777, true, true);
                @chmod($uploadDir, 0777);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to create upload directory: ' . $e->getMessage());
                throw new \RuntimeException('Gagal membuat folder upload: ' . $e->getMessage());
            }
        }

        // Generate unique filename
        $extension = $file->getClientOriginalExtension() ?: 'jpg';
        $filename = time() . '_' . uniqid() . '.' . $extension;
        $targetPath = $uploadDir . DIRECTORY_SEPARATOR . $filename;

        \Illuminate\Support\Facades\Log::info('Attempting image upload', [
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime' => $file->getMimeType(),
            'target' => $targetPath,
            'upload_dir_exists' => File::isDirectory($uploadDir),
            'upload_dir_writable' => is_writable($uploadDir),
        ]);

        // Strategy 1: Laravel move method
        try {
            $file->move($uploadDir, $filename);
            if (file_exists($targetPath)) {
                @chmod($targetPath, 0644);
                \Illuminate\Support\Facades\Log::info('Upload success via move()');
                return 'uploads/landing-images/' . $filename;
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Move method failed: ' . $e->getMessage());
        }

        // Strategy 2: PHP move_uploaded_file
        try {
            $tempPath = $file->getRealPath() ?: $file->getPathname();
            if ($tempPath && move_uploaded_file($tempPath, $targetPath)) {
                @chmod($targetPath, 0644);
                \Illuminate\Support\Facades\Log::info('Upload success via move_uploaded_file()');
                return 'uploads/landing-images/' . $filename;
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('move_uploaded_file failed: ' . $e->getMessage());
        }

        // Strategy 3: Copy file
        try {
            $tempPath = $file->getRealPath() ?: $file->getPathname();
            if ($tempPath && copy($tempPath, $targetPath)) {
                @chmod($targetPath, 0644);
                \Illuminate\Support\Facades\Log::info('Upload success via copy()');
                return 'uploads/landing-images/' . $filename;
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('copy() failed: ' . $e->getMessage());
        }

        // Strategy 4: file_put_contents with file_get_contents
        try {
            $tempPath = $file->getRealPath() ?: $file->getPathname();
            $content = file_get_contents($tempPath);
            if ($content !== false && file_put_contents($targetPath, $content)) {
                @chmod($targetPath, 0644);
                \Illuminate\Support\Facades\Log::info('Upload success via file_put_contents()');
                return 'uploads/landing-images/' . $filename;
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('file_put_contents failed: ' . $e->getMessage());
        }

        // All strategies failed
        \Illuminate\Support\Facades\Log::error('All upload strategies failed', [
            'upload_dir' => $uploadDir,
            'is_writable' => is_writable($uploadDir),
            'disk_free_space' => @disk_free_space($uploadDir),
        ]);

        throw new \RuntimeException('Gagal upload gambar. Pastikan folder uploads/landing-images dapat ditulis dengan permission 777.');
    }

    /**
     * Save base64 encoded image to file
     * This bypasses PHP upload_max_filesize limits
     */
    private function saveBase64Image(string $base64Data): string
    {
        $uploadDir = public_path('uploads/landing-images');

        // Pastikan folder ada
        if (!File::isDirectory($uploadDir)) {
            File::makeDirectory($uploadDir, 0777, true, true);
            @chmod($uploadDir, 0777);
        }

        // Extract mime type and data from base64 string
        // Format: data:image/jpeg;base64,/9j/4AAQSkZJRg...
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $matches)) {
            $extension = $matches[1];
            $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
        } else {
            $extension = 'png'; // default
        }

        // Normalize extension
        $extension = strtolower($extension);
        if ($extension === 'jpeg')
            $extension = 'jpg';

        // Decode base64
        $imageData = base64_decode($base64Data);
        if ($imageData === false) {
            throw new \RuntimeException('Gagal decode base64 image data');
        }

        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.' . $extension;
        $targetPath = $uploadDir . DIRECTORY_SEPARATOR . $filename;

        // Save file
        $result = file_put_contents($targetPath, $imageData);
        if ($result === false) {
            \Illuminate\Support\Facades\Log::error('Failed to save base64 image', [
                'target' => $targetPath,
                'is_writable' => is_writable($uploadDir),
            ]);
            throw new \RuntimeException('Gagal menyimpan gambar. Pastikan folder uploads/landing-images dapat ditulis.');
        }

        @chmod($targetPath, 0644);
        \Illuminate\Support\Facades\Log::info('Base64 image saved successfully', [
            'path' => $targetPath,
            'size' => $result
        ]);

        return 'uploads/landing-images/' . $filename;
    }

    /**
     * Fetch Open Graph image from URL
     */
    private function fetchOgImage(string $url): ?string
    {
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(5)->get($url);
            if ($response->successful()) {
                $html = $response->body();
                if (preg_match('/<meta property="og:image" content="([^"]+)"/', $html, $matches)) {
                    return $matches[1];
                }
            }
        } catch (\Exception $e) {
            // Ignore errors
        }
        return null;
    }
}
