<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageController extends Controller
{
  public function resizeImage($imageFile, $fileName, $maxHeight = 800){
    $img = Image::make($imageFile);

    return $img
      ->resize(null, $maxHeight, function ($constraint){
        $constraint->aspectRatio();
        $constraint->upsize();
      })
      ->encode('webp', 100)
      ->save(Storage::disk('public')->path($fileName));
  }
}
