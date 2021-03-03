<?php

namespace App\Traits;

use Dawson\Youtube\Facades\Youtube;

trait VideoYoutube
{
    public function uploadVideoToYoutube($data)
    {
            return Youtube::upload($data['file'], [
                'title' => $data['title'],
                'description' => $data['description']
            ]);
    }
}
