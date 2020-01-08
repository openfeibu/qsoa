<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Finance\Controller;
use App\Services\ImageService;
use App\Traits\Upload;
use Filer,Input;
use Request;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class UploadController extends Controller
{
    use Upload;

    public function __construct(ImageService $image)
    {
        $this->image = $image;
    }
}
