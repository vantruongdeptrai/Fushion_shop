@extends('admin.dashboard')
@section('title')
   Edit Product
@endsection

@section('content')
@if(session('success'))
        <div class="alert alert-success">
            {{session('success')}}
        </div>
    @endif
@if(session('error'))
    <div class="alert alert-danger">
        {{session('error')}}
    </div>
@endif
    <form action="{{route('admin.products.update',$product->id)}}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-6">
                <div>
                    <label for="">Name </label>
                    <input type="text" name="name" placeholder="Enter name" class="form-control" value="{{$product->name}}">
                </div>
                
                <div class="mt-3">
                    <label for="">SKU</label>
                    <input type="text" name="sku" id="" class="form-control" value="{{$product->name}}">
                </div>
                
                <div class="mt-3">
                    <label for="">Image</label>
                    <input type="file" name="img_thumbnail" id="" class="form-control" value="{{$product->img_thumbnail}}">
                </div>
                
                <div class="mt-3">
                    <label for="">Price regular</label>
                    <input type="text" name="price_regular" id="" class="form-control" value="{{$product->price_regular}}">
                </div>
                
                <div class="mt-3">
                    <label for="">Price sale</label>
                    <input type="text" name="price_sale" id="" class="form-control" value="{{$product->price_sale}}">
                </div>
                
            </div>
            <div class="col-6">
                <div class="row mt-4">
                    @php
                    $is = [
                        'is_active' => 'primary',
                        'is_hot_deal' => 'danger',
                        'is_good_deal' => 'warning',
                        'is_new' => 'success',
                        'is_show_home' => 'info',
                    ];
                    @endphp

                    @foreach($is as $key => $color)
                        <div class="col-3">
                            <div class="form-check form-switch form-switch-{{ $color }}">
                                <input class="form-check-input" type="checkbox" role="switch"
                                        name="{{ $key }}" value="1" id="{{ $key }}" checked>
                                <label class="form-check-label"
                                        for="{{ $key }}">{{ \Str::convertCase($key, MB_CASE_TITLE) }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-3">
                    <label for="catelogue_id" class="form-label">Catelogues</label>
                    <select type="text" class="form-select" name="catelogue_id" id="catelogue_id" >
                        @foreach($catalogues as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mt-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" name="description" id="description" rows="2"></textarea>
                </div>
                <div class="mt-3">
                    <label for="material" class="form-label">Material</label>
                    <textarea class="form-control" name="material" id="material" rows="2"></textarea>
                </div>
                <div class="mt-3">
                    <label for="user_manual" class="form-label">User Manual</label>
                    <textarea class="form-control" name="user_manual" id="user_manual" rows="2"></textarea>
                </div>
                <div class="mt-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea class="form-control" name="content" id="content"></textarea>
                </div>
            </div>
        </div>
        <!-- Biến thể -->
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Variants</h4>
                    </div><!-- end card header -->
                    <div class="card-body" style="height: 450px; overflow: scroll">
                        <div class="live-preview">
                            <div class="row gy-4">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr class="text-center">
                                            <th>Size</th>
                                            <th>Color</th>
                                            <th>Quantity</th>
                                            <th>Image</th>
                                        </tr>

                                        @foreach($sizes as $sizeID => $sizeName)
                                            @php($flagRowspan = true)

                                            @foreach($colors as $colorID => $colorName)
                                                <tr class="text-center">

                                                    @if($flagRowspan)
                                                        <td style="vertical-align: middle;"
                                                            rowspan="{{ count($colors) }}"><b>{{ $sizeName }}</b></td>
                                                    @endif
                                                    @php($flagRowspan = false)

                                                    <td>
                                                        <div
                                                            style="width: 50px; height: 50px; background: {{ $colorName }};"></div>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                               value="0"
                                                               name="product_variants[{{ $sizeID . '-' . $colorID }}][quantity]">
                                                    </td>
                                                    <td>
                                                        <input type="file" class="form-control"
                                                               name="product_variants[{{ $sizeID . '-' . $colorID }}][image]">
                                                    </td>
                                                </tr>

                                            @endforeach
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        <!-- Biến thể -->
    </div>
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Gallery</h4>
                    <button type="button" class="btn btn-primary" onclick="addImageGallery()">Add image</button>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="live-preview">
                        <div class="row gy-4" id="gallery_list">
                            <div class="col-md-4" id="gallery_default_item">
                            <label for="gallery_default" class="form-label">Image</label>
                            <div class="d-flex">
                                <input type="file" class="form-control" name="galleries[]"
                                        id="gallery_default">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">More infomation</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="live-preview">
                        <div class="row gy-4">
                            <div class="col-md-12">
                                <div>
                                    <label for="tags" class="form-label">Tags</label>
                                    <select class="form-select" name="tags[]" id="tags" multiple>
                                        @foreach($tags as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!--end col-->
    </div>
        <input type="submit" value="Submit" class="btn btn-success mt-5">
    </form>
@endsection
@section('scripts')
    <script>
        CKEDITOR.replace('content');

        function addImageGallery() {
            let id = 'gen' + '_' + Math.random().toString(36).substring(2, 15).toLowerCase();
            let html = `
                <div class="col-md-4" id="${id}_item">
                    <label for="${id}" class="form-label">Image</label>
                    <div class="d-flex">
                        <input type="file" class="form-control" name="galleries[]" id="${id}">
                        <button type="button" class="btn btn-danger" onclick="removeImageGallery('${id}_item')">
                            <span class="bx bx-trash"></span>
                        </button>
                    </div>
                </div>
            `;

            $('#gallery_list').append(html);
        }

        function removeImageGallery(id) {
            if (confirm('Chắc chắn xóa không?')) {
                $('#' + id).remove();
            }
        }
    </script>
@endsection