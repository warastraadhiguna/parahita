<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Benefit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class BenefitController extends Controller
{
    public function index(Request $request)
    {
        $benefits = Benefit::select('benefits.*')
            ->latest('benefits.id');

        if (!empty($request->get('keyword'))) {
            $benefits = $benefits
                ->where('benefits.name', 'like', '%' . $request
                    ->get('keyword') . '%');
        }

        $benefits = $benefits->paginate(10);

        $page = Request()->input('page');
        $page = $page ? ($page - 1) : 0;

        return view('admin.benefit.list', compact('benefits', 'page'));
    }

    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        return view('admin.benefit.create', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'icon' => 'required|unique:benefits',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'icon.required' => 'Icon wajib diisi.',
        ]);

        if ($validator->passes()) {

            $benefit = new Benefit();
            $benefit->name = $request->name;
            $benefit->icon = $request->icon;
            $benefit->save();

            Session::flash('success', 'Benefit Berhasil di Tambah');

            return response([
                'status' => true,
                'message' => 'Benefit berhasil di Tambah.'
            ]);
        } else {
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request)
    {
        $benefit = Benefit::find($id);
        if (empty($benefit)) {
            Session::flash('error', 'Data tidak ditemukan');
            return redirect()->route('sub-categories.index');
        }

        $data['benefit'] = $benefit;
        return view('admin.benefit.edit', $data);
    }

    public function update($id, Request $request)
    {

        $benefit = Benefit::find($id);

        if (empty($benefit)) {
            Session::flash('error', 'Data tidak ditemukan');
            return response([
                'status' => false,
                'notFound' => true
            ]);
            //return redirect()->route('sub-categories.index');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'icon' => 'required',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'icon.required' => 'Icon wajib diisi.',
        ]);

        if ($validator->passes()) {

            $benefit->name = $request->name;
            $benefit->icon = $request->icon;
            $benefit->save();

            Session::flash('success', 'Benefit Berhasil di Update');

            return response([
                'status' => true,
                'message' => 'Benefit berhasil di Update.'
            ]);
        } else {
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id, Request $request)
    {
        $benefit = Benefit::find($id);

        if (empty($benefit)) {
            Session::flash('error', 'Data tidak ditemukan');
            return response([
                'status' => false,
                'notFound' => true
            ]);
        }

        $benefit->delete();

        Session::flash('success', 'Benefit Berhasil di Hapus');

        return response([
            'status' => true,
            'message' => 'Benefit berhasil di Hapus.'
        ]);
    }
}
