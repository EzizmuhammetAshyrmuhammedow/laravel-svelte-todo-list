<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;

class HomeController extends Controller
{
	const PER_PAGE = 10;

	public function index(): Response
	{
		$posts = Post::orderBy('id', 'desc')->simplePaginate(self::PER_PAGE);
		$layout = Auth::check() ? 'Layouts/AuthenticatedLayout' : 'Layouts/GuestLayout';

		return Inertia::render('Posts/Index', ['posts' => $posts, 'success' => session('success'),]);
	}
}
