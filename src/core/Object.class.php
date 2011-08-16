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
 * @subpackage core
 */
if (! defined('BOSC'))
    require_once(realpath(dirname(__FILE__).'/../bosc.inc.php'));

/**
 * Object is the root of the class hierarchy. Every Bosc class has Object as a
 * superclass.
 */
class Object
{

    /**
     * Returns the name of the class of this object.
     *
     * @return string
     */
    function getClass()
    {
        return get_class($this);
    }

    /**
     * Returns an array of the method names for this object.
     *
     * @return array
     */
    function getMethods()
    {
        return get_class_methods(get_class($this));
    }

    /**
     * Returns parent class name for this object.
     *
     * @return string
     */
    function getParentClass()
    {
        return get_parent_class($this);
    }

    /**
     * Returns an associative array of the properties for this object.
     *
     * @return array
     */
    function getProperties()
    {
        return get_object_vars($this);
    }

    /**
     * Determine whether this object is an instance of the specified object
     * class.
     *
     * @param  string $className
     * @return bool
     */
    function isInstanceOf($className)
    {
        return is_a($this, $className);
    }

    /**
     * Allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     */
    function __toString()
    {
        list(, $id) = @explode('#', ''.$this);
        return get_class($this)." Object (id #$id)";
    }

}

?>
