<?php

namespace Utils\Model;

use Cake\Database\Exception;

/**
 * Enum Trait
 *
 * @package Utils\Model
 */
trait EnumTrait
{
    /**
     * Gets the list of values for all ENUM columns
     *
     * @return array
     */
    public static function getValueSets()
    {
        return self::$enumValueSets;
    }

    /**
     * Gets the list of values for an ENUM column
     *
     * @param string $colname The ENUM column name.
     *
     * @throws \Cake\Database\Exception
     * @return array list of possible values for the column
     */
    public static function getValueSet($colname)
    {
        $valueSets = self::getValueSets();

        if (!isset($valueSets[$colname])) {
            throw new Exception(sprintf('Column "%s" has no ValueSet.', $colname));
        }

        return $valueSets[$colname];
    }

    /**
     * Gets the SQL value for the ENUM column value
     *
     * @param string $colname ENUM column name.
     * @param string $enumVal ENUM value.
     *
     * @throws \Cake\Database\Exception
     * @return int SQL value
     */
    public static function getSqlValueForEnum($colname, $enumVal)
    {
        $values = self::getValueSet($colname);
        if (!in_array($enumVal, $values)) {
            throw new Exception(sprintf('Value "%s" is not accepted in this enumerated column', $colname));
        }

        return array_search($enumVal, $values);
    }

    /**
     * Get enum options
     *
     * @param string|array|null $value
     * @param array             $options
     *
     * @return array|string
     */
    protected static function getEnumOptions($value, $options)
    {
        if (is_array($value) && !is_null($value)) {
            $removeOptions = array_diff($value, $options);

            $newOptions = [];
            foreach ($removeOptions as $option) {
                $newOptions[$option] = $options[$option];
            }

            return $newOptions;
        } elseif (!is_array($value) && !is_null($value) && array_key_exists($value, $options)) {
            return $options[(string)$value];
        } elseif (is_null($value)) {
            return $options;
        } else {
            return false;
        }
    }

    /**
     * Get value.
     *
     * @param string $filedName Field name
     *
     * @throws Exception
     * @return null|string
     */
    public function getValue($filedName)
    {
        $col = self::translateFieldName($filedName, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME);

        if (null === $this->_properties[$col]) {
            return null;
        }

        $valueSet = self::getValueSet($filedName);

        if (!isset($valueSet[$this->_properties[$col]])) {
            throw new Exception('Unknown stored enum key: ' . $this->_properties[$col]);
        }

        return $valueSet[$this->_properties[$col]];
    }

    /**
     * Set the value of [status] column.
     *
     * @param string $filedName Field name
     * @param int    $value     New value
     *
     * @throws \Cake\Database\Exception
     * @return $this The current object (for fluent API support)
     */
    public function setValue($filedName, $value)
    {
        if ($value !== null) {
            $valueSet = self::getValueSet($filedName);
            if (!in_array($value, $valueSet)) {
                throw new Exception(sprintf('Value "%s" is not accepted in this enumerated column', $value));
            }
            $value = array_search($value, $valueSet);
        }

        $col = self::translateFieldName($filedName, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME);

        if ($this->get($col) !== $value) {
            $this->set($col, $value);
        }

        return $this;
    }
}
