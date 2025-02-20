<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class usersController extends Controller
{
    // عرض جميع المستخدمين
    public function index()
    {
        $stats = [
            'total' => User::count(),
            'active' => User::where('status', 1)->count(),
            'admins' => User::where('role', 'admin')->count(),
            'users' => User::where('role', 'user')->count(),
        ];

        $users = User::latest()->get();
        return view('dashboard.users.index', compact('stats', 'users'));
    }

    // حفظ المستخدم الجديد
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users',
            'phone' => 'nullable|unique:users',
            'address' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role' => 'required|in:admin,user',
            'status' => 'required|boolean',
            'password' => 'required|min:8',
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        User::create($validated);
        return redirect()->route('users.index')->with('success', 'تمت إضافة المستخدم بنجاح.');
    }

    // تحديث بيانات المستخدم
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|unique:users,phone,' . $user->id,
            'address' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role' => 'required|in:admin,user',
            'status' => 'required|boolean',
            'password' => 'nullable|min:8',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        return redirect()->route('users.index')->with('success', 'تم تحديث المستخدم بنجاح.');
    }

    // حذف المستخدم
    public function destroy(User $user)
    {
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'تم حذف المستخدم بنجاح.');
    }
}