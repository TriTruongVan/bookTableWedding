<?php

namespace App\Http\Controllers;

use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function getService()
    {
        $service = Service::get();

        return $this->okRes(['item' => ServiceResource::collection($service)]);
    }
}
