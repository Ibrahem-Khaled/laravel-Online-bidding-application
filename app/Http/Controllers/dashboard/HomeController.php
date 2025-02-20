<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\Category;
use App\Models\User;
use App\Models\LiveStraming as LiveStreaming;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalAuctions = Auction::count();
        $totalLiveStreams = LiveStreaming::count();
        $totalCategories = Category::count();

        $recentAuctions = Auction::latest()->take(5)->get();
        $recentLiveStreams = LiveStreaming::latest()->take(5)->get();

        return view('dashboard.index', compact(
            'totalUsers',
            'totalAuctions',
            'totalLiveStreams',
            'totalCategories',
            'recentAuctions',
            'recentLiveStreams'
        ));
    }
}
