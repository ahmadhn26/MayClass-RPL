<?php

namespace App\Http\Controllers\Admin;

use App\Models\Documentation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DocumentationController extends BaseAdminController
{
    public function index()
    {
        $documentations = Documentation::where('is_active', true)
            ->latestOrder()
            ->get()
            ->map(function ($doc) {
                // Support both old (storage/) and new (uploads/) paths
                $photoUrl = str_starts_with($doc->photo_path, 'uploads/')
                    ? asset($doc->photo_path)
                    : asset('storage/' . $doc->photo_path);

                return [
                    'id' => $doc->id,
                    'photo_url' => $photoUrl,
                    'photo_path' => $doc->photo_path,
                    'activity_date' => $doc->activity_date->format('Y-m-d'),
                    'activity_date_formatted' => $doc->activity_date->locale('id')->translatedFormat('d F Y'),
                    'description' => $doc->description,
                    'week_number' => $doc->week_number,
                    'year' => $doc->year,
                ];
            });

        return $this->render('admin.documentations.index', [
            'documentations' => $documentations,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'activity_date' => ['required', 'date'],
            'description' => ['required', 'string', 'max:1000'],
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        try {
            // Upload foto langsung ke public folder
            $photoPath = $this->uploadImage($request->file('photo'));

            // Hitung week number
            $date = Carbon::parse($data['activity_date']);
            $weekNumber = $date->weekOfYear;
            $year = $date->year;

            // Get max order untuk minggu ini
            $maxOrder = Documentation::where('year', $year)
                ->where('week_number', $weekNumber)
                ->max('order') ?? 0;

            Documentation::create([
                'photo_path' => $photoPath,
                'activity_date' => $data['activity_date'],
                'description' => $data['description'],
                'week_number' => $weekNumber,
                'year' => $year,
                'order' => $maxOrder + 1,
                'is_active' => true,
            ]);

            return redirect()->back()
                ->with('status', 'Dokumentasi berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['photo' => 'Upload gagal: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, Documentation $documentation)
    {
        $data = $request->validate([
            'activity_date' => ['required', 'date'],
            'description' => ['required', 'string', 'max:1000'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        try {
            $photoPath = $documentation->photo_path;

            // Upload foto baru jika ada
            if ($request->hasFile('photo')) {
                // Hapus foto lama jika bukan URL external
                if ($documentation->photo_path && !str_starts_with($documentation->photo_path, 'http')) {
                    $oldPath = public_path($documentation->photo_path);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }

                $photoPath = $this->uploadImage($request->file('photo'));
            }

            // Update week number jika tanggal berubah
            $date = Carbon::parse($data['activity_date']);
            $weekNumber = $date->weekOfYear;
            $year = $date->year;

            $documentation->update([
                'photo_path' => $photoPath,
                'activity_date' => $data['activity_date'],
                'description' => $data['description'],
                'week_number' => $weekNumber,
                'year' => $year,
            ]);

            return redirect()->back()
                ->with('status', 'Dokumentasi berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['photo' => 'Update gagal: ' . $e->getMessage()]);
        }
    }

    public function destroy(Documentation $documentation)
    {
        // Hapus foto jika bukan URL external
        if ($documentation->photo_path && !str_starts_with($documentation->photo_path, 'http')) {
            $filePath = public_path($documentation->photo_path);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        $documentation->delete();

        return redirect()->back()
            ->with('status', 'Dokumentasi berhasil dihapus.');
    }

    /**
     * Upload image dengan cara yang kompatibel hosting
     */
    private function uploadImage($file): string
    {
        $uploadDir = public_path('uploads/documentations');

        // Pastikan folder ada
        if (!File::isDirectory($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true, true);
        }

        // Generate unique filename
        $extension = $file->getClientOriginalExtension() ?: 'jpg';
        $filename = time() . '_' . uniqid() . '.' . $extension;

        try {
            // Move file ke public folder
            $file->move($uploadDir, $filename);
        } catch (\Exception $e) {
            Log::error('Documentation upload failed: ' . $e->getMessage());
            throw new \RuntimeException('Gagal upload gambar: ' . $e->getMessage());
        }

        return 'uploads/documentations/' . $filename;
    }
}

