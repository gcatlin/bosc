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
 * @subpackage sql
 */
if (! defined('BOSC'))
    require_once(realpath(dirname(__FILE__).'/../bosc.inc.php'));

/**
 *
 */
class Dsn extends Object
{

    /**
     *
     */
    var $_dsn;

    /**
     *
     * @param  string $dsn
     * @return void
     */
    function Dsn($dsn)
    {
        include_once(BOSC.'/net/Url.class.php');
        $this->_dsn = new Url($dsn);
    }

    /**
     *
     *
     * @return string
     */
    function getDatabase()
    {
        return basename($this->_dsn->getPath());
    }

    /**
     *
     *
     * @return string
     */
    function getDriver()
    {
        return $this->_dsn->getScheme();
    }

    /**
     *
     *
     * @return string
     */
    function getHost()
    {
        return $this->_dsn->getHost().($this->_dsn->getPort() ? ':'.$this->_dsn->getPort() : '');
    }

    /**
     *
     *
     * @return array
     */
    function getParameters()
    {
        return explode('&', $this->_dsn->getQuery());
    }

    /**
     *
     *
     * @return string
     */
    function getPassword()
    {
        return $this->_dsn->getPassword();
    }

    /**
     *
     *
     * @return string
     */
    function getTable()
    {
        return $this->_dsn->getFragment();
    }

    /**
     *
     *
     * @return string
     */
    function getUsername()
    {
        return $this->_dsn->getUsername();
    }

    /**
     *
     *
     * @param  string $database
     * @return string
     */
    function setDatabase($database)
    {
        return basename($this->_dsn->setPath('/'.$database));
    }

    /**
     *
     *
     * @param  string $driver
     * @return string
     */
    function setDriver($driver)
    {
        return $this->_dsn->setScheme($driver);
    }

    /**
     *
     *
     * @param  string $host
     * @return string
     */
    function setHost($host)
    {
        list($host, $port) = explode(':', $host);
        $old_host = $this->_dsn->setHost($host);
        $old_port = $this->_dsn->setPort($port);
        return $old_host.($old_port ? ':'.$old_port : '');
    }

    /**
     *
     *
     * @param  array $parameters
     * @param  bool  $append
     * @return array
     */
    function setParameters($parameters, $append=FALSE)
    {
        return explode('&', $this->_dsn->setQuery($parameters, $append));
    }

    /**
     *
     *
     * @param  string $password
     * @return string
     */
    function setPassword($password)
    {
        return $this->_dsn->setPassword($password);
    }

    /**
     *
     *
     * @param  string $table
     * @return string
     */
    function setTable($table)
    {
        return $this->_dsn->setFragment($table);
    }

    /**
     *
     *
     * @param  string $username
     * @return string
     */
    function setUsername($username)
    {
        return $this->_dsn->setUsername($username);
    }

    /**
     *
     *
     * @return string
     */
    function __toString()
    {
        return $this->_dsn->__toString();
    }

}

?>
