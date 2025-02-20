<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::latest()->get();
        $categories = Category::all();
        return view('dashboard.sliders.index', compact('sliders', 'categories'));
    }
    // حفظ السلايدر الجديد
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|boolean',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        // تحميل الصورة وحفظ الرابط
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('sliders', 'public');
            $imageUrl = asset('storage/' . $imagePath);
        }

        // إنشاء السلايدر
        Slider::create([
            'title' => $request->title,
            'image' => $imageUrl,
            'status' => $request->status,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('sliders.index')->with('success', 'تمت إضافة السلايدر بنجاح.');
    }

    // تحديث السلايدر
    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|boolean',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        // تحديث الصورة إذا تم تحميل صورة جديدة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($slider->image) {
                $oldImagePath = str_replace(asset('storage/'), '', $slider->image);
                Storage::disk('public')->delete($oldImagePath);
            }

            // تحميل الصورة الجديدة وحفظ الرابط
            $imagePath = $request->file('image')->store('sliders', 'public');
            $imageUrl = asset('storage/' . $imagePath);
            $slider->image = $imageUrl;
        }

        // تحديث البيانات
        $slider->update([
            'title' => $request->title,
            'status' => $request->status,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('sliders.index')->with('success', 'تم تحديث السلايدر بنجاح.');
    }

    // حذف السلايدر
    public function destroy(Slider $slider)
    {
        // حذف الصورة إذا كانت موجودة
        if ($slider->image) {
            $oldImagePath = str_replace(asset('storage/'), '', $slider->image);
            Storage::disk('public')->delete($oldImagePath);
        }

        // حذف السلايدر
        $slider->delete();

        return redirect()->route('sliders.index')->with('success', 'تم حذف السلايدر بنجاح.');
    }
}
