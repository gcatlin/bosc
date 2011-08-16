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
 * Outputs human-readable information about a variable
 */
function trace($var, $name='$var', $sort=TRUE)
{
    $output  = '<div style="color: black; font: 7pt Verdana; border: 1px solid #000; background: #fff; padding: 10px; margin: 10px; text-align: left">';
    $output .= _trace($var, $name, $sort);
    $output .= '</div>';
    echo $output;
    return TRUE;
}

/**
 * Traces information about a variable
 */
function _trace($var, $name, $sort)
{
    $output       = '';
    $var_prepend  = '<span style="font: bold">'.$name.'</span> =&gt; ';
    $var_append   = '&nbsp;&nbsp;&nbsp;<span style="font: italic">&lt;'.gettype($var).'&gt;</span>';
    $list_prepend = '<ul style="margin-top: 0; margin-bottom: 0">';
    $list_append  = '</ul>';
    $item_prepend = '<li style="color: black; font: 7pt Verdana">';
    $item_append  = '</li>';

    switch (strtolower(gettype($var)))
    {
        case 'object':
            $output .= $var_prepend.$var.'{ }'.$var_append.'<br />';
            $output .= $list_prepend;
                $output .= $item_prepend._trace(get_class($var),         'Class',         $sort).$item_append;
                $output .= $item_prepend._trace(get_parent_class($var),  'Parent Class',       $sort).$item_append;
                $output .= $item_prepend._trace(get_object_vars($var),   'Properties',  $sort).$item_append;
                $output .= $item_prepend._trace(get_class_methods($var), 'Methods',     $sort).$item_append;
            $output .= $list_append;
            break;

        case 'array':
            if ($sort)
                ksort($var);
            $output .= $var_prepend.$var.'()'.$var_append.'<br />';
            $output .= $list_prepend;
            foreach ($var as $key => $val)
                $output .= $item_prepend._trace($val, $key, $sort).$item_append;
            $output .= $list_append;
            break;

        case 'boolean':
            $output .= $var_prepend.'[ '.($var ? 'true' : 'false').' ]'.$var_append;
            break;

        case 'string':
            $output .= $var_prepend.'"'.nl2br(htmlspecialchars(trim($var))).'"'.$var_append;
            break;

        case 'resource':
            $output .= $var_prepend.get_resource_type($var).' ('.$var.')'.$var_append;
            break;

        case 'integer':
        case 'double':
            $output .= $var_prepend.$var.$var_append;
            break;

        case 'null':
            $output .= $var_prepend.$var_append;
            break;

        case 'unkown type':
        default:
            $output .= $var_prepend.'? '.htmlspecialchars($var).' ?'.$var_append;
    }
    return($output);
}

?>