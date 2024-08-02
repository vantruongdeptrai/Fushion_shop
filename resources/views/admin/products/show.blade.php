@extends('admin.dashboard')
@section('title')
Detail Product
@endsection
@section('content')
<div class="container">
    <div class="container">
        <table class="table" style="border-color: black;">
            <thead>
                <tr>
                    <th>Name product</th>
                    <th>Description</th>
                    <th colspan="2">Variants</th> <!-- Thay đổi từ một cột "Variants" thành hai cột con -->
                    <th>Tags</th>
                    <th>Catelogue</th>
                    <th>Galleries</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th>Size</th> <!-- Cột con đầu tiên -->
                    <th>Color</th> <!-- Cột con thứ hai -->
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($product_variants as $variant) <!-- Lặp qua từng biến thể -->
                            <tr>
                                @if ($loop->first) <!-- Chỉ hiển thị thông tin sản phẩm ở dòng đầu tiên -->
                                    <td rowspan="{{ count($product_variants) }}">{{ $product->name }}</td>
                                    <td rowspan="{{ count($product_variants) }}">{{ $product->description }}</td>
                                @endif
                                <td>{{ $variant->size->name ?? 'No Size' }}</td>
                                <td>{{ $variant->color->name ?? 'No Color' }}</td>
                                @if ($loop->first) <!-- Chỉ hiển thị thông tin khác ở dòng đầu tiên -->
                                    <td rowspan="{{ count($product_variants) }}">
                                        <ul>
                                            @foreach($product->tag as $tag)
                                                <li>{{ $tag->name }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td rowspan="{{ count($product_variants) }}">
                                        <p>{{ $product->catelogue->name }}</p>
                                    </td>
                                    <td rowspan="{{ count($product_variants) }}">
                                        <ul>
                                            @foreach($product->galleries as $gallery)
                                                
                                                @php
                                                    $url = $gallery->image;

                                                    if (!\Str::contains($url, 'http')) {
                                                        $url = \Illuminate\Support\Facades\Storage::url($url);
                                                    }
                                                @endphp

                                                <img src="{{ $url }}" alt="" width="100px"><br>
                                            @endforeach
                                            
                                        </ul>
                                        
                                    </td>
                                @endif
                            </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
@endsection