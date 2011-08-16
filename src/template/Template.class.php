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
 * @subpackage template
 */
if (! defined('BOSC'))
    require_once(realpath(dirname(__FILE__).'/../bosc.inc.php'));

/**
 *
 */
class Template extends Object
{

    /**
     *
     */
    var $_file;

    /**
     *
     */
    var $_output;

    /**
     *
     */
    var $_parsed = FALSE;

    /**
     *
     */
    var $_ldelim = '{';

    /**
     *
     */
    var $_markers = array();

    /**
     *
     */
    var $_rdelim = '}';

    /**
     *
     */
    function Template($file)
    {
        $this->_file = $file;
    }

    /**
     *
     */
    function addMarker($name, $value)
    {
        $this->_markers[$name] = $value;
        $this->_parsed = FALSE;
    }

    /**
     *
     */
    function output()
    {
        if (! $this->_parsed)
            $this->parse();
        echo $this->_output;
    }

    /**
     *
     */
    function parse()
    {
        $data = file_get_contents($this->_file);
        foreach ($this->_markers as $marker=>$value)
            $data = str_replace($this->_ldelim.$marker.$this->_rdelim, $value, $data);
        $this->_output = $data;
        $this->_parsed = TRUE;
    }

}

?>
