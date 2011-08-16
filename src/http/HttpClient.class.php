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
    require_once(realpath(dirname(__path__).'/../bosc.inc.php'));

/**
 *
 * Resources:
 *   o http://curl.haxx.se/docs/manpage.html
 *   o http://www.gnu.org/software/wget/manual/wget.html
 *   o http://www.ietf.org/rfc/rfc1945.txt
 *
 *  history, forward, backward, cacheing, cookies
 */
class HttpClient extends Object
{

    var $_ua;
    var $_history = array();

    function HttpClient()
    {
        include_once(BOSC_EXT.'/PEAR/HTTP/Request.php');
        $this->_ua = new HTTP_Request();
    }

    function getResponseBody()
    {
        return $this->_ua->getResponseBody();
    }

    function getResponseHeaders()
    {
        return $this->_ua->getResponseHeader();
    }

    function getResponseCookie($name='PHPSESSID')
    {
        $cookies = $this->_ua->getResponseCookies();
        foreach ($cookies as $i => $cookie)
            if ($cookie['name'] == $name)
                return $cookie['value'];
        return NULL;
    }

    function getResponseCookies()
    {
        $cookies = $this->_ua->getResponseCookies();
        return (is_array($cookies) ? $cookies : array());
    }

    function sendRequest($method, $url, $params=NULL)
    {
        $ua =& $this->_ua;
        $ua->setMethod($method);
        $ua->setURL($url);

        $params = (is_array($params) ? $params : array());
        $m = ($method == 'POST' ? 'addPostData' : 'addQueryString');
        foreach ($params as $k => $v)
            $ua->$m($k, $v);

        if (PEAR::isError($ua->sendRequest()))
            return FALSE;

        $this->_history[] = $url;
        return TRUE;
    }

}

?>
