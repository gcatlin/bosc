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
 * @subpackage cache
 */
if (! defined('BOSC'))
    require_once(realpath(dirname(__FILE__).'/../bosc.inc.php'));

/**
 *
 */
class Cache extends Object
{

    var $_expiration;
    var $_id;
    var $_lifetime;

    /**
     *
     */
    function Cache($id, $lifetime=0)
    {
        $this->setId($id);
        $this->setLifetime($lifetime);
    }

    /**
     *
     * @return bool
     */
    function delete()
    {
    }

    /**
     *
     * @return int
     */
    function getExpiration()
    {
        return $this->_expiration;
    }

    /**
     *
     * @return string
     */
    function getId()
    {
        return $this->_id;
    }

    /**
     *
     * @return int
     */
    function getLastModified()
    {
    }

    /**
     *
     * @return int
     */
    function getLifetime()
    {
        return $this->_lifetime;
    }

    /**
     *
     * @return bool
     */
    function isCached()
    {
    }

    /**
     *
     * @return string
     */
    function read()
    {
    }

    /**
     *
     */
    function setExpiration($timestamp)
    {
        $old_setting = $this->getExpiration();
        $this->_expiration = (int) $timestamp;
        return $old_setting;
    }

    /**
     *
     * @param  string $id
     * @return string
     */
    function setId($id)
    {
        $old_setting = $this->getId();
        $this->_id = $id;
        return $old_setting;
    }

    /**
     *
     * @param  int $timestamp
     * @return int
     */
    function setLastModified($timestamp)
    {
    }

    /**
     *
     * @param  int $seconds
     * @return int
     */
    function setLifetime($seconds)
    {
        $old_setting = $this->getLifetime();
        $this->_lifetime = (int) $seconds;
        $this->setExpires(time() + $seconds);
        return $old_setting;
    }

    /**
     *
     * @param  string $cache
     * @return bool
     */
    function write($cache)
    {
    }

}

?>
