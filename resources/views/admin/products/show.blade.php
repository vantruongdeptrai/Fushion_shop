@extends('admin.dashboard')
@section('title')
   Detail Product
@endsection
@section('content')
<div class="container">
    <div class="container">
    <h1>{{ $product->name }}</h1>
    <p>{{ $product->description }}</p>
    
    <h2>Variants</h2>
    <ul>
        @foreach($product->variants as $variant)
            <li>
                Size: {{ $variant->product_size_id }},
                Color: {{ $variant->product_color_id }},
                Quantity: {{ $variant->quatity }},
                @if($variant->image)
                    <img src="{{ Storage::url($variant->image) }}" alt="Variant Image">
                @endif
            </li>
        @endforeach
    </ul>

    <h2>Tags</h2>
    <ul>
    @if($product->tags != null)
    <h2>Tags</h2>
    <ul>
        @foreach($product->tags as $tag)
            <li>{{ $tag->name }}</li>
        @endforeach
    </ul>
    @else
        <p>No tags available for this product.</p>
    @endif
    </ul>

    <h2>Galleries</h2>
    <ul>
        @foreach($product->galleries as $gallery)
            <li><img src="{{ $gallery->url }}" alt="{{ $gallery->alt_text }}"></li>
        @endforeach
    </ul>

    <h2>Catelogue</h2>
    <p>{{ $product->catelogue->name }}</p>
</div>
</div>
@endsection
