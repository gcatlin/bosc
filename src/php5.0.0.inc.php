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
 * @package bosc
 */
if (! defined('BOSC'))
    require_once(realpath(dirname(__FILE__).'/../bosc.inc.php'));

/**
 *
 */
if (! defined('E_STRICT'))
    define('E_STRICT', 2048);

if (! defined('FILE_USE_INCLUDE_PATH'))
    define('FILE_USE_INCLUDE_PATH', 1);

if (! defined('FILE_IGNORE_NEW_LINES'))
    define('FILE_IGNORE_NEW_LINES', 2);

if (! defined('FILE_SKIP_EMPTY_LINES'))
    define('FILE_SKIP_EMPTY_LINES', 4);

if (! defined('FILE_APPEND'))
    define('FILE_APPEND', 8);

if (! defined('FILE_NO_DEFAULT_CONTEXT'))
    define('FILE_NO_DEFAULT_CONTEXT', 16);

?>