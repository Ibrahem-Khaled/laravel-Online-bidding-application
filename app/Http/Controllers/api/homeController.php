<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\AuctionOffer;
use App\Models\Category;
use App\Models\LiveStraming;
use App\Models\Notification;
use App\Models\Slider;
use Illuminate\Http\Request;

class homeController extends Controller
{
    public function getCategories()
    {
        $categories = Category::where('status', 1)->get();

        return response()->json([
            'categories' => $categories,
        ]);
    }

    public function publicSlider()
    {
        $sliders = Slider::where('status', 1)
            ->whereNull('category_id')
            ->get();

        return response()->json([
            'sliders' => $sliders
        ]);
    }

    public function getOffersByCategory($id)
    {
        $auctions = Auction::where('category_id', $id)
            ->get();

        return response()->json([
            'auctions' => $auctions
        ]);
    }

    public function getLiveStreamings()
    {
        $liveStreamings = LiveStraming::where('status', 1)
            ->with('user', 'category')
            ->get();

        return response()->json([
            'liveStreamings' => $liveStreamings
        ]);
    }


    public function getNotifications()
    {
        $notifications = Notification::where('user_id', auth()->guard('api')->user()->id)
            ->orWhere('user_id', null)
            ->get();

        return response()->json([
            'notifications' => $notifications
        ]);
    }
}
