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
            'image' => 'nullable|image|max:10240',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('landing-images', 'public');
            $data['image'] = 'storage/' . $path;
        } elseif ($request->input('section') === 'article' && !empty($data['content']['link'])) {
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(5)->get($data['content']['link']);
                if ($response->successful()) {
                    $html = $response->body();
                    if (preg_match('/<meta property="og:image" content="([^"]+)"/', $html, $matches)) {
                        $data['image'] = $matches[1];
                    }
                }
            } catch (\Exception $e) {
                // Ignore errors, keep image null
            }
        }

        \App\Models\LandingContent::create($data);

        return redirect()->back()->with('status', 'Konten berhasil ditambahkan!');
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
            'image' => 'nullable|image|max:10240',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('landing-images', 'public');
            $data['image'] = 'storage/' . $path;
        } elseif ($landingContent->section === 'article' && !empty($data['content']['link'])) {
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(5)->get($data['content']['link']);
                if ($response->successful()) {
                    $html = $response->body();
                    if (preg_match('/<meta property="og:image" content="([^"]+)"/', $html, $matches)) {
                        $data['image'] = $matches[1];
                    }
                }
            } catch (\Exception $e) {
                // Ignore errors
            }
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
