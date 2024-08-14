@extends('admin.dashboard')

@section('content1')
<div class="container">
    <h1>Tạo Banner Mới</h1>
    <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="title">Tiêu đề</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="image">Hình ảnh</label>
            <input type="file" class="form-control" id="image" name="image" required>
        </div>
        <div class="form-group">
            <label for="link">Liên kết</label>
            <input type="url" class="form-control" id="link" name="link">
        </div>
        <button type="submit" class="btn btn-primary">Lưu</button>
    </form>
</div>
@endsection
