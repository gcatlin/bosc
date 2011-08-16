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
 * The Stack class represents a last-in-first-out (LIFO) stack of objects.
 *
 * When a stack is first created, it contains no items.
 */
class Stack extends Object
{

    /**
     *
     */
    var $_arr = array();

    /**
     *
     */
    function Stack()
    {
    }

    /**
     * Tests if this stack is empty.
     *
     * @return bool
     */
    function empty()
    {
        return (count($this->_arr) == 0);
    }

    /**
     * Looks at the object at the top of this stack without removing it from the
     * stack.
     *
     * @return object
     */
    function &peek()
    {
        return $this->_arr[count($this->_arr) - 1];
    }

    /**
     * Removes the object at the top of this stack and returns that object as
     * the value of this function.
     *
     * @return object
     */
    function &pop()
    {
        $obj =& $this->_arr[count($this->_arr) - 1];
        array_pop($this->_arr);
        return $obj;
    }

    /**
     * Pushes an object onto the top of this stack.
     *
     * @param  object $obj
     * @return void
     */
    function push(&$obj)
    {
        $this->_arr[] =& $obj;
    }

    /**
     * Returns the 1-based position where an object is on this stack. If the
     * object occurs as an item in this stack, this method returns the
     * distance from the top of the stack of the occurrence nearest the top of
     * the stack; the topmost item on the stack is considered to be at distance
     * 1. The return value NULL indicates that the object is not on the stack.
     *
     * @param  object $obj
     * @return int
     */
    function search(&$obj)
    {
        $pos = array_search($obj, array_reverse($this->_arr));
        return ($pos === FALSE ? NULL : count($this->_arr) - 1 - $pos);
    }

}

?>
