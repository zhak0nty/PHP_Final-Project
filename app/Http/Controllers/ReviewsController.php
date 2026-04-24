<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePublicReviewRequest;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReviewsController extends Controller
{
    public function index(): View
    {
        $reviews = Review::query()
            ->where('kind', 'review')
            ->latest()
            ->take(20)
            ->get();

        return view('reviews.index', compact('reviews'));
    }

    public function store(StorePublicReviewRequest $request): RedirectResponse
    {
        $kind = $request->input('kind', 'review');

        if ($kind === 'complaint') {
            $text = $request->input('complaint_text');
            $name = $request->input('complaint_name');
            $phone = $request->input('complaint_phone');
        } else {
            $text = $request->input('text');
            $name = $request->input('name');
            $phone = $request->input('phone');
        }

        Review::create([
            'kind' => $kind,
            'name' => $name,
            'phone' => $phone,
            'text' => $text,
        ]);

        $message = $kind === 'complaint'
            ? 'Thank you! We will take your feedback into account.'
            : 'Thank you for your review!';

        return redirect()
            ->route('reviews.index')
            ->with('status', $message);
    }
}
