@extends('admin.dashboard')
@section('title')
   Create Categolue
@endsection

@section('content')
    <form action="{{route('admin.catalogues.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        <label for="">Name : </label>
        <input type="text" name="name" placeholder="Enter name" class="form-control">
        <label for="">File</label>
        <input type="file" name="cover" id="" class="form-control">
        <div class="mt-5">
            <input type="checkbox" name="is_active" value="1" checked> Is active
        </div>
        <input type="submit" value="Submit" class="btn btn-success mt-5">
    </form>
@endsection