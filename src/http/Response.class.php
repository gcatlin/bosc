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
 * @subpackage http
 */
if (! defined('BOSC'))
    require_once(realpath(dirname(__FILE__).'/../bosc.inc.php'));

/**
 *
 *
 * Resources:
 *   o http://www.w3.org/Protocols/rfc2616/rfc2616
 *   o http://java.sun.com/j2ee/1.4/docs/api/index.html
 *   o http://msdn.microsoft.com/library/default.asp?url=/library/en-us/iissdk/iis/ref_vbom_reso.asp
 *   o http://msdn.microsoft.com/library/default.asp?url=/library/en-us/cpref/html/frlrfsystemwebhttpresponseclasstopic.asp
 */
class Response extends Object
{

    /**
     * Adds a response header with the given name and value.
     *
     * @param  string $name
     * @param  string $value
     * @param  bool   $replace
     * @return void
     */
    function addHeader($name, $value, $replace=FALSE)
    {
        header($name.': '.$value, $replace);
    }

    /**
     * Enables page output buffering.
     *
     * @param  bool $enable
     * @return void
     */
    function buffer($enable=TRUE)
    {
        if ($enable)
            ob_start();
        else
            @ob_flush();
    }

    /**
     * Returns contents of buffered output and clears all buffers.
     *
     * @return string
     */
    function clear()
    {
        while (1 < ob_get_level())
            @ob_end_flush();
        return ob_get_clean();
    }

    /**
     * Encodes the specified URL and includes the session ID in it (if
     * appropriate).
     *
     * @param  string $url
     * @return string
     */
    function encodeUrl($url)
    {
        include_once(BOSC.'/net/Url.class.php');
        $abs_url = new Url($url, Request::getUrl());
        /*//
        $schemes_match = ($abs_url->getScheme() == Request::getScheme());
        $hosts_match = ($abs_url->getHost() == Request::getHost());
        $ports_match = ($abs_url->getPort() == Request::getPort());
        if (! Session::isCookied() && $schemes_match && $hosts_match && $ports_match)
            $abs_url->setQuery(SID, TRUE);
        //*/
        return $abs_url->encode();
    }

    /**
     * Terminates the current script and optionally outputs a message..
     *
     * @param  string $message
     * @return void
     */
    function end($message='')
    {
        exit($message);
    }

    /**
     * Sends buffered output immediately.
     *
     * @return void
     */
    function flush()
    {
        return ob_get_flush();
    }

    /**
     *
     *
     * @param  string $file
     * @param  string $callback_function
     * @return void
     */
    function forward($file, $callback_function=NULL)
    {
        extract($GLOBALS);
        include($file);
        if ($callback_function)
            call_user_func($callback_function);
        Response::end();
    }

    /**
     * Returns buffered output.
     *
     * @return void
     */
    function getBuffer()
    {
        return @ob_get_contents();
    }

    /**
     * Sends a temporary redirect response to the client using the specified
     * redirect location URL.
     *
     * If no scheme,
     *
     * @param  string $location
     * @return void
     */
    function redirect($url)
    {
        header('Location: '.Response::encodeUrl($url), TRUE);
        Response::end();
    }

    /**
     * Sets the HTTP Content-Length header in the response.
     *
     * @param  int  $len
     * @return void
     */
    function setContentLength($len)
    {
        header('Content-Length: '.(int) $len, TRUE);
    }

    /**
     * Sets the content type of the response being sent to the client.
     *
     * @param  string $type
     * @return void
     */
    function setContentType($type)
    {
        header('Content-Type: '.$type, TRUE);
    }

    /**
     * Adds the specified cookie to the response. This method can be called
     * multiple times to set more than one cookie.
     *
     * @param  string $name
     * @param  string $value
     * @param  int    $expires
     * @param  string $path
     * @param  string $domain
     * @param  bool   $secure
     * @return void
     */
    function setCookie($name, $value=NULL, $expires=NULL, $path=NULL, $domain=NULL, $secure=FALSE)
    {
        setcookie($name, $value, $expires, $path, $domain, $secure);
    }

    /**
     * Specifies the length of time before a page cached on a browser expires.
     *
     * @param  int  $minutes
     * @return void
     */
    function setExpiration($minutes=0)
    {
        $seconds = (int) $minutes / 60;
        header('Expires: '.date('r', time() - $seconds));
        if (! $seconds)
        {
            header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
            header('Cache-Control: no-store, no-cache, must-revalidate');
            header('Cache-Control: post-check=0, pre-check=0', FALSE);
            header('Pragma: no-cache');
        }
    }

    /**
     * Sets the status code for this response.
     *
     * @param  int    $status_code
     * @param  string $message
     * @return void
     */
    function setStatus($status_code, $message)
    {
        header('Status: '.$status_code.' '.$message, TRUE);
    }

    /**
     * Writes the supplied text to the output device.
     *
     * @param  string $text
     * @return void
     */
    function write($text)
    {
        echo $text;
    }

}

?>
