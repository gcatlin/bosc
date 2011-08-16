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
 * About Bosc.
 *
 * Resources:
 *   o http://java.sun.com/j2se/javadoc/writingdoccomments/
 *   o http://www.agsrhichome.bnl.gov/Controls/doc/codingGuidelines/codingGuidelines.html
 *   o http://www.mines.edu/Academic/courses/math_cs/macs261/macs261j/LABS/Documentation.html
 *
 * @package bosc
 */

/**
 * Define Bosc constants
 */
define('BOSC',     dirname(__FILE__));
define('BOSC_EXT', realpath(BOSC.'/../ext'));

/**
 * Load PHP compatability functions
 */
if (! function_exists('version_compare'))
    include_once(BOSC.'/php4.1.0.inc.php');

if (version_compare(PHP_VERSION, '4.2.0', '<'))
    include_once(BOSC.'/php4.2.0.inc.php');

if (version_compare(PHP_VERSION, '4.3.0', '<'))
    include_once(BOSC.'/php4.3.0.inc.php');

if (version_compare(PHP_VERSION, '4.3.0-RC2', '<'))
    include_once(BOSC.'/php4.3.0-rc2.inc.php');

if (version_compare(PHP_VERSION, '4.3.2', '<'))
    include_once(BOSC.'/php4.3.2.inc.php');

if (version_compare(PHP_VERSION, '5.0.0', '<'))
    include_once(BOSC.'/php5.0.0.inc.php');

/**
 * Load essential Bosc classes and functions
 */
require_once(BOSC.'/core/Object.class.php');
require_once(BOSC.'/core/Error.class.php');
require_once(BOSC.'/http/Request.class.php');
require_once(BOSC.'/http/Response.class.php');
require_once(BOSC.'/http/Session.class.php');
require_once(BOSC.'/functions.inc.php');
ini_set('include_path', get_include_path().PATH_SEPARATOR.BOSC_EXT.'/PEAR');

?>
