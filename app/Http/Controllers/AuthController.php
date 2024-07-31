<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AuthController extends Controller
{
	public function getSignup()
	{
		return Inertia::render("Auth/signup");
	}
	public function getLogin(): Response
	{
		return Inertia::render("Auth/login");
	}

	public function login(Request $request)
	{
		$credentials = $request->validate([
			'email' => ['required', 'email'],
			'password' => ['required'],
		]);

		if (Auth::attempt($credentials)) {
			$request->session()->regenerate();
			return redirect()->intended(route('home'))->with('success', 'Login successful!');
		}

		return back()->withErrors([
			'email' => 'The provided credentials do not match our records.',
		]);
	}
	public function signup(Request $request)
	{
		$validator = Validator::make($request->all(), [
			"name" => "required|max:100",
			"email" => "required|email|max:100|unique:users",
			"password" => "required|min:8",
		]);

		if ($validator->fails()) {
			return Inertia::render('Auth/signup', [
				'errors' => $validator->errors()
			])->withViewData(['errors' => $validator->errors()]);
		}

		$user = User::create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => bcrypt($request->password),
		]);

		Auth::login($user);

		$request->session()->regenerate();

		return redirect()->intended(route('home'))->with('success', 'Account created and logged in successfully!');
	}
	public function logout(Request $request): RedirectResponse
	{
		Auth::logout();
		$request->session()->invalidate();
		$request->session()->regenerateToken();

		return redirect()->intended(route('home'))->with('success', 'You have been logged out successfully.');
	}
}
