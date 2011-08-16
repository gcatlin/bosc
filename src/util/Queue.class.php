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
 * The queue class represents a first-in-first-out (FIFO) queue of objects.
 *
 * When a queue is first created, it contains no items.
 */
class Queue extends Object
{

    /**
     *
     */
    var $_arr = array();

    /**
     *
     */
    function Queue()
    {
    }

    /**
     * Tests if this queue is empty.
     *
     * @return bool
     */
    function empty()
    {
        return (count($this->_arr) == 0);
    }

    /**
     * Looks at the object at the top of this queue without removing it from the
     * queue.
     *
     * @return Object
     */
    function &peek()
    {
        return $this->_arr[0];
    }

    /**
     * Removes the object at the top of this queue and returns that object as
     * the value of this function.
     *
     * @return Object
     */
    function &pop()
    {
        $obj =& $this->_arr[0];
        array_shift($this->_arr);
        return $obj;
    }

    /**
     * Pushes an object onto the top of this queue.
     *
     * @param  object $obj
     * @return void
     */
    function push(&$obj)
    {
        $this->_arr[] =& $obj;
    }

    /**
     * Returns the 1-based position where an object is on this queue. If the
     * object o occurs as an item in this queue, this method returns the
     * distance from the top of the queue of the occurrence nearest the top of
     * the queue; the topmost item on the queue is considered to be at distance
     * 1. The return value NULL indicates that the object is not on the queue.
     *
     * @param  object $obj
     * @return int
     */
    function search(&$obj)
    {
        $pos = array_search($obj, $this->_arr);
        if ($pos !== FALSE)
            return $pos + 1;
        return NULL;
    }

}

?>