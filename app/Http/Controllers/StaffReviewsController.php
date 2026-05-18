<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\View\View;

class StaffReviewsController extends Controller
{
    public function index(): View
    {
        $reviews = Review::query()
            ->where('kind', 'review')
            ->latest()
            ->paginate(20, ['*'], 'reviews_page');

        $complaints = Review::query()
            ->where('kind', 'complaint')
            ->latest()
            ->paginate(20, ['*'], 'complaints_page');

        return view('reviews.manage', compact('reviews', 'complaints'));
    }
}
