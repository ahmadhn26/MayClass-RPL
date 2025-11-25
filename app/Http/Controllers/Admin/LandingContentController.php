<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        $data = $request->validate([
            'section' => 'required|string',
            'title' => 'nullable|string',
            'content' => 'required|array',
            'content.link' => 'nullable|url',
            'image' => 'nullable|image|max:2048',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('landing-images', 'public');
            $data['image'] = 'storage/' . $path;
        }

        \App\Models\LandingContent::create($data);

        return redirect()->back()->with('status', 'Konten berhasil ditambahkan!');
    }

    public function update(Request $request, \App\Models\LandingContent $landingContent)
    {
        $data = $request->validate([
            'title' => 'nullable|string',
            'content' => 'required|array',
            'content.link' => 'nullable|url',
            'image' => 'nullable|image|max:2048',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('landing-images', 'public');
            $data['image'] = 'storage/' . $path;
        }

        $landingContent->update($data);

        return redirect()->back()->with('status', 'Konten berhasil diperbarui!');
    }

    public function destroy(\App\Models\LandingContent $landingContent)
    {
        $landingContent->delete();
        return redirect()->back()->with('status', 'Konten berhasil dihapus!');
    }
}
