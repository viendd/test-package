<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadService
{
    public function uploadFile(Request $request, $folderName, $inputName)
    {
        if (!$request->hasFile($inputName)) {
            return '';
        }
        $file = $request->file($inputName);
        $file_name = $file->getClientOriginalName();
        $extension = explode('.', $file_name);
        $extension = $extension[count($extension) - 1];
        if ($request->name != '') {
            $private_name = Str::slug($request->name) . '-' .time() . '.' . $extension;
        } else {
            $private_name = md5($file_name . time()) . '.' . $extension;
        }
        $path = 'public/upload/'. $folderName .'/'. date("Y/m");
        \File::makeDirectory('app/'. $path, $mode = 0777, true, true);
        Storage::putFileAs($path, $file, $private_name);
        $path_full = str_replace('public/', '', $path);
        return 'storage/'.$path_full .'/'. $private_name;
    }

    public function multipleUploadFile($files, $folderName)
    {
        $fileArr = [];
        foreach ($files as $file) {
            $file_name = $file->getClientOriginalName();
            $extension = explode('.', $file_name);
            $extension = $extension[count($extension) - 1];
            $private_name = md5($file_name . time()) . '.' . $extension;
            $path = 'public/upload/'. $folderName .'/'. date("Y/m");
            \File::makeDirectory('app/'. $path, $mode = 0777, true, true);
            Storage::putFileAs($path, $file, $private_name);
            $path_full = str_replace('public/', '', $path);
            array_push($fileArr, 'storage/'.$path_full .'/'. $private_name);
        }
        if (empty($fileArr)) {
            return [];
        }

        return $fileArr;
    }

    function removeFile($fileUrl)
    {
        $path = str_replace("storage","public",$fileUrl);
        if (Storage::exists($path)) {
            Storage::delete($path);

            return true;
        }

        return false;
    }
}
