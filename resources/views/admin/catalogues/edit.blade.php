@extends('admin.dashboard')
@section('title')
   Edit Categolue
@endsection

@section('content')
    <form action="{{route('admin.catalogues.update',$model->id)}}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
    
    <label for="name">Name:</label>
    <input type="text" name="name" placeholder="Enter name" class="form-control" value="{{ old('name', $model->name) }}">
    
    <div class="mt-4">
        <label for="cover">Current File:</label>
        @if ($model->cover)
            <img src="{{ asset('storage/' . $model->cover) }}" alt="Cover Image" style="width: 100px; height: auto;">
        @endif
    </div>
    
    <div>
        <label for="cover">New File:</label>
        <input type="file" name="cover" class="form-control">
    </div>
    
    <div class="mt-5">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $model->is_active) ? 'checked' : '' }}> Is active
    </div>
    
    <input type="submit" value="Submit" class="btn btn-success mt-5">
    </form>
@endsection