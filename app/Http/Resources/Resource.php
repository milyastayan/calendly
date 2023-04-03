<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    /**
     * @param $type
     * @param $resource
     *
     * @return mixed
     */
    protected function getResourceClass($type, $resource)
    {
        $className = $this->getClassName($type);
        $className = 'App\Http\Resources\\' . $className . 'Resource';

        return class_exists($className) ? new $className($resource) : $resource;
    }
}
