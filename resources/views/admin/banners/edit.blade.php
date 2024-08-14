@extends('admin.dashboard')

@section('content')
<div class="container">
    <h1>Sửa Banner</h1>
    <form action="{{ route('banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="title">Tiêu đề</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ $banner->title }}" required>
        </div>
        <div class="form-group">
            <label for="image">Hình ảnh</label>
            <input type="file" class="form-control" id="image" name="image">
            <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" width="100">
        </div>
        <div class="form-group">
            <label for="link">Liên kết</label>
            <input type="url" class="form-control" id="link" name="link" value="{{ $banner->link }}">
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
@endsection
