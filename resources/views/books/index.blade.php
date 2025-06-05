@extends('layouts.app')

@section('content')
    <h1 class="mb-10 text-3xl font-bold text-gray-800">Books</h1>

    <form method="GET" action="{{ route('books.index') }}" class="mb-6 flex items-center gap-4">
        <input
            type="text"
            name="title"
            placeholder="Search By Title"
            value="{{ request('title') }}"
            class="input border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
        <input type="hidden" name="filter" value="{{request('filter')}}">
        <button
            type="submit"
            class="btn bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
        >
            Search
        </button>
        <a
            href="{{ route('books.index') }}"
            class="text-blue-600 hover:underline"
        >
            Clear
        </a>
    </form>

    <div class="filter-container mb-4 flex">
        @php
            $filters = [
                ''=>"Latest",
                'popular_last_month'=> 'Popular Last Month',
                'popular_last_6months'=> 'Popular Last 6 Months',
                'highest_rated_last_month'=> 'Highest Rated Last Month',
                'highest_rated_last_6months'=> 'Highest Rated Last 6 Months',
    ];
        @endphp

        @foreach($filters as $key=>$label)
            <a href="{{route('books.index',[...request()->query(),'filter'=>$key]) }}"
            class="{{request('filter')===$key||(request('filter')===null && $key=== '') ? 'filter-item-active':'filter-item'}}">
                {{$label}}
            </a>
        @endforeach
    </div>

    <ul>
        @forelse($books as $book)
            <li class="mb-4">
                <div class="book-item p-4 border border-gray-200 rounded hover:shadow-md transition">
                    <div class="flex flex-wrap items-center justify-between">
                        <div class="w-full flex-grow sm:w-auto">
                            <a href="{{ route('books.show', $book) }}"
                               class="book-title text-xl font-semibold text-blue-700 hover:underline">
                                {{ $book->title }}
                            </a>
                            <span class="book-author block text-gray-600">by {{ $book->author }}</span>
                        </div>
                        <div class="text-right mt-2 sm:mt-0">
                            <div class="book-rating text-yellow-500 font-bold text-lg">
                                ★ {{ number_format($book->reviews_avg_rating, 1) }}
                            </div>
                            <div class="book-review-count text-gray-500 text-sm">
                                out of {{ $book->reviews_count }} {{ Str::plural('review', $book->reviews_count) }}
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        @empty
            <li class="mb-4">
                <div class="empty-book-item p-6 bg-yellow-50 border border-yellow-200 rounded text-center">
                    <p class="empty-text text-yellow-800 font-medium">No books found</p>
                    <a href="{{ route('books.index') }}"
                       class="reset-link text-blue-600 hover:underline mt-2 inline-block">
                        Reset criteria
                    </a>
                </div>
            </li>
        @endforelse
    </ul>
@endsection
