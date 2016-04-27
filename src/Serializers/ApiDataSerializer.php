<?php

namespace Appitized\Optimus\Serializers;

use League\Fractal\Serializer\ArraySerializer;

class ApiDataSerializer extends ArraySerializer
{
    /**
     * Serialize a collection.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function collection($resourceKey, array $data)
    {
        return ($resourceKey) ? $data : ['data' => $data];
    }
    /**
     * Serialize an item.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function item($resourceKey, array $data)
    {
        return ($resourceKey) ? $data : ['data' => $data];
    }
}
