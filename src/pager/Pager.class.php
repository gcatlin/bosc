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
 * @subpackage pager
 */
if (! defined('BOSC'))
    require_once(realpath(dirname(__FILE__).'/../bosc.inc.php'));

require_once(BOSC_EXT.'/PEAR/Pager/Sliding.php');

/**
 *
 */
class Pager extends Object
{

    /**
     *
     */
    var $_itemsPerPage;

    /**
     *
     */
    var $_pager;

    /**
     *
     */
    function Pager($totalItems, $itemsPerPage=30, $delta=5)
    {
        $this->_pager = new Pager_Sliding(array(
            'delta'                 => $delta,
            'firstPagePost'         => '',
            'firstPagePre'          => '',
            'firstPageText'         => '&laquo; First',
            'lastPagePost'          => '',
            'lastPagePre'           => '',
            'lastPageText'          => 'Last &raquo;',
            'nextImg'               => 'Next &raquo;',
            'perPage'               => $itemsPerPage,
            'prevImg'               => '&laquo; Prev',
            'separator'             => '|',
            'spacesAfterSeparator'  => 1,
            'spacesBeforeSeparator' => 1,
            'totalItems'            => $totalItems,
            'urlVar'                => 'p'));
        $this->_itemsPerPage = (int) $itemsPerPage;
    }

    /**
     *
     */
    function getCurrentPage()
    {
        return $this->_pager->getCurrentPageID();
    }

    /**
     *
     */
    function getItemsPerPage()
    {
        return $this->_itemsPerPage;
    }

    /**
     *
     */
    function getItemRange($separator='-')
    {
        list($low, $high) = $this->_pager->getOffsetByPageId();
        return ($low == $high ? $low : $low.$separator.$high);
    }

    /**
     *
     */
    function getNumItems()
    {
        return $this->_pager->numItems();
    }

    /**
     *
     */
    function getNumPages()
    {
        return $this->_pager->numPages();
    }

    /**
     *
     */
    function getOffset()
    {
        list($offset) = $this->_pager->getOffsetByPageId();
        return $offset-1;
    }

    /**
     *
     */
    function toHtml()
    {
        $html = $this->_pager->getLinks();
        return $html['all'];
    }

}
