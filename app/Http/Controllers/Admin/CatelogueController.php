<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Catelogue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CatelogueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    const PATH_VIEW = 'admin.catalogues.';


    public function index()
    {
        //
        $data = Catelogue::query()->latest('id')->get();
        // foreach($data as $item){
        //     dd(asset($item['cover']));
        // }

        return view(self::PATH_VIEW . __FUNCTION__, compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view(self::PATH_VIEW . __FUNCTION__);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $request->validate([
            'name' => 'required|string|max:255',
            'cover' => 'required|image|mimes:png,jpg,svg,gif,jpeg|max:2048'
        ]);

        $data = $request->all();
        if ($request->hasFile('cover')) {
           // Lưu tệp vào thư mục public/catalogues và lấy đường dẫn
            $path = $request->file('cover')->store('catalogues', 'public');
            // Lưu đường dẫn vào mảng data
            $data['cover'] = $path;
            
        }

        $res = Catelogue::query()->create($data);
        if($res){
            return back()->with('success','Tạo danh mục thành công !');
        }else{
            return back()->with('error','Tạo danh mục thất bại !');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $model = Catelogue::query()->findOrFail($id);
        return view('admin.catalogues.detail', compact('model'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //

        $model = Catelogue::query()->findOrFail($id);

        return view('admin.catalogues.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $model = Catelogue::query()->findOrFail($id); // đối tượng builder
        $request->validate([
            'name' => 'required|string|max:255',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_active' => 'sometimes|boolean'
        ]);
        $model->name = $request->input('name');
        $model->is_active = $request->input('is_active', 0);
        if ($request->hasFile('cover')) {
            // Delete old cover if exists
            if ($model->cover) {
                Storage::delete($model->cover);
            }
            // Store new cover and save its path
            $model->cover = $request->file('cover')->store('catalogues');
        }
        // Save the model
        $model->save();

        // Redirect with success message
        return redirect()->route('admin.catalogues.index')->with('success', 'Catalogue updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $model = Catelogue::findOrFail($id);

        // Xóa ảnh nếu tồn tại
        if ($model->cover) {
            Storage::delete($model->cover);
        }

        // Xóa bản ghi khỏi cơ sở dữ liệu
        $model->delete();

        // Chuyển hướng và thông báo thành công
        return redirect()->route('admin.catalogues.index')->with('success', 'Catalogue deleted successfully');
    }
}
