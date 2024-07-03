<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Slider;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image as Image;
use Illuminate\Support\Facades\Session;

class SliderController extends Controller
{
    public function index(Request $request)
    {
        $sliders = Slider::latest();

        $sliders = $sliders->paginate(10);

        return view('admin.slider.list', compact('sliders'));
    }

    public function create()
    {
        return view('admin.slider.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);

        if($validator->passes()) {

            $slider = new Slider();
            $slider->title = $request->title;
            $slider->description = $request->description;
            $slider->status = $request->status;
            $slider->showHome = $request->showHome;
            $slider->save();

            // Simpan Gambar Disini
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $slider->id.'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/front-assets/images/'.$newImageName;
                File::copy($sPath, $dPath);

                $slider->image = $newImageName;
                $slider->save();
            }

            Session::flash('success', 'Slider berhasil ditambahkan');

            return response()->json([
                'status' => true,
                'message' => 'Slider berhasil ditambahkan'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($sliderId, Request $request)
    {
        $slider = Slider::find($sliderId);
        if(empty($slider)) {
            Session::flash('error', 'Data tidak ditemukan');
            return redirect()->route('sliders.index');
        }


        return view('admin.slider.edit', compact('slider'));
    }

    public function update($sliderId, Request $request)
    {

        $slider = Slider::find($sliderId);

        if(empty($slider)) {
            Session::flash('error', 'Slider tidak ditemukan');

            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Slider not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);

        if($validator->passes()) {

            $slider->title = $request->title;
            $slider->description = $request->description;
            $slider->status = $request->status;
            $slider->showHome = $request->showHome;
            $slider->save();

            $oldImage = $slider->image;

            // Simpan Gambar Disini
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $slider->id.'-'.time().'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/front-assets/images/'.$newImageName;
                File::copy($sPath, $dPath);

                $slider->image = $newImageName;
                $slider->save();

                File::delete(public_path().'/front-assets/images/'.$oldImage);

            }

            Session::flash('success', 'Slider berhasil di Update');

            return response()->json([
                'status' => true,
                'message' => 'Slider berhasil di Update'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($sliderId, Request $request)
    {
        $slider = Slider::find($sliderId);

        if (empty($slider)) {
            Session::flash('error', 'Slider tidak ditemukan');
            return response()->json([
                'status' => true,
                'message' => 'Slider tidak ditemukan'
            ]);
        }

        File::delete(public_path().'/front-assets/images/'.$slider->image);

        $slider->delete();

        Session::flash('success', 'Slider berhasil di Hapus');

        return response()->json([
            'status' => true,
            'message' => 'Slider berhasil di Hapus'
        ]);
    }
}
