<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\LiveStraming as LiveStreaming;

class LiveStreamingsController extends Controller
{

    public function index(Request $request)
    {
        $query = LiveStreaming::query();

        // البحث بالعنوان
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->input('search') . '%');
        }

        // التصفية بالحالة
        if ($request->has('status') && in_array($request->input('status'), ['0', '1'])) {
            $query->where('status', $request->input('status'));
        }

        // الترتيب
        if ($request->has('sort')) {
            switch ($request->input('sort')) {
                case 'title_asc':
                    $query->orderBy('title', 'asc');
                    break;
                case 'title_desc':
                    $query->orderBy('title', 'desc');
                    break;
                case 'created_at_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'created_at_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->latest();
        }

        $streams = $query->paginate(10);


        return view('dashboard.live_streamings.index', compact('streams'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'user_id' => 'required|exists:users,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // 'status' => 'required|boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('live-streams', 'public');
        }

        LiveStreaming::create($validated);
        return redirect()->route('live-streamings.index')->with('success', 'تمت الإضافة بنجاح');
    }

    public function update(Request $request, LiveStreaming $liveStreaming)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'user_id' => 'required|exists:users,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // 'status' => 'required|boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($liveStreaming->image) {
                Storage::disk('public')->delete($liveStreaming->image);
            }
            $validated['image'] = $request->file('image')->store('live-streams', 'public');
        }

        $liveStreaming->update($validated);
        return redirect()->route('live-streamings.index')->with('success', 'تم التحديث بنجاح');
    }

    public function destroy(LiveStreaming $liveStreaming)
    {
        if ($liveStreaming->image) {
            Storage::disk('public')->delete($liveStreaming->image);
        }
        $liveStreaming->delete();
        return redirect()->route('live-streamings.index')->with('success', 'تم الحذف بنجاح');
    }
}
