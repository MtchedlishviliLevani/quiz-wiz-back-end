<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(): Collection
	{
		return Category::orderBy('id', 'asc')->get(['id', 'name']);
	}
}
