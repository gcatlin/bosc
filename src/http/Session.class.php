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
 * Provides a way to identify a user across more than one page request or visit
 * to a Web site and to store information about that user.
 *
 * Resources:
 *   o http://www.php.net/manual/en/ref.session.php
 *   o http://java.sun.com/j2ee/1.4/docs/api/index.html
 *   o http://msdn.microsoft.com/library/default.asp?url=/library/en-us/iissdk/iis/ref_vbom_seso.asp
 */
class Session extends Object
{

    /**
     * Invalidates this session and unbinds any objects bound to it.
     *
     * @return void
     */
    function destroy()
    {
        Session::initialize();
        $cookie = session_get_cookie_params();
        Response::setCookie(
            session_name(),
            '',
            0,
            (isset($cookie['path'])   ? $cookie['path']   : '/'),
            (isset($cookie['domain']) ? $cookie['domain'] : NULL),
            (isset($cookie['secure']) ? $cookie['secure'] : FALSE));
        session_unset();
        session_destroy();
    }

    /**
     *
     *
     * @return int
     */
    function getAge()
    {
        Session::initialize();
        return sprintf('%f', $_SESSION['__mtime'] - $_SESSION['__ctime']);
    }

    /**
     * Returns the UNIX timestamp of when this session was created.
     *
     * @return int
     */
    function getCreated()
    {
        Session::initialize();
        return $_SESSION['__ctime'];
    }

    /**
     * Returns a string containing the unique identifier assigned to this
     * session.
     *
     * @return string
     */
    function getId()
    {
        Session::initialize();
        return session_id();
    }

    /**
     * Returns the last time the client sent a request associated with this
     * session, as the number of milliseconds since midnight January 1, 1970
     * GMT, and marked by the time the container recieved the request.
     *
     * @return int
     */
    function getLastModified()
    {
        Session::initialize();
        return $_SESSION['__mtime'];
    }

    /**
     * Returns the maximum time interval, in seconds, that the servlet container
     * will keep this session open between client accesses.
     *
     * @return int
     */
    function getLifetime()
    {
        return ini_get('session.gc_maxlifetime');
    }

    /**
     * Get the current session name.
     *
     * @return string
     */
    function getName()
    {
        return session_name();
    }

    /**
     * Returns the data registered with the specified name in this session, or
     * NULL if no object is registered under the name.
     *
     * @param  string $name
     * @return mixed
     */
    function &getRegistered($name)
    {
        Session::initialize();
        if (isset($_SESSION[$name]))
            return $_SESSION[$name];
        return NULL;
    }

    /**
     * Returns an array of strings containing the names of all the objects
     * registered to this session.
     *
     * @return array
     */
    function getRegisteredNames()
    {
        Session::initialize();
        return array_keys($_SESSION);
    }

    /**
     *
     *
     * @param  string $handler
     * @param  mixed  $param
     * @return mixed
     */
    function getSaveHandler()
    {
        $save_handler = ini_get('session.save_handler');
        return ($save_handler !== FALSE ? $save_handler : NULL);
    }

    /**
     *
     *
     * @return void
     */
    function initialize()
    {
        static $initialized = FALSE;
        if (! $initialized)
        {
            @session_start();
            if (defined('SID') && SID && ! isset($_SESSION['__ctime']))
            {
                // Enable trans-id?
                ;
            }
            list($usec, $sec) = explode(' ', microtime());
            $mtime = sprintf('%f', (float) $usec + (float) $sec);
            $_SESSION['__ctime'] = (isset($_SESSION['__ctime']) ? $_SESSION['__ctime'] : $mtime);
            $_SESSION['__mtime'] = $mtime;
            $initialized = TRUE;
        }
    }

    /**
     *
     *
     * @return bool
     */
    function isCookied() // rename?
    {
        return (defined('SID') && ! SID);
    }

    /**
     *
     *
     * @return bool
     */
    function isNew()
    {
        Session::initialize();
        return (isset($_SESSION['__ctime']));
    }

    /**
     *
     *
     * @return bool
     */
    function isStarted()
    {
        return (defined('SID'));
    }

    /**
     * Generates a new session id and replace the old one with it.
     *
     * @return void
     */
    function regenerateId()
    {
        Session::initialize();
        session_regenerate_id();
    }

    /**
     * Registers an object to this session, using the name specified.
     *
     * @param  string $name
     * @param  mixed  $obj
     * @return void
     */
    function register($name, $obj)
    {
        Session::initialize();
        $old_setting = Session::getRegistered($name);
        $_SESSION[$name] = $obj;
        return $old_setting;
    }

    /**
     *
     *
     * @param  string $handler (files | user | dsn)
     * @param  mixed  $param
     * @return mixed
     */
    function setSaveHandler($handler, $param=NULL)
    {
        if ($handler == 'files')
        {
            ini_set('session.save_handler', 'files');
            if ($param !== NULL && file_exists($param) && is_writable($param))
                ini_set('session.save_path', $param);
        }
        elseif ($handler == 'user')
        {
            ini_set('session.save_handler', 'user');
            session_set_save_handler($param[0], $param[1], $param[2], $param[3], $param[4], $param[5]);
        }
        elseif ($handler == 'dsn')
        {
            ini_set('session.save_handler', 'user');
            $dsn = parse_url($param);
            $GLOBALS['ADODB_SESSION_DRIVER']  = $dsn['scheme'];
            $GLOBALS['ADODB_SESSION_USER']    = $dsn['user'];
            $GLOBALS['ADODB_SESSION_PWD']     = $dsn['pass'];
            $GLOBALS['ADODB_SESSION_CONNECT'] = $dsn['host'].(isset($dsn['port']) ? ':'.$dsn['port'] : '');
            $GLOBALS['ADODB_SESSION_DB']      = basename($dsn['path']);
            $GLOBALS['ADODB_SESSION_TBL']     = $dsn['fragment'];
            if ($dsn['query'] = 'crypt')
                include_once(BOSC_EXT.'/adodb/session/adodb-cryptsession.php');
            else
                include_once(BOSC_EXT.'/adodb/session/adodb-session.php');
        }
    }

    /**
     * Unregisters the object registered with the specified name from this
     * session.
     *
     * @param  string $name
     * @return void
     */
    function unregister($name=NULL)
    {
        Session::initialize();
        if (isset($_SESSION[$name]))
            unset($_SESSION[$name]);
    }

}

?>
