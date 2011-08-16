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
if (! defined('STDIN')
    define('STDIN',  fopen('php://stdin',  'r'));

if (! defined('STDOUT')
    define('STDOUT', fopen('php://stdout', 'w'));

if (! defined('STDERR')
    define('STDERR', fopen('php://stderr', 'w'));

if (! defined('UPLOAD_ERR_OK')
    define('UPLOAD_ERR_OK', 0);

if (! defined('UPLOAD_ERR_INI_SIZE')
    define('UPLOAD_ERR_INI_SIZE', 1);

if (! defined('UPLOAD_ERR_FORM_SIZE')
    define('UPLOAD_ERR_FORM_SIZE', 2);

if (! defined('UPLOAD_ERR_PARTIAL')
    define('UPLOAD_ERR_PARTIAL', 3);

if (! defined('UPLOAD_ERR_NO_FILE')
    define('UPLOAD_ERR_NO_FILE', 4);

if (! function_exists('file_get_contents'))
{
    function file_get_contents($filename)
    {
        $fh = @fopen($filename, 'rb');
        $content = @fread($fh, @filesize($filename));
        @fclose($fh);
        return $content;
    }

}

if (! function_exists('get_include_path'))
{
    function get_include_path()
    {
        return ini_get('include_path');
    }

}

if (! function_exists('html_entity_decode'))
{
    function html_entity_decode($str, $style=NULL)
    {
        return strtr($str, array_flip(get_html_translation_table(HTML_ENTITIES)));
    }

}

if (! function_exists('ob_get_clean'))
{
    function ob_get_clean()
    {
        $buffer = ob_get_contents();
        if ($buffer !== FALSE)
            ob_end_clean();
        return $buffer;
    }

}

if (! function_exists('ob_get_flush'))
{
    function ob_get_flush()
    {
        $buffer = ob_get_contents();
        if ($buffer !== FALSE)
            ob_end_flush();
        return $buffer;
    }

}

if (! function_exists('restore_include_path'))
{
    function restore_include_path()
    {
        return ini_restore('include_path');
    }

}

if (! function_exists('set_include_path'))
{
    function set_include_path($path)
    {
        return ini_set('include_path', $path);
    }

}

if (! function_exists('str_shuffle'))
{
    function str_shuffle($str)
    {
        $str2 = '';
        for ($i = 0; $len = strlen($str); $i < $len; $i++)
            $str2 .= $str[mt_rand(0, $len - 1)];
        return $str2;
    }
}

?>
