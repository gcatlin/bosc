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
class ArrayList extends Object
{

    /**
     *
     *
     */
    var $_arr;

    /**
     *
     * @param  array $arr
     * @return void
     */
    function ArrayList($arr)
    {
        $this->_arr = (is_array($arr) ? $arr : array($arr));
    }

    /**
     * Ensures that this list contains the specified element.
     *
     * @param  int    $index
     * @param  object $element
     * @return void
     */
    function add($index, &$element)
    {
        $this->_arr = array_splice($this->_arr, $index, 0, array($element));
    }

    /**
     * Adds all of the elements in the specified list to this list.
     *
     * @param  int       $index
     * @param  ArrayList $list
     * @return void
     */
    function addAll($index, &$list)
    {
        $this->_arr = array_splice($this->_arr, $index, 0, $list->__toArray());
    }

    /**
     * Appends the specified element to the end of this list.
     *
     * @param  mixed   $element
     * @return void
     */
    function append(&$element)
    {
        $this->_arr[] =& $element;
    }

    /**
     * Appends all of the elements in the specified list to the end of this
     * list.
     *
     * @param  ArrayList $list
     * @return void
     */
    function appendAll(&$list)
    {
        $this->_arr = array_merge($this->_arr, $list->__toArray());
    }

    /**
     * Removes all of the elements from this list.
     *
     * @return void
     */
    function clear()
    {
        unset($this->_arr);
        $this->_arr = array();
    }

    /**
     * Returns true if this list contains the specified element.
     *
     * @param  mixed   $element
     * @return bool
     */
    function contains(&$element)
    {
        return in_array($element, $this->_arr, TRUE);
    }

    /**
     * Returns true if this list contains all of the elements in the specified
     * list.
     *
     * @param  ArrayList $list
     * @return bool
     */
    function containsAll(&$list)
    {
        foreach ($list->__toArray() as $element)
            if (! in_array($element, $this->_arr, TRUE))
                return FALSE;
        return TRUE;
    }

    /**
     * Returns the element at the specified position in this list.
     *
     * @param  int     $index
     * @return mixed
     */
    function &get($index)
    {
        return $this->_arr[$index];
    }

    /**
     * Returns the element at the front of this list.
     *
     * @return mixed
     */
    function &getFirst()
    {
        return $this->_arr[0];
    }

    /**
     * Returns the element at the end of this list.
     *
     * @return mixed
     */
    function &getLast()
    {
        return $this->_arr[count($this->_arr) - 1];
    }

    /**
     * Returns the index in this list of the first occurence of the specified
     * element, or NULL if the list does not contain this element.
     *
     * @param  mixed   $element
     * @return int
     */
    function indexOf(&$element)
    {
        $index = array_search($element, $this->_arr);
        if ($index !== FALSE)
            return $index;
        return NULL;
    }

    /**
     * Returns true if this list contains no elements.
     *
     * @return bool
     */
    function isEmpty()
    {
        return (count($this->_arr) == 0);
    }

    /**
     * Returns an iterator over the elements in this list.
     *
     * @return Iterator
     */
    function &iterator()
    {
        return $this->_listIterator(0);
    }

    /**
     * Returns the index in this list of the last occurence of the specified
     * element, or -1 if the list does not contain this element.
     *
     * @param  mixed   $element
     * @return int
     */
    function lastIndexOf(&$element)
    {
        $pos = array_search($element, array_reverse($this->_arr));
        if ($pos !== FALSE)
            return count($this->_arr) - 1 - $pos;
        return -1;
    }

    /**
     * Returns a list iterator of the elements in this list (in proper sequence),
     * starting at the specified position in the list.
     *
     * @param  int     $fromIndex
     * @return ArrayList
     */
    function &listIterator($fromIndex = 0)
    {
        return new ArrayListIterator($this, $fromIndex);
    }

    /**
     * Prepends the specified element to this list.
     *
     * @param  mixed   $element
     * @return void
     */
    function prepend(&$element)
    {
        array_unshift($this->_arr, array($element));
    }

    /**
     * Prepends the specified list to this list.
     *
     * @param  ArrayList $list
     * @return void
     */
    function prependAll(&$list)
    {
        array_unshift($this->_arr, $list->__toArray());
    }

    /**
     * Removes the element at the specified position in this list.
     *
     * @param  int     $index
     * @return mixed
     */
    function &remove($index)
    {
        $element =& $this->_arr[$index];
        array_splice($this->_arr, $index, 1);
        return $element;
    }

    /**
     * Removes all this list's elements that are contained in the specified
     * list.
     *
     * @param  ArrayList $list
     * @return void
     */
    function removeAll(&$list)
    {
        $size = count($this->_arr);
        $list = $list->__toArray();
        $this->_arr = array_diff($this->_arr, $list);
        return (count($this->_arr) != $size);
    }

    /**
     * Removes the element at the front ofthis list.
     *
     * @return mixed $element
     */
    function &removeFirst()
    {
        $element =& $this->_arr[0];
        array_shift($this->_arr);
        return $element;
    }

    /**
     * Removes the element at the end of this list.
     *
     * @return mixed $element
     */
    function &removeLast()
    {
        $element =& $this->_arr[$this->_size() - 1];
        array_pop($this->_arr);
        return $element;
    }

    /**
     * Removes from this list all of the elements whose index is between
     * fromIndex, inclusive, and toIndex, exclusive.
     *
     * @param  int $fromIndex
     * @param  int $toIndex
     * @return void
     */
    function removeRange($fromIndex, $toIndex)
    {
        array_splice($this->_arr, $fromIndex, $toIndex - $fromIndex);
    }

    /**
     * Retains only the elements in this list that are contained in the specified
     * list.
     *
     * @param  ArrayList $list
     * @return bool
     */
    function retainAll(&$list)
    {
        $size = count($this->_arr);
        $list = $list->__toArray();
        $this->_arr = array_intersect($this->_arr, $list);
        return (count($this->_arr) != $size);
    }

    /**
     * Replaces the element at the specified position in this list with the
     * specified element.
     *
     * @param  int   $index
     * @param  mixed $element
     * @return mixed
     */
    function &set($index, &$element)
    {
        $old_element =& $this->get($index);
        $this->_arr[$index] =& $element;
        return $old_element;
    }

    /**
     * Returns the number of elements in this list.
     *
     * @return int
     */
    function size()
    {
        return count($this->_arr);
    }

    /**
     * Returns a view of the portion of this list between fromIndex, inclusive,
     * and toIndex, exclusive.
     *
     * @param  int       $fromIndex
     * @param  int       $toIndex
     * @return ArrayList
     */
    function &subList($fromIndex, $toIndex)
    {
        $fromIndex = (int) $fromIndex;
        $toIndex = (int) $toIndex;
        if ($toIndex < $fromIndex)
            return NULL;
        return new ArrayList(array_slice($this->_arr, $fromIndex, $toIndex-$fromIndex));
    }

    /**
     * Returns an array containing all of the elements in this list.
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
        foreach ($this->_arr as $value)
            $arr[] = $value->__toString();
        return implode(', ', $arr);
    }

}

/**
 *
 */
class ArrayListIterator extends Object
{

    /**
     *
     */
    var $_list;

    /**
     *
     */
    var $_pos;

    /**
     *
     */
    var $_size;

    /**
     *
     *
     * @return void
     */
    function ArrayListIterator(&$list, $fromIndex=0)
    {
        $this->_list =& $list;
        $this->_size = $list->size();
        $this->_pos  = $fromIndex;
    }

    /**
     * Returns true if this list iterator has more elements when traversing the
     * list in the forward direction.
     *
     * @return bool
     */
    function hasNext()
    {
        return ($this->_pos < $this->_size);
    }

    /**
     * Returns true if this list iterator has more elements when traversing the
     * list in the reverse  direction.
     *
     * @return bool
     */
    function hasPrevious()
    {
        return (0 < $this->_pos);
    }

    /**
     * Returns the next element in the list.
     *
     * @return object
     */
    function &next()
    {
        return $this->_list->get($this->_pos++);
    }

    /**
     * Returns the index of the element that would be returned by a subsequent
     * call to next.
     *
     * @return int
     */
    function nextIndex()
    {
        return $this->_pos;
    }

    /**
     * Returns the previous element in the list.
     *
     * @return object
     */
    function &previous()
    {
        return $this->_list->get(--$this->_pos);
    }

    /**
     * Returns the index of the element that would be returned by a subsequent
     * call to previous.
     *
     * @return int
     */
    function previousIndex()
    {
        return ($this->_pos - 1);
    }

}

?>
