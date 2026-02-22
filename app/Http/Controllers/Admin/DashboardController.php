<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelOrder;
use App\Models\HotelView;
use App\Models\Lugar;
use App\Models\Message;
use App\Models\Order;
use App\Models\PageView;
use App\Models\Promotion;
use App\Models\PromotionUse;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Revenue ──────────────────────────────────────────────────
        $membershipRevenue = (float) Order::where('status', 'completed')->sum('total');
        $hotelRevenue      = (float) HotelOrder::where('status', 'completed')->sum('amount');
        $totalRevenue      = $membershipRevenue + $hotelRevenue;

        $thisMonthRevenue = (float) Order::where('status', 'completed')
            ->whereYear('completed_at', now()->year)
            ->whereMonth('completed_at', now()->month)
            ->sum('total')
            + (float) HotelOrder::where('status', 'completed')
            ->whereYear('completed_at', now()->year)
            ->whereMonth('completed_at', now()->month)
            ->sum('amount');

        $lastMonthRevenue = (float) Order::where('status', 'completed')
            ->whereYear('completed_at', now()->subMonth()->year)
            ->whereMonth('completed_at', now()->subMonth()->month)
            ->sum('total')
            + (float) HotelOrder::where('status', 'completed')
            ->whereYear('completed_at', now()->subMonth()->year)
            ->whereMonth('completed_at', now()->subMonth()->month)
            ->sum('amount');

        $revenueMoM = $lastMonthRevenue > 0
            ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : ($thisMonthRevenue > 0 ? 100 : 0);

        // ── Orders ───────────────────────────────────────────────────
        $membershipOrders = [
            'total'     => Order::count(),
            'pending'   => Order::where('status', 'pending')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];
        $membershipOrders['conversion'] = $membershipOrders['total'] > 0
            ? round(($membershipOrders['completed'] / $membershipOrders['total']) * 100, 1)
            : 0;

        $hotelOrders = [
            'total'     => HotelOrder::count(),
            'pending'   => HotelOrder::where('status', 'pending')->count(),
            'completed' => HotelOrder::where('status', 'completed')->count(),
            'cancelled' => HotelOrder::where('status', 'cancelled')->count(),
        ];
        $hotelOrders['conversion'] = $hotelOrders['total'] > 0
            ? round(($hotelOrders['completed'] / $hotelOrders['total']) * 100, 1)
            : 0;

        // Lost checkouts: pending orders 72+ hours old
        $lostMembership      = Order::where('status', 'pending')->where('created_at', '<', now()->subHours(72))->count();
        $lostHotel           = HotelOrder::where('status', 'pending')->where('created_at', '<', now()->subHours(72))->count();
        $lostMembershipValue = (float) Order::where('status', 'pending')->where('created_at', '<', now()->subHours(72))->sum('total');
        $lostHotelValue      = (float) HotelOrder::where('status', 'pending')->where('created_at', '<', now()->subHours(72))->sum('amount');
        $totalLost      = $lostMembership + $lostHotel;
        $totalLostValue = $lostMembershipValue + $lostHotelValue;

        $totalPending = $membershipOrders['pending'] + $hotelOrders['pending'] + Hotel::where('status', 'pending')->count();

        // ── Users ────────────────────────────────────────────────────
        $users = [
            'total'     => User::count(),
            'premium'   => User::whereNotNull('premium_expires_at')
                               ->where('premium_expires_at', '>', now())
                               ->where('is_admin', false)
                               ->count(),
            'new_month' => User::where('created_at', '>=', now()->startOfMonth())->count(),
            'new_week'  => User::where('created_at', '>=', now()->startOfWeek())->count(),
            'admins'    => User::where('is_admin', true)->count(),
        ];

        // ── Hotels ───────────────────────────────────────────────────
        $hotels = [
            'total'     => Hotel::count(),
            'active'    => Hotel::where('status', 'active')->count(),
            'pending'   => Hotel::where('status', 'pending')->count(),
            'rejected'  => Hotel::where('status', 'rejected')->count(),
            'suspended' => Hotel::where('status', 'suspended')->count(),
            'featured'  => Hotel::where('featured', true)->count(),
        ];

        $hotelsByTier = DB::table('hotels')
            ->join('hotel_plans', 'hotels.plan_id', '=', 'hotel_plans.id')
            ->select('hotel_plans.tier', 'hotel_plans.name as plan_name', DB::raw('count(*) as count'))
            ->where('hotels.status', 'active')
            ->groupBy('hotel_plans.tier', 'hotel_plans.name')
            ->orderBy('hotel_plans.tier')
            ->get();

        $hotelViewsTotal30 = HotelView::where('viewed_date', '>=', now()->subDays(30)->toDateString())->count();

        // ── Messages ─────────────────────────────────────────────────
        $messages = [
            'total'  => Message::count(),
            'unread' => Message::where('is_read', false)->count(),
            'today'  => Message::whereDate('created_at', today())->count(),
            'week'   => Message::where('created_at', '>=', now()->startOfWeek())->count(),
        ];

        // ── Promotions ───────────────────────────────────────────────
        $promotionsStats = [
            'total'          => Promotion::count(),
            'active'         => Promotion::where('is_active', true)->count(),
            'total_discount' => (float) PromotionUse::sum('discount_amount'),
            'total_uses'     => PromotionUse::count(),
        ];

        // ── Content ──────────────────────────────────────────────────
        $totalLugares = Lugar::count();

        // ── Chart: Revenue last 12 months ────────────────────────────
        $spanishMonths = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
        $revenueLabels     = [];
        $revenueMembership = [];
        $revenueHotel      = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenueLabels[]     = $spanishMonths[$month->month - 1] . " '" . $month->format('y');
            $revenueMembership[] = (float) Order::where('status', 'completed')
                ->whereYear('completed_at', $month->year)
                ->whereMonth('completed_at', $month->month)
                ->sum('total');
            $revenueHotel[] = (float) HotelOrder::where('status', 'completed')
                ->whereYear('completed_at', $month->year)
                ->whereMonth('completed_at', $month->month)
                ->sum('amount');
        }

        // ── Chart: New users last 30 days ────────────────────────────
        $userLabels = [];
        $userCounts = [];
        for ($i = 29; $i >= 0; $i--) {
            $day          = now()->subDays($i);
            $userLabels[] = $day->format('d/m');
            $userCounts[] = User::whereDate('created_at', $day->toDateString())->count();
        }

        // ── Chart: Hotel views last 30 days ──────────────────────────
        $hotelViewLabels = [];
        $hotelViewCounts = [];
        $viewsByDate     = HotelView::where('viewed_date', '>=', now()->subDays(29)->toDateString())
            ->select('viewed_date', DB::raw('count(*) as count'))
            ->groupBy('viewed_date')
            ->pluck('count', 'viewed_date')
            ->toArray();

        for ($i = 29; $i >= 0; $i--) {
            $day               = now()->subDays($i);
            $hotelViewLabels[] = $day->format('d/m');
            $hotelViewCounts[] = $viewsByDate[$day->toDateString()] ?? 0;
        }

        // ── Recent activity ──────────────────────────────────────────
        $recentOrders      = Order::with(['user', 'plan'])->latest()->take(5)->get();
        $recentHotelOrders = HotelOrder::with(['user', 'hotel', 'plan'])->latest()->take(5)->get();

        // ── Traffic / Page views ─────────────────────────────────────
        $trafficKpis = [
            'total'   => PageView::count(),
            'month'   => PageView::where('viewed_date', '>=', now()->startOfMonth()->toDateString())->count(),
            'week'    => PageView::where('viewed_date', '>=', now()->startOfWeek()->toDateString())->count(),
            'today'   => PageView::where('viewed_date', today()->toDateString())->count(),
        ];

        // Daily views last 30 days split by page group
        $pageLabels       = ['inicio', 'lugares', 'lugar', 'guias', 'contacto', 'hoteles', 'hotel'];
        $trafficByDateRaw = PageView::where('viewed_date', '>=', now()->subDays(29)->toDateString())
            ->select('viewed_date', 'page', DB::raw('count(*) as cnt'))
            ->groupBy('viewed_date', 'page')
            ->get()
            ->groupBy('viewed_date');

        $trafficDailyLabels = [];
        $trafficDailyTotal  = [];
        $trafficDailyGroups = ['lugar' => [], 'hotel' => [], 'otros' => []]; // stacked groups

        for ($i = 29; $i >= 0; $i--) {
            $day                  = now()->subDays($i)->toDateString();
            $trafficDailyLabels[] = now()->subDays($i)->format('d/m');
            $dayRows              = $trafficByDateRaw[$day] ?? collect();
            $dayTotal             = $dayRows->sum('cnt');
            $trafficDailyTotal[]  = $dayTotal;

            $lugarViews = $dayRows->whereIn('page', ['lugar'])->sum('cnt');
            $hotelViews = $dayRows->whereIn('page', ['hotel'])->sum('cnt');
            $otherViews = $dayRows->whereNotIn('page', ['lugar', 'hotel'])->sum('cnt');

            $trafficDailyGroups['lugar'][] = $lugarViews;
            $trafficDailyGroups['hotel'][] = $hotelViews;
            $trafficDailyGroups['otros'][] = $otherViews;
        }

        // Top pages (all time)
        $pageLabelsMap = [
            'inicio'   => 'Inicio',
            'lugares'  => 'Listado de Lugares',
            'lugar'    => 'Páginas de Lugares',
            'guias'    => 'Guías',
            'contacto' => 'Contacto',
            'hoteles'  => 'Listado de Hoteles',
            'hotel'    => 'Páginas de Hoteles',
        ];

        $topPages = PageView::select('page', DB::raw('count(*) as total'))
            ->groupBy('page')
            ->orderByDesc('total')
            ->get()
            ->map(fn($r) => [
                'page'  => $pageLabelsMap[$r->page] ?? $r->page,
                'total' => $r->total,
                'month' => PageView::where('page', $r->page)
                               ->where('viewed_date', '>=', now()->startOfMonth()->toDateString())
                               ->count(),
            ]);

        // Top individual lugares (by page_views, last 30 days + all time)
        $topLugares = PageView::where('page', 'lugar')
            ->select('entity_slug', DB::raw('count(*) as total'))
            ->groupBy('entity_slug')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(function ($r) {
                $lugar = Lugar::where('slug', $r->entity_slug)->first();
                return [
                    'title' => $lugar->title ?? $r->entity_slug,
                    'slug'  => $r->entity_slug,
                    'total' => $r->total,
                    'month' => PageView::where('page', 'lugar')
                                   ->where('entity_slug', $r->entity_slug)
                                   ->where('viewed_date', '>=', now()->startOfMonth()->toDateString())
                                   ->count(),
                ];
            });

        // Top individual hotels (by page_views all time)
        $topHoteles = PageView::where('page', 'hotel')
            ->select('entity_slug', DB::raw('count(*) as total'))
            ->groupBy('entity_slug')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(function ($r) {
                $hotel = Hotel::where('slug', $r->entity_slug)->first();
                return [
                    'name'  => $hotel->name ?? $r->entity_slug,
                    'slug'  => $r->entity_slug,
                    'total' => $r->total,
                    'month' => PageView::where('page', 'hotel')
                                   ->where('entity_slug', $r->entity_slug)
                                   ->where('viewed_date', '>=', now()->startOfMonth()->toDateString())
                                   ->count(),
                ];
            });

        return view('admin.dashboard', compact(
            'totalRevenue', 'membershipRevenue', 'hotelRevenue',
            'thisMonthRevenue', 'lastMonthRevenue', 'revenueMoM',
            'membershipOrders', 'hotelOrders',
            'lostMembership', 'lostHotel', 'totalLost', 'totalLostValue',
            'lostMembershipValue', 'lostHotelValue', 'totalPending',
            'users', 'hotels', 'hotelsByTier', 'hotelViewsTotal30',
            'messages', 'promotionsStats', 'totalLugares',
            'revenueLabels', 'revenueMembership', 'revenueHotel',
            'userLabels', 'userCounts',
            'hotelViewLabels', 'hotelViewCounts',
            'recentOrders', 'recentHotelOrders',
            'trafficKpis', 'trafficDailyLabels', 'trafficDailyTotal',
            'trafficDailyGroups', 'topPages', 'topLugares', 'topHoteles'
        ));
    }
}
