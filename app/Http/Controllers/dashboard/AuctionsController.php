<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuctionsController extends Controller
{
    public function index()
    {
        $auctions = Auction::with(['user', 'category'])->latest()->get();
        return view('dashboard.auctions.index', compact('auctions'));
    }

    public function show($id)
    {
        $auction = Auction::with(['user', 'category', 'offers.user'])->findOrFail($id);
        return view('dashboard.auctions.show', compact('auction'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_price' => 'required|numeric|min:0',
            'end_price' => 'nullable|numeric|min:0',
            'status' => 'required|boolean'
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('auctions', 'public');
                $imagePaths[] = $path;
            }
        }

        Auction::create([
            'title' => $request->title,
            'user_id' => $request->user_id,
            'category_id' => $request->category_id,
            'images' => $imagePaths,
            'start_price' => $request->start_price,
            'end_price' => $request->end_price,
            'status' => $request->status
        ]);

        return redirect()->route('auctions.index')->with('success', 'تمت إضافة المزاد بنجاح');
    }

    public function update(Request $request, Auction $auction)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_price' => 'required|numeric|min:0',
            'end_price' => 'nullable|numeric|min:0',
            'status' => 'required|boolean'
        ]);

        $imagePaths = $auction->images ?? [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('auctions', 'public');
                $imagePaths[] = $path;
            }
        }

        $auction->update([
            'title' => $request->title,
            'user_id' => $request->user_id,
            'category_id' => $request->category_id,
            'images' => $imagePaths,
            'start_price' => $request->start_price,
            'end_price' => $request->end_price,
            'status' => $request->status
        ]);

        return redirect()->route('auctions.index')->with('success', 'تم تحديث المزاد بنجاح');
    }

    public function destroy(Auction $auction)
    {
        if ($auction->images) {
            foreach ($auction->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        $auction->delete();
        return redirect()->route('auctions.index')->with('success', 'تم حذف المزاد بنجاح');
    }
}
