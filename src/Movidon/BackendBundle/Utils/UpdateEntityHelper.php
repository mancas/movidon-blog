<?php
namespace Movidon\BackendBundle\Utils;

class UpdateEntityHelper
{
    public static function updateEntity($data, &$entity, $prefix = '')
    {
        $updatedValues = array();
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);
            $getMethod = 'get' . ucfirst($key);
            if (method_exists($entity, $method) && $entity->$getMethod() !== $value) {
                $entity->$method($value);
                $updatedValues[$prefix . $key] = $value;
            }
        }

        return $updatedValues;
    }
}