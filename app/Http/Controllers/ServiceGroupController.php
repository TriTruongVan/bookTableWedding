<?php

namespace App\Http\Controllers;

use App\Http\Resources\ServiceGroupResource;
use App\Models\ServiceGroup;
use Illuminate\Http\Request;

class ServiceGroupController extends Controller
{
    public function getServiceGroup()
    {
        $serviceGroup = ServiceGroup::get();

        return $this->okRes(['item' => ServiceGroupResource::collection($serviceGroup)]);
    }
}
