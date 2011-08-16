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
 * @subpackage core
 */
if (! defined('BOSC'))
    require_once(realpath(dirname(__FILE__).'/../bosc.inc.php'));

$__ERROR_TYPES = array(
    E_ERROR        => 'PHP Fatal Error',
    E_WARNING      => 'PHP Warning',
    E_NOTICE       => 'PHP Notice',
    E_USER_ERROR   => 'User Fatal Error',
    E_USER_WARNING => 'User Warning',
    E_USER_NOTICE  => 'User Notice',
    E_STRICT       => 'PHP Strict Standards');

/**
 *
 *
 * Resources:
 *   o http://php.net/manual/en/ref.errorfunc.php
 *   o http://php.net/manual/en/function.error-log.php
 *   o http://php.net/manual/en/function.error-reporting.php
 *   o http://php.net/manual/en/function.set-error-handler.php
 *   o http://php.net/manual/en/function.trigger-error.php
 *   o http://msdn.microsoft.com/library/default.asp?url=/library/en-us/iissdk/iis/ref_vbom_aseo.asp
 */
class Error extends Object
{

    /**
     *
     */
    var $_code;

    /**
     *
     */
    var $_file;

    /**
     *
     */
    var $_line;

    /**
     * $message is limited to 1024 characters in length. Any additional
     * characters beyond 1024 will be truncated.
     */
    var $_message;

    /**
     *
     */
    var $_type;

    /**
     *
     * @param  string $message
     * @param  int    $code
     * @param  string $file
     * @param  string $line
     * @param  string $context
     * @return void
     */
    function Error($message='Unspecified error', $code=E_USER_ERROR, $file='Unknown File', $line=0)
    {
        global $__ERROR_TYPES;

        $this->_code = $code;
        $this->_file = $file;
        $this->_line = $line;
        $this->_message = $message;
        $this->_type = (isset($__ERROR_TYPES[$code]) ? $__ERROR_TYPES[$code] : 'Unknown Error');
    }

    /**
     *
     * @return int
     */
    function getCode()
    {
        return $this->_code;
    }

    /**
     *
     * @return string
     */
    function getFile()
    {
        return $this->_file;
    }

    /**
     *
     * @return int
     */
    function getLine()
    {
        return $this->_line;
    }

    /**
     *
     * @return string
     */
    function getMessage()
    {
        return $this->_message;
    }

    /**
     *
     * @return string
     */
    function getType()
    {
        return $this->_type;
    }

    /**
     *
     * @return bool
     */
    function isFatal()
    {
        return ($this->_code & (E_ERROR | E_USER_ERROR));
    }

    /**
     *
     * @return bool
     */
    function isNotice()
    {
        return ($this->_code & (E_NOTICE | E_USER_NOTICE | E_STRICT));
    }

    /**
     *
     * @return bool
     */
    function isWarning()
    {
        return ($this->_code & (E_WARNING | E_USER_WARNING));
    }

    /**
     *
     * @param  Error $__error
     * @return void
     * @static
     */
    function raise(&$error, $file=NULL, $line=NULL)
    {
        if ($file)
            $error->setFile($file);
        if ($line)
            $error->setLine($line);
        trigger_error('__Error::raise:'.@serialize($error), $error->getCode());
    }

    /**
     *
     * @return string
     */
    function setFile($file)
    {
        $this->_file = $file;
    }

    /**
     *
     * @return int
     */
    function setLine($line)
    {
        $this->_line = (int) $line;
    }

}

/**
 *
 */
class ErrorHandler extends Object
{

    /**
     *
     */
    var $_errors = array();

    /**
     *
     * @return void
     */
    function ErrorHandler()
    {
    }

    /**
     * Returns an array of Error objects.
     *
     * @param  Error $err
     * @return void
     */
    function addError(&$error)
    {
        $this->_errors[] =& $error;
    }

    /**
     * Override this method in subclasses of ErrorHandler to customize error
     * handling.
     *
     * @param  Error $error
     * @return void
     */
    function doHandleError(&$error)
    {
        // Error Logging
        if (ErrorHandler::isLogging())
        {
            $message =
                $error->getType().':  '.
                $error->getMessage().' ('.
                $error->getFile().' on line '.
                $error->getLine().')';
            ErrorHandler::logError($message);
        }

        // Error Displaying
        if (ErrorHandler::isDisplaying())
        {
            if ($error->isFatal())
                Response::clear();

            echo
                '<br />'."\n".
                '<b>'.$error->getType().'</b>:  '.$error->getMessage().' in '.
                '<b>'.$error->getFile().'</b> on line '.
                '<b>'.$error->getLine().'</b>'.
                '<br />'."\n";

            if ($error->isFatal())
                Response::end();
        }
    }

    /**
     * Returns an array of Error objects.
     *
     * @return array
     */
    function getErrors()
    {
        return $this->_errors;
    }

    /**
     *
     *
     * @return string
     * @static
     */
    function getLogFile()
    {
        $error_log = ini_get('error_log');
        return ($error_log !== FALSE ? $error_log : NULL);
    }

    /**
     *
     *
     * @return int
     * @static
     */
    function getReportingLevel()
    {
        return ini_get('error_reporting');
    }

    /**
     *
     *
     * @param  int    $code
     * @param  string $message
     * @param  string $file
     * @param  int    $line
     * @param  string $context
     * @return void
     */
    function handleError($code, $message, $file=NULL, $line=NULL, $context=NULL)
    {
        if ($code == E_STRICT)
            return;

        if (strpos($message, '__Error::raise:') === 0)
            $error = @unserialize(substr($message, 15));
        else
            $error = new Error($message, $code, $file, $line);
        $this->addError($error);

        if ($code & error_reporting())
            $this->doHandleError($error);
    }

    /**
     *
     *
     * @return bool
     * @static
     */
    function isDisplaying()
    {
        return (bool) ini_get('display_errors');
    }

    /**
     *
     *
     * @return bool
     * @static
     */
    function isLogging()
    {
        return (bool) ini_get('log_errors');
    }

    /**
     *
     *
     * @return void
     * @static
     */
    function logError($message, $message_type=0, $destination=NULL, $extra_headers=NULL)
    {
        error_log($message, $message_type, $destination, $extra_headers);
    }

    /**
     *
     *
     * @param  ErrorHandler $handler
     * @return void
     * @static
     */
    function setHandler()
    {
        switch (func_num_args())
        {
            case 1:
                $handler = func_get_arg(0);
                break;
            case 2:
                $arg1 = func_get_arg(0);
                $arg2 = func_get_arg(1);
                $handler = array(&$arg1, $arg2);
                break;
            default:
                $handler = '';
        }
        set_error_handler($handler);
    }

    /**
     *
     *
     * @param  bool $display_errors
     * @return bool
     * @static
     */
    function setDisplayErrors($display_errors)
    {
        ini_set('display_errors', (bool) $display_errors);
    }

    /**
     *
     *
     * @param  bool $log_errors
     * @return void
     * @static
     */
    function setLogErrors($log_errors)
    {
        ini_set('log_errors', (bool) $log_errors);
    }

    /**
     *
     *
     * @param  string $file
     * @param  int    $max_len
     * @return void
     * @static
     */
    function setLogFile($file, $max_len=NULL)
    {
        if ($max_len !== NULL)
            ini_set('log_errors_max_len', (int) $max_len);
        ini_set('error_log', $file);
    }

    /**
     *
     *
     * @param  int  $level
     * @return void
     * @static
     */
    function setReportingLevel($level)
    {
        ini_set('error_reporting', (int) $level);
    }

}

?>
