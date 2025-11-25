<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

abstract class BaseTutorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:tutor']);
    }

    protected function render(string $view, array $data = []): ViewContract
    {
        $tutor = Auth::user();

        $tutorProfile = null;

        if ($tutor && Schema::hasTable('tutor_profiles')) {
            $tutor->loadMissing('tutorProfile');
            $tutorProfile = $tutor->tutorProfile;
        }

        return view($view, array_merge([
            'tutor' => $tutor,
            'tutorProfile' => $tutorProfile,
        ], $data));
    }
}
