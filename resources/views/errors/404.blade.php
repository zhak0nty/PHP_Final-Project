@extends('layouts.app')

@section('title', 'Page not found — MedBooking')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
        <div class="max-w-lg mx-auto text-center">
            <p class="text-sm font-semibold text-[#6B21A8] uppercase tracking-wide">404</p>
            <h1 class="mt-2 text-3xl font-bold text-gray-900 tracking-tight">Page not found</h1>
            <p class="mt-3 text-gray-600">
                The page you are looking for does not exist or has been moved.
            </p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('home') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-800 hover:bg-gray-50">
                    Go back
                </a>
                <a href="{{ route('home') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-[#6B21A8] px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-[#5B1B8A]">
                    Home
                </a>
            </div>
        </div>
    </div>
@endsection
