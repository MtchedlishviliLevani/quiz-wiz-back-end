<?php

namespace App\Http\Controllers;

use App\Models\Difficulty;
use Illuminate\Database\Eloquent\Collection;

class DifficultyController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(): Collection
	{
		return Difficulty::orderBy('id', 'asc')->get(['id', 'level', 'color']);
	}
}
