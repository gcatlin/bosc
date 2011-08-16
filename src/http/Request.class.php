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
 * Defines an object to provide client request information about an HTTP
 * request.
 *
 * Resources:
 *   o http://hoohoo.ncsa.uiuc.edu/cgi/env.html
 *   o http://java.sun.com/j2ee/1.4/docs/api/index.html
 *   o http://msdn.microsoft.com/library/default.asp?url=/library/en-us/iissdk/iis/ref_vbom_reqo.asp
 *   o http://msdn.microsoft.com/library/default.asp?url=/library/en-us/iissdk/iis/ref_vbom_sero.asp
 *   o http://msdn.microsoft.com/library/default.asp?url=/library/en-us/cpref/html/frlrfSystemWebHttpRequestClassTopic.asp
 *   o http://msdn.microsoft.com/library/default.asp?url=/library/en-us/cpref/html/frlrfsystemwebhttpcookieclasstopic.asp
 *   o http://msdn.microsoft.com/library/default.asp?url=/library/en-us/cpref/html/frlrfsystemwebhttpfilecollectionclasstopic.asp
 */
class Request extends Object
{

    /**
     * Returns the Internet Protocol (IP) address of the server that received
     * the request.
     *
     * @return string
     */
    function getAddress()
    {
        return (isset($_SERVER['SEVER_ADDR']) ? $_SERVER['SEVER_ADDR'] : gethostbyname($_SERVER['SERVER_NAME']));
    }

    /**
     * Returns the Internet Protocol (IP) address of the client that sent the
     * request.
     *
     * @return string
     */
    function getClientAddress()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Returns the fully qualified name of the client that sent the request.
     * If no host name is set, the host address is returned.
     *
     * @return string
     */
    function getClientHost()
    {
        return (isset($_SERVER['REMOTE_HOST']) ? $_SERVER['REMOTE_HOST'] : $_SERVER['REMOTE_ADDR']);
    }

    /**
     * Returns the port number of the client that sent the request, or NULL if
     * the port is  not known
     *
     * @return string
     */
    function getClientPort()
    {
        return (isset($_SERVER['REMOTE_PORT']) ? $_SERVER['REMOTE_PORT'] : NULL);
    }

    /**
     * Returns the value of the named cookie sent with this request, or NULL
     * if the named cookie does not exist.
     *
     * @param  string $name
     * @return string
     */
    function getCookie($name='')
    {
        return (isset($_COOKIE[$name]) ? $_COOKIE[$name] : NULL);
        /*
        if (isset($_COOKIE[$name]))
        {
            include_once(BOSC.'/core/_Cookie.class.php');
            return new UploadedFile($name);
        }
        return NULL;
        */
    }

    /**
     * Returns an array containing the names of all the cookies the client
     * sent with this request, or NULL if no cookies were sent.
     *
     * @return array
     */
    function getCookieNames()
    {
        return ($_COOKIE ? array_keys($_COOKIE) : NULL);
    }

    /**
     * Returns the length, in bytes, of the request body and made available by
     * the input stream, or NULL if the length is not known.
     *
     * @return string
     */
    function getContentLength()
    {
        return (isset($_SERVER['CONTENT_LENGTH']) ? $_SERVER['CONTENT_LENGTH'] : NULL);
    }

    /**
     * Returns the MIME type of the body of the request, or NULL if the type is
     * not known.
     *
     * @return string
     */
    function getContentType()
    {
        return (isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : NULL);
    }

    /**
     *
     *
     * @param  string $name
     * @return mixed
     */
    function getFile($name)
    {
        // single file, multifile
        include_once(BOSC.'/http/UploadFile.class.php');
        $file = new UploadFile($name);
        return $file;
    }

    /**
     * Returns an array containing the names of all the files the client sent
     * with this request, or NULL if no files were sent.
     *
     * @return array
     */
    function getFileNames()
    {
        return ($_FILES ? array_keys($_FILES) : NULL);
    }

    /**
     *
     *
     * @return array
     */
    function getForm()
    {
        return $_POST;
    }

    /**
     *
     *
     * @return string
     */
    function getFormParameter($name)
    {
        return (isset($_POST[$name]) ? $_POST[$name] : NULL);
    }

    /**
     *
     *
     * @return array
     */
    function getFormParameterNames()
    {
        return array_keys($_POST);
    }

    /**
     * Returns the value of the specified request header as a string. If the
     * request did not include a header of the specified name, this method
     * returns NULL. The header name is case insensitive. This method can be
     * used with any request header.
     *
     * @param  string $name
     * @return string
     */
    function getHeader($name)
    {
        $header = 'HTTP_'.strtoupper(str_replace('-', '_', $name));
        return (isset($_SERVER[$header]) ? $_SERVER[$header] : NULL);
    }

    /**
     * Returns an array of all the header names this request contains. If the
     * request contains no headers, this method returns an empty array.
     *
     * @return array
     */
    function getHeaderNames()
    {
        $headers = array_values(preg_grep('/^HTTP/', array_keys($_SERVER)));
        sort($headers);
        return preg_replace('/HTTP_(.*)/e', 'strtolower(str_replace("_", "-", "\1"))', $headers);
    }

    /**
     * Returns the host name of the server that received the request.
     *
     * @return string
     */
    function getHost()
    {
        return $_SERVER['SERVER_NAME'];
    }

    /**
     * Returns the preferred Locale that the client will accept content in,
     * based on the Accept-Language header.
     *
     * @return string
     */
    function getLocale()
    {
        return $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    }

    /**
     * Returns the name of the HTTP method with which this request was made,
     * either "GET" or "POST".
     *
     * @return string
     */
    function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Returns the value of a request parameter as a string, or NULL if the
     * parameter does not exist. Request parameters are extra information sent
     * with the request.
     *
     * @param  string $name
     * @return string
     */
    function getParameter($name='')
    {
        return (isset($_REQUEST[$name]) ? $_REQUEST[$name] : NULL);
    }

    /**
     * Returns an array of strings containing the names of the parameters
     * contained in this request. If the request has no parameters, the method
     * returns NULL.
     *
     * @return array
     */
    function getParameterNames()
    {
        return ($_REQUEST ? array_keys($_REQUEST) : NULL);
    }

    /**
     * Returns any path information for this request after the domain name but
     * before the query string.
     *
     * @return string
     */
    function getPath()
    {
        return $_SERVER['SCRIPT_NAME'];
    }

    /**
     * Returns any extra path information for this request after the script path but
     * before the query string. If this information is not set, the method
     * returns NULL.
     *
     * For example, if the request URL is:
     *   http://www.example.com/path/to/script.php/extra/info
     *
     * This method will return:
     *   /extra/info/
     *
     * @return string
     */
    function getPathInfo()
    {
        return (isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : NULL);
    }

    /**
     * Returns any path information after the domain name but before the
     * query string, and translates it to a real path.
     *
     * @return string
     */
    function getPathTranslated()
    {
        return (isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : array_shift(get_included_files()));
    }

    /**
     * Returns the port number on which this request was received, of NULL if
     * it is not known.
     *
     * @return int
     */
    function getPort()
    {
        return $_SERVER['SERVER_PORT'];
    }

    /**
     * Returns the name and version of the protocol the request uses in the form
     * protocol/majorVersion.minorVersion, for example, HTTP/1.1.
     *
     * @return string
     */
    function getProtocol()
    {
        return $_SERVER['SERVER_PROTOCOL'];
    }

    /**
     *
     *
     * @return array
     */
    function getQuery()
    {
        return $_GET;
    }

    /**
     *
     *
     * @return string
     */
    function getQueryParameter($name)
    {
        return (isset($_GET[$name]) ? $_GET[$name] : NULL);
    }

    /**
     *
     *
     * @return array
     */
    function getQueryParameterNames()
    {
        return array_keys($_GET);
    }

    /**
     * Returns the query string that is contained in the request URL after the
     * path. This method returns NULL if the URL does not have a query string.
     *
     * @return string
     */
    function getQueryString()
    {
        return (isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : NULL);
    }

    /**
     * Returns the address of the page (if any) which referred the user agent
     * to the current page. This is set by the user agent. Not all user agents
     * will set this, and some provide the ability to modify HTTP_REFERER as a
     * feature. In short, it cannot really be trusted.
     *
     * @return string
     */
    function getReferer()
    {
        return (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL);
    }

    /**
     * Returns the name of the scheme used to make this request, either
     * "http", or "https".
     *
     * @return string
     */
    function getScheme()
    {
        return (! empty($_SERVER['HTTPS']) ? 'https' : 'http');
    }

    /**
     * Returns the server identification string of the web server handling this
     * request.
     *
     * @return string
     */
    function getServer()
    {
        return $_SERVER['SERVER_SOFTWARE'];
    }

    /**
     * Returns the session ID specified by the client. This may not be the same
     * as the ID of the actual session in use. For example, if the request
     * specified an old (expired) session ID and the server has started a new
     * session, this method gets a new session with a new ID. If the request did
     * not specify a session ID, this method returns NULL.
     *
     * @return string
     */
    function getSessionId()
    {
        $sess = Session::getName();
        return (isset($_REQUEST[$sess]) ? $_REQUEST[$sess] : NULL);
    }

    /**
     * Reconstructs the URL the client used to make the request. The returned
     * URL contains a protocol, server name, port number, and server path, but
     * it does not include query string parameters.
     *
     * @return string
     */
    function getUrl()
    {
        $scheme = (! empty($_SERVER['HTTPS']) ? 'https' : 'http');
        $port = $_SERVER['SERVER_PORT'];
        return
            $scheme.'://'.
            $_SERVER['SERVER_NAME'].
            (($scheme == 'http' && $port == 80) || ($scheme == 'https' && $port == 443) ? '' : ':'.$port).
            $_SERVER['SCRIPT_NAME'];
    }

    /**
     * Returns the name of the user agent used to make this request.
     *
     * @return string
     */
    function getUserAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    /**
     * Returns a boolean indicating whether this request was made using the
     * GET method.
     *
     * @return bool
     */
    function isGet()
    {
        return ($_SERVER['REQUEST_METHOD'] == 'GET');
    }

    /**
     * Returns a boolean indicating whether this request was made using the
     * POST method.
     *
     * @return bool
     */
    function isPost()
    {
        return ($_SERVER['REQUEST_METHOD'] == 'POST');
    }

    /**
     * Returns a boolean indicating whether this request was made using HTTPS.
     *
     * @return bool
     */
    function isSecure()
    {
        return (! empty($_SERVER['HTTPS']));
    }

}

/**
 *
 */
if (get_magic_quotes_gpc())
{
    function __undo_magic_quotes_gpc($var)
    {
        return (is_array($var) ? array_map('__undo_magic_quotes_gpc', $var) : stripslashes($var));
    }

    $_GET     = array_map('__undo_magic_quotes_gpc', $_GET);
    $_POST    = array_map('__undo_magic_quotes_gpc', $_POST);
    $_COOKIE  = array_map('__undo_magic_quotes_gpc', $_COOKIE);
    $_REQUEST = array_map('__undo_magic_quotes_gpc', $_REQUEST);
}

?>