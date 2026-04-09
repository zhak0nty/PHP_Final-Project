<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function store(Request $request): RedirectResponse
    {
        $kind = $request->input('kind', 'review');

        $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'text' => ['required_without:complaint_text', 'nullable', 'string', 'max:2000'],
            'complaint_name' => ['required_if:kind,complaint', 'string', 'max:255'],
            'complaint_phone' => ['nullable', 'string', 'max:255'],
            'complaint_text' => ['required_without:text', 'nullable', 'string', 'max:2000'],
        ]);

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
