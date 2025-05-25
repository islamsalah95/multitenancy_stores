<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class StoreController extends Controller
{
    public function showRegistrationForm()
    {
        if (!View::exists('store.register')) {
            abort(404, 'Registration view not found');
        }
        return view('store.register');
    }

    public function showLoginForm()
    {
        if (!View::exists('store.login')) {
            abort(404, 'Login view not found');
        }
        return view('store.login');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:stores',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
        ]);

        $store = Store::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'description' => $validated['description'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        Auth::guard('store')->login($store);

        return redirect()->route('store.dashboard')
            ->with('success', "Store created successfully! Your store domain is: {$store->domain}");
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('store')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('store/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function dashboard()
    {
        $store = Auth::guard('store')->user();

        // Set tenant context to query from tenant database
        $store->makeCurrent();

        $categories = Category::withCount('products')->get();
        $products = Product::with('category')->latest()->take(5)->get();
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();

        return view('store.dashboard', compact('store', 'categories', 'products', 'totalProducts', 'activeProducts'));
    }

    public function profile()
    {
        $store = Auth::guard('store')->user();
        return view('store.profile', compact('store'));
    }

    public function updateProfile(Request $request)
    {
        $store = Auth::guard('store')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoPath = $logo->store('store-logos', 'public');
            $validated['logo'] = $logoPath;
        }

        $store->update($validated);

        return redirect()->route('store.profile')->with('success', 'Profile updated successfully');
    }

    public function logout(Request $request)
    {
        Auth::guard('store')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}