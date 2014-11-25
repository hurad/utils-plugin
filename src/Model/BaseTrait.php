<?php

namespace Utils\Model;

use Cake\Database\Exception;

/**
 * Base Trait
 *
 * @package Utils\Model
 */
trait BaseTrait
{
    /**
     * Translates a fieldname to another type
     *
     * @param      string $name     field name
     * @param      string $fromType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                              BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @param      string $toType   One of the class type constants
     *
     * @throws Exception
     * @return string          translated name of the field.
     */
    public static function translateFieldName($name, $fromType, $toType)
    {
        $toNames = self::getFieldNames($toType);
        $key = isset(self::$fieldKeys[$fromType][$name]) ? self::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new Exception("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(
                    self::$fieldKeys[$fromType],
                    true
                ));
        }

        return $toNames[$key];
    }

    /**
     * Returns an array of field names.
     *
     * @param string $type      The type of fieldnames to return:
     *                          One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                          BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     *
     * @throws Exception
     * @return array           A list of field names
     */
    public static function getFieldNames($type = BasePeer::TYPE_FIELDNAME)
    {
        if (!array_key_exists($type, self::$fieldNames)) {
            throw new Exception('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, ::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return self::$fieldNames[$type];
    }
}
