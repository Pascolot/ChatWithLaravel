<?php

namespace App\Actions;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageUploadAction
{
    public function handle(UploadedFile $image): string
    {
        $imageName = time() . '.' . $image->extension();
        $imageUrl = $image->storeAs(
            "fichier_telecharger",
            $imageName,
            'public'
        );

        return Storage::url($imageUrl);
    }
}
