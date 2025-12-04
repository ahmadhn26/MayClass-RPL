<?php

namespace App\Http\Controllers\Admin;

use App\Models\Documentation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DocumentationController extends BaseAdminController
{
    public function index()
    {
        $documentations = Documentation::where('is_active', true)
            ->latestOrder()
            ->get()
            ->map(function ($doc) {
                return [
                    'id' => $doc->id,
                    'photo_url' => asset('storage/' . $doc->photo_path),
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

        // Upload foto
        $photoPath = $request->file('photo')->store('documentations', 'public');

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
    }

    public function update(Request $request, Documentation $documentation)
    {
        $data = $request->validate([
            'activity_date' => ['required', 'date'],
            'description' => ['required', 'string', 'max:1000'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        $photoPath = $documentation->photo_path;

        // Upload foto baru jika ada
        if ($request->hasFile('photo')) {
            // Hapus foto lama
            if (Storage::disk('public')->exists($documentation->photo_path)) {
                Storage::disk('public')->delete($documentation->photo_path);
            }
            $photoPath = $request->file('photo')->store('documentations', 'public');
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
    }

    public function destroy(Documentation $documentation)
    {
        // Hapus foto
        if (Storage::disk('public')->exists($documentation->photo_path)) {
            Storage::disk('public')->delete($documentation->photo_path);
        }

        $documentation->delete();

        return redirect()->back()
            ->with('status', 'Dokumentasi berhasil dihapus.');
    }
}
