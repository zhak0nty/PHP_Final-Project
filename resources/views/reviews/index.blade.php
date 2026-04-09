@extends('layouts.app')

@section('title', 'Reviews — MedBooking')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10">
        <div class="mb-8">
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Reviews</h1>
            <p class="mt-2 text-gray-600 max-w-2xl">
                Share your visit experience or tell us what we can improve. This is anonymous — include only what you are comfortable sharing.
            </p>
        </div>

        <div class="grid gap-8 lg:grid-cols-[minmax(0,2fr),minmax(0,1.5fr)] items-start">
            <div class="space-y-6">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Leave a review</h2>
                    <form method="POST" action="{{ route('reviews.store') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="kind" value="review">
                        <div class="mt-3 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Your name (optional)</label>
                                <input id="name" name="name" type="text"
                                       class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-[#6B21A8] focus:ring-2 focus:ring-[#6B21A8]/20 focus:outline-none transition-colors"
                                       placeholder="May be left blank">
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone (optional)</label>
                                <input id="phone" name="phone" type="text"
                                       class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-[#6B21A8] focus:ring-2 focus:ring-[#6B21A8]/20 focus:outline-none transition-colors"
                                       placeholder="+1 555 000 0000">
                            </div>
                        </div>
                        <div>
                            <label for="text" class="block text-sm font-medium text-gray-700 mb-1.5">Your review</label>
                            <textarea id="text" name="text" rows="4"
                                      class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-[#6B21A8] focus:ring-2 focus:ring-[#6B21A8]/20 focus:outline-none transition-colors resize-none"
                                      placeholder="For example: what went well, what could be better"></textarea>
                        </div>
                        <button type="submit"
                                class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#6B21A8] px-4 py-3 text-sm font-medium text-white shadow-sm hover:bg-[#5B1B8A] transition-colors">
                            Submit review
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </form>
                </div>

                <div class="space-y-4">
                    <h2 class="text-lg font-semibold text-gray-900">Latest reviews</h2>
                    <div class="grid gap-4 md:grid-cols-2">
                        @forelse ($reviews as $review)
                            <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                                <div class="flex items-center justify-between gap-3 mb-2">
                                    <div class="font-semibold text-gray-900">{{ $review->name ?: 'Anonymous' }}</div>
                                    <div class="text-xs text-gray-400">{{ $review->created_at->format('d.m.Y H:i') }}</div>
                                </div>
                                <p class="text-sm text-gray-700 leading-relaxed">
                                    {{ $review->text }}
                                </p>
                            </article>
                        @empty
                            <p class="text-sm text-gray-500 col-span-2">No reviews yet. Be the first to share your experience.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        Your feedback matters
                    </div>
                    <h2 class="mt-4 text-lg font-semibold text-gray-900">Complaint or suggestion?</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Tell us what we can improve: booking, care, or the interface. It helps us serve you better.
                    </p>
                    <form method="POST" action="{{ route('reviews.store') }}" class="mt-4 space-y-3">
                        @csrf
                        <input type="hidden" name="kind" value="complaint">
                        <div>
                            <label for="complaint_name" class="block text-sm font-medium text-gray-700 mb-1.5">Name <span class="text-red-500">*</span></label>
                            <input id="complaint_name" name="complaint_name" type="text"
                                   class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-[#6B21A8] focus:ring-2 focus:ring-[#6B21A8]/20 focus:outline-none transition-colors"
                                   placeholder="Your name">
                        </div>
                        <div>
                            <label for="complaint_phone" class="sr-only">Phone</label>
                            <input id="complaint_phone" name="complaint_phone" type="text"
                                   class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-[#6B21A8] focus:ring-2 focus:ring-[#6B21A8]/20 focus:outline-none transition-colors"
                                   placeholder="Your phone (optional)">
                        </div>
                        <div>
                            <label for="complaint_text" class="sr-only">Comment</label>
                            <textarea id="complaint_text" name="complaint_text" rows="3"
                                      class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-[#6B21A8] focus:ring-2 focus:ring-[#6B21A8]/20 focus:outline-none transition-colors resize-none"
                                      placeholder="Describe the issue or suggestion"></textarea>
                        </div>
                        <button type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-800 shadow-sm hover:bg-gray-50 transition-colors">
                            Send feedback
                        </button>
                        <p class="text-[11px] text-gray-400">
                            Messages go to MedBooking. Only clinic administrators can see them — we do not share them with third parties.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
