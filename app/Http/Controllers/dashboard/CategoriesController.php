<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoriesController extends Controller
{
    // عرض جميع الفئات
    public function index()
    {
        $categories = Category::latest()->get();
        return view('dashboard.categories.index', compact('categories'));
    }

    // حفظ الفئة الجديدة
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'slug' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|boolean',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
            $validated['image'] = asset('storage/' . $imagePath); // حفظ الرابط الكامل للصورة
        }

        Category::create($validated);
        return redirect()->route('categories.index')->with('success', 'تمت إضافة الفئة بنجاح.');
    }

    // تحديث بيانات الفئة
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'slug' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($category->image) {
                $oldImagePath = str_replace(asset('storage/'), '', $category->image);
                Storage::disk('public')->delete($oldImagePath);
            }
            $imagePath = $request->file('image')->store('categories', 'public');
            $validated['image'] = asset('storage/' . $imagePath); // حفظ الرابط الكامل للصورة
        }

        $category->update($validated);
        return redirect()->route('categories.index')->with('success', 'تم تحديث الفئة بنجاح.');
    }

    // حذف الفئة
    public function destroy(Category $category)
    {
        if ($category->image) {
            $oldImagePath = str_replace(asset('storage/'), '', $category->image);
            Storage::disk('public')->delete($oldImagePath);
        }
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'تم حذف الفئة بنجاح.');
    }
}