@extends('admin.layout.master')

@section('content1')
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">
            <h1>Quản lý Banners</h1>
            <a href="{{ route('banners.create') }}" class="btn btn-primary">Tạo Banner mới</a>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tiêu đề</th>
                        <th>Hình ảnh</th>
                        <th>Liên kết</th>
                        <th>Hoạt động</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($banners as $banner)
                        <tr>
                            <td>{{ $banner->title }}</td>
                            <td><img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" width="100">
                            </td>
                            <td><a href="{{ $banner->link }}">{{ $banner->link }}</a></td>
                            <td>{{ $banner->is_active ? 'Có' : 'Không' }}</td>
                            <td>
                                <a href="{{ route('banners.edit', $banner) }}" class="btn btn-warning">Sửa</a>
                                <form action="{{ route('banners.destroy', $banner) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection