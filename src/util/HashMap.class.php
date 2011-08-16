<?php
//  ___________________________________________________________________________
// /                                                                           \
// |  Bosc                                                                     |
// |                                                                           |
// |      Bosc is a PHP class library for writing web applications             |
// |      http://bosc-project.org/                                             |
// |                                                                           |
// |  Copyright (c) 2004  Geoff Catlin <geoff@bosc-project.org>                |
// |  ________________________________________________________________________ |
// |                                                                           |
// |  This library is free software; you can redistribute it and or            |
// |  modify it under the terms of the GNU Lesser General Public               |
// |  License as published by the Free Software Foundation; either             |
// |  version 2.1 of the License, or (at your option) any later version.       |
// |                                                                           |
// |  This library is distributed in the hope that it will be useful,          |
// |  but WITHOUT ANY WARRANTY; without even the implied warranty of           |
// |  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU        |
// |  Lesser General Public License for more details.                          |
// |                                                                           |
// |  You should have received a copy of the GNU Lesser General Public         |
// |  License along with this library; if not, write to the Free Software      |
// |  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA  |
// \___________________________________________________________________________/

/**
 * @package    bosc
 * @subpackage util
 */
if (! defined('BOSC'))
    require_once(realpath(dirname(__FILE__).'/../bosc.inc.php'));

/**
 *
 */
class HashMap extends Object
{

    /**
     *
     */
    var $_arr = array();

    /**
     *
     * @param  array $arr
     * @return void
     */
    function HashMap($arr)
    {
        $this->_arr = (is_array($arr) ? $arr : array($arr));
    }

    /**
     * Removes all mappings from this map.
     *
     * @return void
     */
    function clear()
    {
        unset($this->_arr);
        $this->_arr = array();
    }

    /**
     * Returns true if this map contains a mapping for the specified key.
     *
     * @param  string $key
     * @return bool
     */
    function containsKey($key)
    {
        return array_key_exists($key, $this->_arr);
    }

    /**
     * Returns true if this map maps one or more keys to the specified value.
     *
     * @param  object $element
     * @return bool
     */
    function containsValue(&$element)
    {
        return in_array($element, $this->_arr, TRUE);
    }

    /**
     * Returns the value to which the specified key is mapped in this map, or
     * null if the map contains no mapping for this key.
     *
     * @param  string $key
     * @return object
     */
    function &get($key)
    {
        if ($this->containsKey($key))
            return $this->_arr[$key];
        return NULL;
    }

    /**
     * Returns an array of the keys contained in this map.
     *
     * @return array
     */
    function keys()
    {
        return array_keys($this->_arr);
    }

    /**
     * Returns true if this List contains no elements.
     *
     * @return bool
     */
    function isEmpty()
    {
        return empty($this->_arr);
    }

    /**
     * Associates the specified value with the specified key in this map.
     *
     * @param  string $key
     * @param  object $element
     * @return void
     */
    function put($key, &$element)
    {
        $this->_arr[$key] =& $element;
    }

    /**
     * Copies all of the mappings from the specified map to this map -- these
     * mappings will replace any mappings that this map had for any of the keys
     * currently in the specified map.
     *
     * @param  HashMap $map
     * @return void
     */
    function putAll(&$map)
    {
        $this->_arr = array_merge($this->_arr, $map->__toArray());
    }

    /**
     * Removes the mapping for this key from this map if present.
     *
     * @param  string $key
     * @return object
     */
    function &remove($key)
    {
        $element = $this->getKey($key);
        unset($this->_arr[$key]);
        return $element;
    }

    /**
     * Returns the number of key-value mappings in this map.
     *
     * @return int
     */
    function size()
    {
        return count($this->_arr);
    }

    /**
     * Returns an array of the values contained in this map.
     *
     * @return array
     */
    function values()
    {
        return array_values($this->_arr);
    }

    /**
     * Returns an array containing all of the elements in this map.
     *
     * @return array
     */
    function __toArray()
    {
        return $this->_arr;
    }

    /**
     * Returns a primitive string representation of this map.
     *
     * @return string
     */
    function __toString()
    {
        $arr = array();
        foreach ($this->_arr as $key => $value)
            $arr[] = $key . ':' . $value->__toString();
        return implode(', ', $arr);
    }

}

?>