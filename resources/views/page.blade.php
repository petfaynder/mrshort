@extends('layouts.app') {{-- veya uygun bir layout --}}

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">{{ $page->title }}</h1>
    <div>
        {!! $page->content !!} {{-- HTML içeriğini render et --}}
    </div>
</div>
@endsection