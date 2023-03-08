<?php

namespace App\Actions;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UpdateStatusAction
{
    public function handle($user, $status): bool
    {
        if (empty($user)) {
            return false;
        }

        $user = $user->update([
            'status' => $status,
        ]);

        return $user;
    }
}
