<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use Illuminate\View\View;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
class CustomerAuthController extends Controller
{
    public function showRegistrationForm()
    {
        return view('client.auth.register');
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the customer record
        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Log the customer in after registration
        Auth::guard('customer')->login($customer);

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Registration successful!',
                'redirect' => route('client.dashboard'),
            ]);
        }

        return redirect()->route('client.dashboard');
    }



    public function showLoginForm()
    {
        return view('client.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('customer')->attempt($credentials)) {
            $customer = Auth::guard('customer')->user();
            $customer->last_login_at = now();
            $customer->save();

            if ($request->ajax()) {
                return response()->json([
                    'message' => 'Login successful!',
                    'redirect' => route('client.dashboard'),
                ]);
            }

            return redirect()->intended(route('dashboard'));
        }

        if ($request->ajax()) {
            return response()->json([
                'errors' => ['email' => 'Invalid credentials'],
            ], 422);
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function create(): View
    {
        return view('admin.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'firstname' => $request->firstname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('admin.dashboard', absolute: false));
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Logout successful!',
                'redirect' => route('client.login'),
            ]);
        }

        return redirect()->route('client.login');
    }


    public function dashboard()
    {
        return view('client.dashboard');
    }
}
