<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function render($name)
    {
        return Storage::get('public/infografis/' . $name);
    }
}
