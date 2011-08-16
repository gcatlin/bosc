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
 *
 */
class PriorityQueue extends Object
{

    /**
     *
     */
    var $_arr = array();

    /**
     *
     *
     *
     */
    function PriorityQueue()
    {
    }

    /**
     *
     *
     * @return bool
     */
    function isEmpty()
    {
        return (empty($this->_arr));
    }

    /**
     *
     *
     * @return object
     */
    function &pop()
    {
        $heap =& $this->_arr;
        if (count($heap) == 1)
        {
            $obj =& $heap[0][1];
            $this->_arr = array();
            return $obj;
        }
        if (count($heap) >= 1)
        {
            $obj =& $heap[0][1];
            $elem = array_pop($heap);
            $i = 0;
            $l = count($heap);
            while (($j = ($i << 1) + 1) < $l)
            {
                if ($j + 1 < $l && $heap[$j][0] < $heap[$j + 1][0])
                    $j++;
                if ($elem[0] < $heap[$j][0])
                {
                    $heap[$i] = $heap[$j];
                    $i = $j;
                }
                else break;
            }
            $heap[$i] = $elem;
            return $obj;
        }
        return NULL;
    }

    /**
     *
     *
     * @param  object $obj
     * @param  int    $priority
     * @return void
     */
    function push(&$obj, $priority)
    {
        $heap =& $this->_arr;
        $j = count($heap);
        while (0 < $j && $heap[$i = ($j - 1) >> 1][0] < $priority)
        {
            $heap[$j] = $heap[$i];
            $j = $i;
        }
        $heap[$j] = array($priority);
        $heap[$j][] =& $obj;
    }

    /**
     *
     *
     * @return int
     */
    function size()
    {
        return count($this->_arr);
    }

    /**
     *
     *
     * @return object
     */
    function &top()
    {
        return $this->_arr[0][1];
    }

}

?>
