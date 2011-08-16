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
if (! function_exists('php_combined_lcg'))
{
    function php_combined_lcg()
    {
        $tod = gettimeofday();
        $lcg['s1'] = $tod['sec'] ^ (~$tod['usec']);
        $lcg['s2'] = posix_getpid();

        $q = (int) ($lcg['s1'] / 53668);
        $lcg['s1'] = (int) (40014 * ($lcg['s1'] - 53668 * $q) - 12211 * $q);
        if ($lcg['s1'] < 0)
            $lcg['s1'] += 2147483563;

        $q = (int) ($lcg['s2'] / 52774);
        $lcg['s2'] = (int) (40692 * ($lcg['s2'] - 52774 * $q) - 3791 * $q);
        if ($lcg['s2'] < 0)
            $lcg['s2'] += 2147483399;

        $z = (int) ($lcg['s1'] - $lcg['s2']);
        if ($z < 1)
            $z += 2147483562;

        return $z * 4.656613e-10;
    }
}

if (! function_exists('session_regenerate_id'))
{
    function session_regenerate_id()
    {
       $tod = gettimeofday();
       $buf = sprintf("%.15s%ld%ld%0.8f", $_SERVER['REMOTE_ADDR'], $tod['sec'], $tod['usec'], php_combined_lcg() * 10);
       session_id(md5($buf));
       if (ini_get('session.use_cookies'))
           setcookie(session_name(), session_id(), NULL, '/');
       return TRUE;
    }
}

?>