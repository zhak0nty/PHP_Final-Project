@extends('layouts.app')

@section('title', 'Patient feedback — MedBooking')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Patient feedback</h1>
                <p class="mt-2 text-gray-600">Reviews and complaints submitted by clients. Visible only to administrators and doctors.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-[#6B21A8] hover:underline">
                &larr; Back to dashboard
            </a>
        </div>

        <div class="grid gap-8 lg:grid-cols-2 items-start">
            <div class="space-y-4">
                <h2 class="text-lg font-semibold text-gray-900">Reviews ({{ $reviews->total() }})</h2>
                <div class="space-y-4">
                    @forelse ($reviews as $review)
                        <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                            <div class="flex items-center justify-between gap-3 mb-2">
                                <div class="font-semibold text-gray-900">{{ $review->name ?: 'Anonymous' }}</div>
                                <div class="text-xs text-gray-400 shrink-0">{{ $review->created_at->format('d.m.Y H:i') }}</div>
                            </div>
                            @if ($review->phone)
                                <p class="text-xs text-gray-500 mb-2">{{ $review->phone }}</p>
                            @endif
                            <p class="text-sm text-gray-700 leading-relaxed">{{ $review->text }}</p>
                        </article>
                    @empty
                        <p class="text-sm text-gray-500 rounded-2xl border border-dashed border-gray-200 p-6 text-center">No reviews yet.</p>
                    @endforelse
                </div>
                @if ($reviews->hasPages())
                    <div class="pt-2">{{ $reviews->links() }}</div>
                @endif
            </div>

            <div class="space-y-4">
                <h2 class="text-lg font-semibold text-gray-900">Complaints &amp; suggestions ({{ $complaints->total() }})</h2>
                <div class="space-y-4">
                    @forelse ($complaints as $complaint)
                        <article class="rounded-2xl border border-amber-200 bg-amber-50/50 p-5 shadow-sm">
                            <div class="flex items-center justify-between gap-3 mb-2">
                                <div class="font-semibold text-gray-900">{{ $complaint->name ?: 'Anonymous' }}</div>
                                <div class="text-xs text-gray-400 shrink-0">{{ $complaint->created_at->format('d.m.Y H:i') }}</div>
                            </div>
                            @if ($complaint->phone)
                                <p class="text-xs text-gray-500 mb-2">{{ $complaint->phone }}</p>
                            @endif
                            <p class="text-sm text-gray-700 leading-relaxed">{{ $complaint->text }}</p>
                        </article>
                    @empty
                        <p class="text-sm text-gray-500 rounded-2xl border border-dashed border-gray-200 p-6 text-center">No complaints yet.</p>
                    @endforelse
                </div>
                @if ($complaints->hasPages())
                    <div class="pt-2">{{ $complaints->links() }}</div>
                @endif
            </div>
        </div>
    </div>
@endsection
