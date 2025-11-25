<?php

namespace App\Http\Middleware;

use App\Support\StudentAccess;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentIsSubscribed
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! StudentAccess::hasActivePackage($user)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Langganan aktif diperlukan untuk mengakses fitur ini.',
                ], Response::HTTP_PAYMENT_REQUIRED);
            }

            return redirect()
                ->route('student.dashboard')
                ->with('subscription_notice', 'Aktifkan paket belajar untuk membuka materi, kuis, dan jadwal MayClass.')
                ->with('subscription_redirect', $request->fullUrl());
        }

        return $next($request);
    }
}
