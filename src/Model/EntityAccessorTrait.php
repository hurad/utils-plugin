<?php

namespace Utils\Model;

use Cake\Utility\Inflector;

/**
 * Entity Accessor Trait
 *
 * @method set($property, $value = null, $options = []) Sets a single property inside this entity.
 * @method get($property) Returns the value of a property by name.
 * @property array $_properties Holds all properties and their values for this entity.
 *
 * @package Utils\Model
 */
trait EntityAccessorTrait
{
    /**
     * Is triggered when invoking inaccessible methods in an object context.
     *
     * @param string $name      The $name argument is the name of the method being called.
     * @param array  $arguments The $arguments argument is an enumerated array containing
     *                          the parameters passed to the $name'ed method.
     *
     * @return mixed
     */
    function __call($name, $arguments)
    {
        $propertyName = Inflector::underscore(substr($name, 3));

        if (strpos($name, 'get', 0) !== false) {
            return $this->get($propertyName);
        } elseif (strpos($name, 'set', 0) !== false) {
            $this->set($propertyName, $arguments[0]);
            return $this;
        }
    }
}
