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

        return view('admin.landing-content.index', compact('contents'));
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

        try {
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($landingContent->image && !str_starts_with($landingContent->image, 'http')) {
                    $oldPath = public_path($landingContent->image);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }
                $data['image'] = $this->uploadImage($request->file('image'));
            } elseif ($landingContent->section === 'article' && !empty($data['content']['link'])) {
                $ogImage = $this->fetchOgImage($data['content']['link']);
                if ($ogImage) {
                    $data['image'] = $ogImage;
                }
            }

            $landingContent->update($data);

            return redirect()->back()->with('status', 'Konten berhasil diperbarui!');
        } catch (\Exception $e) {
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
     */
    private function uploadImage($file): string
    {
        $uploadDir = public_path('uploads/landing-images');

        // Pastikan folder ada
        if (!File::isDirectory($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true, true);
        }

        // Generate unique filename (tanpa karakter spesial dari nama asli)
        $extension = $file->getClientOriginalExtension() ?: 'jpg';
        $filename = time() . '_' . uniqid() . '.' . $extension;

        try {
            // Coba move file
            $file->move($uploadDir, $filename);
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Illuminate\Support\Facades\Log::error('Upload failed: ' . $e->getMessage());
            throw new \RuntimeException('Gagal upload gambar: ' . $e->getMessage());
        }

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
