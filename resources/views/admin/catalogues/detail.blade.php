@extends('admin.layout.master')
@section('title')
Category Detail
@endsection
@section('content')
    <table class="table">
        <thead>
            <tr>
                <th data-ordering="false">ID</th>
                <th data-ordering="false">Name</th>
                <th data-ordering="false">Cover</th>
                <th>Created Date</th>
                <th>Updated Date</th>
                <th>Is active</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$model->id}}</td>
                <td>{{$model->name}}</td>
                <td><img src="{{Storage::url($model->cover)}}" alt="" width="60" height="60"></td>
                <td>{{$model->created_at}}</td>
                <td>{{$model->updated_at}}</td>
                <td>
                    @if($model->is_active!=1)
                        <p>Inactive</p>
                    @endif
                    @if($model->is_active==1)
                        <p>Active</p>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
@endsection