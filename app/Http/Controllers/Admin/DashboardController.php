<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Package;
use App\Models\ScheduleSession;
use App\Models\ScheduleTemplate;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends BaseAdminController
{
    public function index(Request $request)
    {
        $stats = [
            'totalRevenue' => $this->formatCurrency($this->sumPaidOrders()),
            'yearRevenue' => $this->formatCurrency($this->sumPaidOrders(now()->year)),
            'totalStudents' => Schema::hasTable('users') ? User::where('role', 'student')->count() : 0,
            'pendingPayments' => Schema::hasTable('orders') ? Order::where('status', 'pending')->count() : 0,
            'averageTicket' => $this->formatCurrency($this->averageTicket()),
            'paidOrders' => Schema::hasTable('orders') ? Order::where('status', 'paid')->count() : 0,
        ];

        $monthlyRevenue = $this->monthlyRevenue();

        $recentStudents = Schema::hasTable('users')
            ? User::query()
                ->where('role', 'student')
                ->latest('created_at')
                ->take(6)
                ->get()
                ->map(function (User $user) {
                    
                    return [
                        'name' => $user->name,
                        'email' => $user->email,
                        'joined_at' => $user->created_at ? $user->created_at->locale('id')->translatedFormat('d F Y') : 'N/A',
                        'student_id' => $user->student_id,
                    ];
                })
            : collect();

        return $this->render('admin.dashboard', [
            'stats' => $stats,
            'monthlyRevenue' => $monthlyRevenue,
            'monthComparison' => $this->monthComparison(),
            'paymentPipeline' => $this->paymentPipeline(),
            'recentPayments' => $this->recentPayments(),
            'topPackages' => $this->topPackages(),
            'recentStudents' => $recentStudents,
            'schedule' => $this->scheduleOverview($request->input('tutor_id')),
        ]);
    }

    private function scheduleOverview($requestedTutor): array
    {
        $tutors = Schema::hasTable('users')
            ? User::query()->where('role', 'tutor')->orderBy('name')->get(['id', 'name'])
            : collect();

        $selectedTutorId = $this->resolveTutorFilter($requestedTutor, $tutors);

        $packages = Schema::hasTable('packages')
            ? Package::orderBy('level')->orderBy('price')->get(['id', 'detail_title'])
            : collect();

        $sessionsReady = Schema::hasTable('schedule_sessions');

        $sessions = $sessionsReady
            ? ScheduleSession::query()
                ->when($selectedTutorId, fn ($query) => $query->where('user_id', $selectedTutorId))
                ->orderBy('start_at')
                ->get()
            : collect();

        if ($sessionsReady && Schema::hasTable('users') && $sessions->isNotEmpty()) {
            $sessions->load('user:id,name');
        }

        if ($sessionsReady && Schema::hasTable('packages') && $sessions->isNotEmpty()) {
            $sessions->load('package:id,detail_title');
        }

        $now = CarbonImmutable::now();

        $sessionPayload = $sessions->map(function (ScheduleSession $session) use ($now) {
            $start = $this->parseScheduleDate($session->start_at);
            $duration = (int) ($session->duration_minutes ?? 90);
            $duration = $duration > 0 ? $duration : 90;
            $end = $start ? $start->addMinutes($duration) : null;

            $timeRange = $start && $end
                ? $start->format('H.i') . ' - ' . $end->format('H.i') . ' WIB'
                : __('Waktu belum ditetapkan');

            $dateKey = $start ? $start->format('Y-m-d') : (string) $session->id;

            return [
                'id' => $session->id,
                'date_key' => $dateKey,
                'weekday' => $start ? $start->locale('id')->translatedFormat('dddd') : __('Tanggal belum ditetapkan'),
                'full_date' => $start ? $start->translatedFormat('d MMMM Y') : '-',
                'label' => $start ? $start->locale('id')->translatedFormat('dddd, D MMMM YYYY') : '-',
                'time_range' => $timeRange,
                'subject' => $session->category ?? '-',
                'title' => $session->title,
                'package' => optional($session->package)->detail_title ?? __('Paket MayClass'),
                'location' => $session->location ?? __('Ruang Virtual'),
                'class_level' => $session->class_level ?? '-',
                'student_count' => $session->student_count,
                'status' => $session->status ?? 'scheduled',
                'tutor' => optional($session->user)->name ?? __('Tutor belum ditetapkan'),
                'start_iso' => $start?->toIso8601String(),
                'is_past' => $start ? $start->lt($now) : false,
            ];
        });

        $upcomingSessions = $sessionPayload->filter(fn ($session) => $session['status'] !== 'cancelled' && ! $session['is_past']);
        $historySessions = $sessionPayload
            ->filter(fn ($session) => $session['status'] !== 'cancelled' && $session['is_past'])
            ->sortByDesc('start_iso')
            ->take(6)
            ->values();
        $cancelledSessions = $sessionPayload
            ->filter(fn ($session) => $session['status'] === 'cancelled')
            ->sortByDesc('start_iso')
            ->take(6)
            ->values();

        $upcomingDays = $upcomingSessions
            ->groupBy('date_key')
            ->map(function (Collection $items) {
                $first = $items->first();

                return [
                    'weekday' => $first['weekday'],
                    'full_date' => $first['full_date'],
                    'label' => $first['label'],
                    'items' => $items->map(function (array $item) {
                        return [
                            'id' => $item['id'],
                            'title' => $item['title'],
                            'subject' => $item['subject'],
                            'package' => $item['package'],
                            'time_range' => $item['time_range'],
                            'location' => $item['location'],
                            'class_level' => $item['class_level'],
                            'student_count' => $item['student_count'],
                            'tutor' => $item['tutor'],
                            'status' => $item['status'],
                        ];
                    })->values(),
                ];
            })
            ->sortKeys()
            ->values();

        $templatesReady = Schema::hasTable('schedule_templates') && $selectedTutorId;

        $templates = $templatesReady
            ? ScheduleTemplate::query()
                ->where('user_id', $selectedTutorId)
                ->orderBy('day_of_week')
                ->orderBy('start_time')
                ->with(['package:id,detail_title', 'user:id,name'])
                ->get()
                ->map(function (ScheduleTemplate $template) {
                    $nextDate = $this->nextDateForDay($template->day_of_week);

                    return [
                        'id' => $template->id,
                        'package_id' => $template->package_id,
                        'title' => $template->title,
                        'category' => $template->category,
                        'class_level' => $template->class_level,
                        'location' => $template->location,
                        'start_time' => $template->start_time,
                        'duration_minutes' => $template->duration_minutes,
                        'student_count' => $template->student_count,
                        'user_id' => $template->user_id,
                        'package_label' => optional($template->package)->detail_title ?? __('Paket MayClass'),
                        'reference_date_value' => $nextDate?->toDateString(),
                        'reference_date_label' => $nextDate ? $nextDate->locale('id')->translatedFormat('dddd, D MMMM YYYY') : null,
                    ];
                })
            : collect();

        $templateTotal = Schema::hasTable('schedule_templates')
            ? ($selectedTutorId ? $templates->count() : ScheduleTemplate::count())
            : 0;

        return [
            'tutors' => $tutors,
            'selectedTutorId' => $selectedTutorId,
            'activeFilter' => $selectedTutorId ? (string) $selectedTutorId : 'all',
            'packages' => $packages,
            'upcomingDays' => $upcomingDays,
            'historySessions' => $historySessions,
            'cancelledSessions' => $cancelledSessions,
            'templates' => $templates,
            'metrics' => [
                'upcoming' => $upcomingSessions->count(),
                'history' => $historySessions->count(),
                'cancelled' => $cancelledSessions->count(),
                'templates' => $templateTotal,
            ],
            'referenceDate' => CarbonImmutable::now()->toDateString(),
            'ready' => $sessionsReady,
        ];
    }

    private function resolveTutorFilter($requestedTutor, Collection $tutors): ?int
    {
        if ($requestedTutor === 'all') {
            return null;
        }

        if ($requestedTutor !== null && $requestedTutor !== '') {
            $candidate = (int) $requestedTutor;
            $match = $tutors->firstWhere('id', $candidate);

            if ($match) {
                return $match->id;
            }
        }

        return $tutors->count() === 1 ? optional($tutors->first())->id : null;
    }

    private function parseScheduleDate($value): ?CarbonImmutable
    {
        if (! $value) {
            return null;
        }

        try {
            return CarbonImmutable::parse($value);
        } catch (\Throwable $exception) {
            return null;
        }
    }

    private function nextDateForDay(?int $dayOfWeek): ?CarbonImmutable
    {
        if ($dayOfWeek === null) {
            return null;
        }

        // Convert ISO day (1=Mon, 7=Sun) to Carbon day (0=Sun, 1=Mon, 6=Sat)
        $carbonDay = $dayOfWeek === 7 ? 0 : $dayOfWeek;

        $now = CarbonImmutable::now();

        if ($now->dayOfWeek === $carbonDay) {
            return $now;
        }

        return $now->next($carbonDay);
    }

    private function sumPaidOrders(?int $year = null): float
    {
        if (! Schema::hasTable('orders')) {
            return 0.0;
        }

        return Order::query()
            ->where('status', 'paid')
            ->when($year, function ($query) use ($year) {
                $query->whereYear('paid_at', $year);
            })
            ->sum('total');
    }

    private function monthlyRevenue(): Collection
    {
        if (! Schema::hasTable('orders')) {
            return collect();
        }

        $year = now()->year;
        // Using a plain string keeps the raw expression reusable in raw clauses
        $paidReference = 'COALESCE(paid_at, updated_at, created_at)';

        $orders = Order::query()
            ->selectRaw('MONTH(' . $paidReference . ') as month, SUM(total) as total')
            ->where('status', 'paid')
            ->whereRaw('YEAR(' . $paidReference . ') = ?', [$year])
            ->groupByRaw('MONTH(' . $paidReference . ')')
            ->pluck('total', 'month');

        return collect(range(1, 12))->map(function ($month) use ($orders, $year) {
            $date = CarbonImmutable::createFromDate($year, $month, 1);

            return [
                'label' => $date->locale('id')->isoFormat('MMM'),
                'value' => (float) ($orders[$month] ?? 0),
                'formatted' => $this->formatCurrency((float) ($orders[$month] ?? 0)),
            ];
        });
    }

    private function averageTicket(): float
    {
        if (! Schema::hasTable('orders')) {
            return 0.0;
        }

        $count = Order::where('status', 'paid')->count();

        if ($count === 0) {
            return 0.0;
        }

        return $this->sumPaidOrders() / $count;
    }

    private function monthComparison(): array
    {
        if (! Schema::hasTable('orders')) {
            return [
                'formatted' => $this->formatCurrency(0),
                'delta' => 0.0,
                'direction' => 'flat',
            ];
        }

        $currentDate = now();
        $previousDate = now()->subMonth();

        $current = $this->sumPaidOrdersForMonth($currentDate->year, $currentDate->month);
        $previous = $this->sumPaidOrdersForMonth($previousDate->year, $previousDate->month);

        if ($previous === 0.0) {
            $delta = $current > 0 ? 100.0 : 0.0;
        } else {
            $delta = (($current - $previous) / $previous) * 100;
        }

        return [
            'formatted' => $this->formatCurrency($current),
            'delta' => round($delta, 1),
            'direction' => $current <=> $previous,
        ];
    }

    private function paymentPipeline(): array
    {
        if (! Schema::hasTable('orders')) {
            return [
                'total' => 0,
                'rows' => collect([
                    ['status' => 'paid', 'label' => $this->statusLabel('paid'), 'count' => 0, 'percentage' => 0],
                    ['status' => 'pending', 'label' => $this->statusLabel('pending'), 'count' => 0, 'percentage' => 0],
                    ['status' => 'failed', 'label' => $this->statusLabel('failed'), 'count' => 0, 'percentage' => 0],
                ]),
            ];
        }

        $counts = Order::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $total = (int) $counts->sum();

        $rows = collect(['paid', 'pending', 'rejected', 'failed', 'initiated'])->map(function (string $status) use ($counts, $total) {
            $count = (int) ($counts[$status] ?? 0);

            return [
                'status' => $status,
                'label' => $this->statusLabel($status),
                'count' => $count,
                'percentage' => $total > 0 ? round(($count / $total) * 100) : 0,
            ];
        });

        return [
            'total' => $total,
            'rows' => $rows,
        ];
    }

    private function recentPayments(): Collection
    {
        if (! Schema::hasTable('orders')) {
            return collect();
        }

        $query = Order::query();

        $usersReady = Schema::hasTable('users');

        if ($usersReady) {
            $query->with(['user:id,name']);
        }

        $packagesReady = Schema::hasTable('packages');

        if ($packagesReady) {
            $query->with(['package:id,detail_title']);
        }

        return $query
            ->latest('created_at')
            ->take(6)
            ->get()
            ->map(function (Order $order) use ($packagesReady, $usersReady) {
                $packageTitle = $packagesReady
                    ? optional($order->getRelationValue('package'))->detail_title ?? 'Paket tidak tersedia'
                    : 'Tabel paket belum tersedia';

                $studentName = $usersReady
                    ? optional($order->getRelationValue('user'))->name ?? 'Tanpa nama'
                    : 'Data siswa belum tersedia';

                return [
                    'invoice' => 'INV-' . str_pad((string) $order->id, 5, '0', STR_PAD_LEFT),
                    'student' => $studentName,
                    'package' => $packageTitle,
                    'total' => $this->formatCurrency((float) $order->total),
                    'status' => $order->status,
                    'status_label' => $this->statusLabel($order->status),
                    'paid_at' => $order->paid_at ? $order->paid_at->locale('id')->translatedFormat('d M Y') : 'Belum dibayar',
                ];
            });
    }

    private function topPackages(): Collection
    {
        if (! Schema::hasTable('packages') || ! Schema::hasTable('orders')) {
            return collect();
        }

        return Package::query()
            ->select('packages.id', 'packages.detail_title as title')
            ->selectRaw('COUNT(orders.id) as orders_count')
            ->selectRaw('COALESCE(SUM(CASE WHEN orders.status = ? THEN orders.total ELSE 0 END), 0) as revenue', ['paid'])
            ->leftJoin('orders', 'orders.package_id', '=', 'packages.id')
            ->groupBy('packages.id', 'packages.detail_title')
            ->orderByDesc('revenue')
            ->orderByDesc('orders_count')
            ->take(4)
            ->get()
            ->map(function ($package) {
                return [
                    'title' => $package->title,
                    'orders' => (int) $package->orders_count,
                    'revenue' => $this->formatCurrency((float) $package->revenue),
                ];
            });
    }

    private function sumPaidOrdersForMonth(int $year, int $month): float
    {
        if (! Schema::hasTable('orders')) {
            return 0.0;
        }

        return Order::query()
            ->where('status', 'paid')
            ->whereYear('paid_at', $year)
            ->whereMonth('paid_at', $month)
            ->sum('total');
    }

    private function formatCurrency(float $value): string
    {
        if ($value >= 1000000000) {
            return 'Rp ' . number_format($value / 1000000000, 1, ',', '.') . 'M';
        }

        if ($value >= 1000000) {
            return 'Rp ' . number_format($value / 1000000, 1, ',', '.') . 'Jt';
        }

        return 'Rp ' . number_format($value, 0, ',', '.');
    }

    private function statusLabel(?string $status): string
    {
        return match ($status) {
            'paid' => 'Lunas',
            'pending' => 'Menunggu',
            'rejected' => 'Ditolak',
            'failed' => 'Kedaluwarsa',
            'initiated' => 'Draft',
            default => ucfirst((string) $status),
        };
    }
}
