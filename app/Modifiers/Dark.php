<?php

namespace App\Modifiers;

use Illuminate\Support\Facades\Storage;
use Statamic\Modifiers\Modifier;

class Dark extends Modifier
{
    public function index($value, $params, $context)
    {
        [$path, $extension] = explode('.', $value);

        $darkPath = $path . '-dark.' . $extension;

        if (Storage::disk('assets')->exists(str_replace('/assets/', '', $darkPath))) {
            return $darkPath;
        }

        return $value;
    }
}
