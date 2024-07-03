<?php

namespace App\Http\Controllers\admin;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image as Image;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.company.index');
    }

    public function update(Request $request, string $id)
    {

        $company = Company::find($id);

        $rules = [
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'youtube_id' => 'nullable',
        ];


        // Pesan kesalahan kustom
        $messages = [
            'name.required' => 'Nama wajib diisi.',
            'address.required' => 'Alamat wajib diisi.',
            'phone.required' => 'Telepon wajib diisi.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->passes()) {

            $company->name = $request->name;
            $company->address = $request->address;
            $company->phone = $request->phone;
            $company->youtube_id = $request->youtube_id;

            $company->save();

            // Simpan Gambar Gallery
            Session::flash('success', 'Data berhasil di Update');

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil di Update'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function updateImage(Request $request)
    {
        $image = $request->image;
        $ext = $image->getClientOriginalExtension();
        $sourcePath = $image->getPathName();

        $company = Company::find($request->company_id);

        if ($company->image) {
            File::delete(public_path('uploads/company/large/' . $company->image));
            File::delete(public_path('uploads/company/small/' . $company->image));
        }

        $imageName = $request->company_id.'-'.time().'.'.$ext;
        $company->image = $imageName;
        $company->save();

        // Gambar Besar
        $destPath = public_path().'/uploads/company/large/'.$imageName;
        $image = Image::make($sourcePath);
        $image->resize(1400, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $image->save($destPath);

        // Gambar Kecil
        $destPath = public_path().'/uploads/company/small/'.$imageName;
        $image = Image::make($sourcePath);
        $image->fit(300, 300);
        $image->save($destPath);

        return response()->json([
            'status' => true,
            'image_id' => $company->id,
            'ImagePath' => asset('uploads/company/small/'.$company->image),
            'message' => 'Gambar Berhasil Tersimpan'
        ]);
    }
}
