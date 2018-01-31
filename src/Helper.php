<?php

namespace Vidovic\Rolly;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use InvalidApplicationNameExceptio;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
class Helper
{
    /**
     * Gets the it from an array, object or integer.
     *
     * @param  mixed  $object
     * @param  string  $type
     * @return int
     */
    public static function getIdFor($object, $type)
    {
        if (is_null($object)) {
            return null;
        } elseif (is_object($object)) {
            return $object->getKey();
        } elseif (is_array($object)) {
            return $object['id'];
        } elseif (is_numeric($object)) {
            return $object;
        } elseif (is_string($object)) {
            return call_user_func_array([
                Config::get("rolly.models.{$type}"), 'where'
            ], ['name', $object])->firstOrFail()->getKey();
        }

        throw new InvalidArgumentException(
            'getIdFor function only accepts an integer, a Model object or an array with an "id" key'
        );
    }

    /**
     * Checks if the string passed contains a pipe '|' and explodes the string to an array.
     * @param  string|array  $value
     * @return string|array
     */
    public static function standardize($value, $toArray = false)
    {
        if (is_array($value) || ((strpos($value, '|') === false) && !$toArray)) {
            return $value;
        }

        return explode('|', $value);
    }

    /**
     * Check if a string is a valid relationship name.
     *
     * @param string $relationship
     * @return boolean
     */
    public static function isValidRelationship($relationship)
    {
        return in_array($relationship, ['roles', 'permissions']);
    }

    /**
     * Creates a model from an array filled with the class data.
     *
     * @param string $class
     * @param string|\Illuminate\Database\Eloquent\Model $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function hidrateModel($class, $data)
    {
        if ($data instanceof Model) {
            return $data;
        }

        if (!isset($data['pivot'])) {
            throw new \Exception("The 'pivot' attribute in the {$class} is hidden");
        }

        $model = new $class;
        $model->setAttribute('id', $data['id'])->setAttribute('name', $data['name']);
        $model->setRelation(
            'pivot',
            MorphPivot::fromRawAttributes($model, $data['pivot'], 'pivot_table')
        );

        return $model;
    }
}
