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
 * @subpackage net
 */
if (! defined('BOSC'))
    require_once(realpath(dirname(__FILE__).'/../bosc.inc.php'));

/**
 * Resources:
 *   o http://www.ietf.org/rfc/rfc2396.txt
 *   o http://www.ietf.org/rfc/rfc1738.txt
 *   o http://www.ietf.org/rfc/rfc1630.txt
 *   o http://www.egenix.com/files/python/mxURL.html
 */
class Url extends Object
{

    /**
     *
     */
    var $_context;

    /**
     *
     */
    var $_fragment;

    /**
     *
     */
    var $_host;

    /**
     *
     */
    var $_password;

    /**
     *
     */
    var $_path;

    /**
     *
     */
    var $_port;

    /**
     *
     */
    var $_query;

    /**
     *
     */
    var $_scheme;

    /**
     *
     */
    var $_username;

    /**
     *
     * @param  string $url
     * @param  string $context
     * @return void
     */
    function Url($url, $context=NULL)
    {
        $url = @parse_url($url);
        $this->_context  = $context;
        $this->_fragment = (isset($url['fragment']) ? $url['fragment'] : NULL);
        $this->_host     = (isset($url['host']) ? $url['host'] : NULL);
        $this->_password = (isset($url['pass']) ? $url['pass'] : NULL);
        $this->_path     = (isset($url['path']) ? $url['path'] : NULL);
        $this->_port     = (isset($url['port']) ? (int) $url['port'] : NULL);
        $this->_query    = (isset($url['query']) ? $url['query'] : NULL);
        $this->_scheme   = (isset($url['scheme']) ? $url['scheme'] : NULL);
        $this->_username = (isset($url['user']) ? $url['user'] : NULL);
    }

    /**
     * Decodes the specified URL or, if decoding is not needed, returns the URL
     * unchanged.
     *
     * @return string
     */
    function decode()
    {
    }

    /**
     * Encodes the URL or, if encoding is not needed, returns the URL
     * unchanged.
     *
     * @param  string $url
     * @return string
     */
    function encode()
    {
        $url = new Url($this->_context ? $this->getAbsolute() : $this->__toString());

        $scheme = $url->getScheme();
        $user   = rawurlencode($url->getUsername());
        $pass   = rawurlencode($url->getPassword());
        $host   = $url->getHost();
        $port   = ($url->getPort() ? $url->getPort() : '');
        $authority =
            ($user ? $user.($pass ? ':'.$pass : '').'@' : '').
            ($host ? $host.($port ? ':'.$port : '') : '');

        $path = '';
        if ($url->getPath())
        {
            $path = explode('/', $url->getPath());
            foreach ($path as $i=>$segment)
            {
                if (strpos($segment, ';') !== FALSE)
                {
                    $segment = explode(';', $segment);
                    foreach ($segment as $j=>$part)
                        $segment[$j] = rawurlencode($part);
                    $path[$i] = implode(';', $segment);
                }
                else
                    $path[$i] = rawurlencode($segment);
            }
            $path = implode('/', $path);
            $path = str_replace('/%7E', '/~', $path);
        }

        $query = '';
        if ($url->getQuery())
        {
            $query = explode('&', $url->getQuery());
            foreach ($query as $i=>$param)
            {
                list($k, $v) = explode('=', $param);
                $query[$i] = urlencode($k).'='.urlencode($v);
            }
        }

        $fragment = rawurlencode($url->getFragment());

        return
            ($scheme    ? $scheme.':'.($authority ? '//' : '') : '').
            ($authority ? $authority : '').
            ($path      ? $path : '').
            ($query     ? '?'.implode('&', $query) : '').
            ($fragment  ? '#'.$fragment : '');
    }

    /**
     *
     *
     * @return string
     */
    function getAbsolute()
    {
        if ($this->isAbsolute())
            return $this->__toString();
        else
        {
            $url  = new Url($this->__toString());
            $base = new Url($this->_context);

            $url->setScheme($url->getScheme() ? $url->getScheme() : $base->getScheme());
            $url->setHost($url->getHost() ? $url->getHost() : $base->getHost());
            $url->setPort($url->getHost() && $url->getPort() ? $url->getPort() : $base->getPort()); //tweak

            $path = $url->getPath();
            if ($path{0} != '/')
                $path = strrev(strstr(strrev($base->getPath()), '/')).$path;

            // resolve path
            $path = explode('/', str_replace('//', '/', $path));
            for ($i=0; $i < count($path); $i++)
            {
                if ($path[$i] == '.' || ($path[$i] == '..' && $i == 1 && $path[0] == ''))
                    unset($path[$i--]);
                elseif ($path[$i] == '..' && ($i > 1 || ($i == 1 && $path[0] != '')))
                    unset($path[$i--], $path[$i--]);
                $path = array_values($path);
            }
            $path = implode('/', $path);
            //

            $url->setPath($path);
            return $url->__toString();
        }
    }

    /**
     *
     *
     * @return string
     */
    function getAuthority()
    {
        return
            ($this->_username ? $this->_username.($this->_password ? ':'.$this->_password : '').'@' : '').
            ($this->_host     ? $this->_host.($this->_port ? ':'.$this->_port : '') : '');
    }

    /**
     *
     *
     * @return string
     */
    function getContext()
    {
        return $this->_context;
    }

    /**
     *
     *
     * @return string
     */
    function getFragment()
    {
        return $this->_fragment;
    }

    /**
     *
     *
     * @return string
     */
    function getHost()
    {
        return $this->_host;
    }

    /**
     *
     *
     * @return string
     */
    function getPassword()
    {
        return $this->_password;
    }

    /**
     *
     *
     * @return string
     */
    function getPath()
    {
        return $this->_path;
    }

    /**
     *
     *
     * @return int
     */
    function getPort()
    {
        return $this->_port;
    }

    /**
     *
     *
     * @return string
     */
    function getQuery()
    {
        return $this->_query;
    }

    /**
     *
     *
     * @return string
     */
    function getScheme()
    {
        return $this->_scheme;
    }

    /**
     *
     *
     * @return string
     */
    function getUsername()
    {
        return $this->_username;
    }

    /**
     *
     *
     * @return bool
     */
    function isAbsolute()
    {
        $url = $this->__toString();
        $pos_colon = strpos($url, ':');
        $pos_slash = strpos($url, '/');
        return ($pos_colon !== FALSE && ($pos_slash !== FALSE ? $pos_colon < $pos_slash : TRUE));
    }

    /**
     *
     *
     * @param  string $authority
     * @return string
     */
    function setAuthority($authority)
    {
        if (strpos($authority, '@') !== FALSE)
        {
            list($user_pass, $host_port) = explode('@', $authority);
            list($user, $pass) = explode(':', $user_pass);
            list($host, $port) = explode(':', $host_port);
            $this->_username = $user;
            $this->_password = $pass;
        }
        else
            list($host, $port) = explode(':', $authority);
        $this->_host = $host;
        $this->_port = $port;
    }

    /**
     *
     *
     * @param  string $context
     * @return string
     */
    function setContext($context)
    {
        $this->_context = $context;
    }

    /**
     *
     *
     * @param  string $fragment
     * @return string
     */
    function setFragment($fragment)
    {
        $this->_fragment = $fragment;
    }

    /**
     *
     *
     * @param  string $host
     * @return string
     */
    function setHost($host)
    {
        $this->_host = strtolower($host);
    }

    /**
     *
     *
     * @param  string $password
     * @return string
     */
    function setPassword($password)
    {
        $this->_password = $password;
    }

    /**
     *
     *
     * @param  string $path
     * @return string
     */
    function setPath($path)
    {
        $this->_path = $path;
    }

    /**
     *
     *
     * @param  int $port
     * @return int
     */
    function setPort($port)
    {
        $this->_port = (int) $port;
    }

    /**
     *
     *
     * @param  string $query
     * @param  bool   $append
     * @return string
     */
    function setQuery($query, $append=FALSE)
    {
        if ($append)
        {
            $old_query = explode('&', $this->_query);
            $new_query = explode('&', $query);
            $query = implode('&', array_unique(array_merge($new_query, $old_query)));
        }
        $this->_query = $query;
    }

    /**
     *
     *
     * @param  string $scheme
     * @return string
     */
    function setScheme($scheme)
    {
        $this->_scheme = strtolower($scheme);
    }

    /**
     *
     *
     * @param  string $username
     * @return string
     */
    function setUsername($username)
    {
        $this->_username = $username;
    }

    /**
     *
     *
     * @return string
     */
    function __toString()
    {
        $authority = $this->getAuthority();
        return
            ($this->_scheme   ? $this->_scheme.':'.($authority ? '//' : '') : '').
            ($authority       ? $authority : '').
            ($this->_path     ? $this->_path : '').
            ($this->_query    ? '?'.$this->_query : '').
            ($this->_fragment ? '#'.$this->_fragment : '');
    }

}

?>
