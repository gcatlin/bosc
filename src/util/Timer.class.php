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
class Timer extends Object
{

    /**
     *
     */
    var $_elapsed;

    /**
     *
     */
    var $_running;

    /**
     *
     */
    var $_start;

    /**
     *
     */
    function Timer($microtime='0 0.0')
    {
        $this->set($microtime);
    }

    /**
     *
     */
    function getElapsed()
    {
        if ($this->_running)
            return $this->elapsedSince(microtime());
        return $this->_elapsed;
    }

    /**
     *
     */
    function getElapsedAt($microtime)
    {
        if ($this->_start != '0 0.0')
        {
            $start = explode(' ', $this->_start);
            $stop  = explode(' ', $microtime);
            $time  = ($stop[1] - $start[1]) + ($stop[0] - $start[0]);
            return $time;
        }
        return 0.0;
    }

    /**
     *
     */
    function reset()
    {
        $this->_elapsed = 0.0;
        $this->_running = FALSE;
        $this->_start = '0 0.0';
    }

    /**
     *
     */
    function setStartTime($microtime)
    {
        $this->start();
        $this->_start = $microtime;
    }

    /**
     *
     */
    function start()
    {
        $this->_elapsed = 0.0;
        $this->_running = TRUE;
        $this->_start = microtime();
    }

    /**
     *
     */
    function stop()
    {
        $this->_elapsed = $this->elapsedSince(microtime());
        $this->_running = FALSE;
        return $this->_elapsed;
    }
}

?>
