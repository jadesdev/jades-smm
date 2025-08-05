@extends('front.layouts.main')

@section('title', $page->title)

@section('content')
    <div class="bg-white dark:bg-gray-800 py-16 lg:py-24">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">

            <article class="prose dark:prose-invert lg:prose-xl max-w-4xl mx-auto">

                <h1>{{ $page->title }}</h1>

                {!! $page->description !!}

            </article>

        </div>
    </div>
@endsection
