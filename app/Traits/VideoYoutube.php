<?php

namespace App\Traits;

use Dawson\Youtube\Facades\Youtube;
use Illuminate\Http\File;

trait VideoYoutube
{
    public function uploadVideoToYoutube($data)
    {
//            return Youtube::upload($data['file'], [
//                'title' => $data['title'],
//                'description' => $data['description']
//            ]);
    }
}
