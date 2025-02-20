<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\AuctionOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class auctionController extends Controller
{

    public function create(Request $request, $id)
    {
        $user = auth()->guard('api')->user();

        // التحقق من البيانات
        $valed = Validator::make($request->all(), [
            'title' => 'required',
            'slug' => 'nullable',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_price' => 'required',
            'end_price' => 'nullable',
        ]);

        if ($valed->fails()) {
            return response()->json($valed->errors(), 422); // إرجاع الأخطاء مع رمز الحالة 422
        }

        // تخزين الصور في مجلد storage
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('auctions', 'public');
                $imagePaths[] = $path;
            }
        }

        // إنشاء المزاد
        $auction = Auction::create([
            'user_id' => $user->id,
            'category_id' => $id,
            'title' => $request->title,
            'slug' => $request->slug,
            'images' => $imagePaths,
            'start_price' => $request->start_price,
            'end_price' => $request->end_price,
        ]);

        return response()->json($auction, 201); // إرجاع المزاد مع رمز الحالة 201
    }

    public function getAuctionByCategory($id)
    {
        $auction = Auction::findOrFail($id);

        $auction->load('category', 'user', 'offers.user');

        return response()->json($auction);
    }

    public function addPriceOffer(Request $request, $id)
    {
        $user = auth()->guard('api')->user();

        AuctionOffer::create([
            'user_id' => $user->id,
            'auction_id' => $id,
            'offer_price' => $request->price
        ]);

        return response()->json('offer added successfully');
    }
}
