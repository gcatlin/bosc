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
if (! isset($_COOKIE))
    $GLOBALS['_COOKIE'] =& $GLOBALS['HTTP_COOKIE_VARS'];

if (! isset($_ENV))
    $GLOBALS['_ENV'] =& $GLOBALS['HTTP_ENV_VARS'];

if (! isset($_FILES))
    $GLOBALS['_FILES'] =& $GLOBALS['HTTP_POST_FILES'];

if (! isset($_GET))
    $GLOBALS['_GET'] =& $GLOBALS['HTTP_GET_VARS'];

if (! isset($_POST))
    $GLOBALS['_POST'] =& $GLOBALS['HTTP_POST_VARS'];

if (! isset($_REQUEST))
    $GLOBALS['_REQUEST'] = array_unique(array_merge($GLOBALS['_FILES'], $GLOBALS['_COOKIE'], $GLOBALS['_POST'], $GLOBALS['_GET']));

if (! isset($_SERVER))
    $GLOBALS['_SERVER'] =& $GLOBALS['HTTP_SERVER_VARS'];

if (! isset($_SESSION) && isset($GLOBALS['HTTP_SESSION_VARS']))
    $GLOBALS['_SESSION'] =& $GLOBALS['HTTP_SESSION_VARS'];

if (! function_exists('version_compare'))
{
    function version_compare($ver1, $ver2, $op=NULL)
    {
        $strings = array('dev', 'a', 'alpha', 'b', 'beta', 'RC', 'pl');
        $values  = array(-5,    -4,  -4,      -3,   -3,    -2,   -1);

        $ver1 = explode('.', str_replace($strings, $values, preg_replace('/([^0-9.]+)/', '.\1.', preg_replace('/[_+-]/', '.', $ver1))));
        $ver2 = explode('.', str_replace($strings, $values, preg_replace('/([^0-9.]+)/', '.\1.', preg_replace('/[_+-]/', '.', $ver2))));

        $compare = 0;
        for ($i = 0, $max = min(count($ver1), count($ver2)); $i < $max; $i++)
        {
            $e1 = (int) $ver1[$i];
            $e2 = (int) $ver2[$i];
            if ($e1 != $e2)
            {
                $compare = ($e1 < $e2 ? -1 : 1);
                break;
            }
        }

        switch($op)
        {
            case '<':
            case 'lt':
                $compare = ($compare == -1 ? 1 : 0);
                break;
            case '<=':
            case 'le':
                $compare = ($compare <=  0 ? 1 : 0);
                break;
            case '>':
            case 'gt':
                $compare = ($compare ==  1 ? 1 : 0);
                break;
            case '>=':
            case 'ge':
                $compare = ($compare >=  0 ? 1 : 0);
                break;
            case '==':
            case '=':
            case 'eq':
                $compare = ($compare ==  0 ? 1 : 0);
                break;
            case '!=':
            case '<>':
            case 'ne':
                $compare = ($compare !=  0 ? 1 : 0);
                break;
            case NULL:
            default:
                break;
        }
        return $compare;
    }
}

if (! function_exists('vprintf'))
{
    function vprintf($format, $args)
    {
        array_unshift($args, $format);
        return call_user_func_array('printf', $args);
    }
}

if (! function_exists('vsprintf'))
{
    function vsprintf($format, $args)
    {
        array_unshift($args, $format);
        return call_user_func_array('sprintf', $args);
    }
}

?>
