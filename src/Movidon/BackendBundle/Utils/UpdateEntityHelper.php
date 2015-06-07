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
                if (strlen($value) === 0) {
                    $entity->$method(null);
                } else {
                    $entity->$method($value);
                }
                $updatedValues[$prefix . $key] = $value;
            }
        }

        return $updatedValues;
    }
}