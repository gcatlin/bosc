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
 * @subpackage image
 */
if (! defined('BOSC'))
    require_once(realpath(dirname(__FILE__).'/../bosc.inc.php'));

class Color extends Object
{

    var $blue;
    var $green;
    var $red;

    function Color($red, $green, $blue)
    {
        $this->setRGB($red, $green, $blue);
    }

    function getBlue()
    {
        return $this->blue;
    }

    function getGreen()
    {
        return $this->green;
    }

    function getRed()
    {
        return $this->red;
    }

    function setRGB($red, $green, $blue)
    {
        $this->red   = $red;
        $this->green = $green;
        $this->blue  = $blue;
    }

}

?>