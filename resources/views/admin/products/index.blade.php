@extends('admin.layout.master')

@section('title')
List Product
@endsection
@section('content1')
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Datatables</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                                <li class="breadcrumb-item active">Datatables</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->


            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h5 class="card-title mb-0 flex-grow-1">Add Product</h5>
                            <div>
                                <a href="{{route('admin.products.create')}}" class="btn btn-success">Create</a>
                            </div>
                        </div>
                        <div class="card-header">
                            <h5 class="card-title mb-0">Basic Datatables</h5>
                        </div>
                        <div class="card-body">
                            <table id="example"
                                class="table table-bordered dt-responsive nowrap table-striped align-middle"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width: 10px;">
                                            <div class="form-check">
                                                <input class="form-check-input fs-15" type="checkbox" id="checkAll"
                                                    value="option">
                                            </div>
                                        </th>
                                        <th>ID</th>
                                        <th>Img Thumbnail</th>
                                        <th>Name</th>
                                        <th>SKU</th>
                                        <th>Catelogues</th>
                                        <th>Price Regular</th>
                                        <th>Price Sale</th>
                                        <th>Views</th>
                                        <th>Is Active</th>
                                        <th>Is Hot Deal</th>
                                        <th>Is Good Deal</th>
                                        <th>Is New</th>
                                        <th>Is Show Home</th>
                                        <th>Tags</th>
                                        <th>Created at</th>
                                        <th>Updated at</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $item)
                                        <tr>

                                            <td scope="row">
                                                <div class="form-check">
                                                    <input class="form-check-input fs-15" type="checkbox" name="checkAll"
                                                        value="option1">
                                                </div>
                                            </td>
                                            <td>{{ $item->id }}</td>
                                            <td>
                                                @php
                                                    $url = $item->img_thumbnail;

                                                    if (!\Str::contains($url, 'http')) {
                                                        $url = \Illuminate\Support\Facades\Storage::url($url);
                                                    }
                                                @endphp

                                                <img src="{{ $url }}" alt="" width="100px">
                                            </td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->sku }}</td>
                                            <td>
                                                {{$item->catelogue->name}}
                                            </td>
                                            <td>{{ $item->price_regular }}</td>
                                            <td>{{ $item->price_sale }}</td>
                                            <td>{{ $item->views }}</td>
                                            <td>{!! $item->is_active ? '<span class="badge bg-primary">YES</span>'
            : '<span class="badge bg-danger">NO</span>' !!}
                                            </td>
                                            <td>{!! $item->is_hot_deal ? '<span class="badge bg-primary">YES</span>'
            : '<span class="badge bg-danger">NO</span>' !!}
                                            </td>
                                            <td>{!! $item->is_good_deal ? '<span class="badge bg-primary">YES</span>'
            : '<span class="badge bg-danger">NO</span>' !!}
                                            </td>
                                            <td>{!! $item->is_new ? '<span class="badge bg-primary">YES</span>'
            : '<span class="badge bg-danger">NO</span>' !!}
                                            </td>
                                            <td>{!! $item->is_show_home ? '<span class="badge bg-primary">YES</span>'
            : '<span class="badge bg-danger">NO</span>' !!}
                                            </td>
                                            <td>
                                                @foreach($item->tag as $tag)
                                                    <span class="badge bg-info">{{ $tag->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>{{ $item->updated_at }}</td>
                                            <td>
                                                <form action="{{ route('admin.products.destroy', $item->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this product?');">Delete</button>
                                                </form>

                                                <a href="{{ route('admin.products.show', $item->id) }}"
                                                    class="btn btn-info">View</a>
                                                <a href="{{ route('admin.products.edit', $item->id) }}"
                                                    class="btn btn-warning">Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <script>document.write(new Date().getFullYear())</script> © Velzon.
                </div>
                <div class="col-sm-6">
                    <div class="text-sm-end d-none d-sm-block">
                        Design & Develop by Themesbrand
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
@endsection
@section('script-libs')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script src="{{asset('theme/admin/assets/js/pages/datatables.init.js')}}"></script>
@endsection