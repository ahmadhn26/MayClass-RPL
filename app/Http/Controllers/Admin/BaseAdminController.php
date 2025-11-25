<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\Auth;

abstract class BaseAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    protected function render(string $view, array $data = []): ViewContract
    {
        return view($view, array_merge([
            'admin' => Auth::user(),
        ], $data));
    }
}
