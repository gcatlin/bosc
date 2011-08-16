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
 * @subpackage io
 */
if (! defined('BOSC'))
    require_once(realpath(dirname(__FILE__).'/../bosc.inc.php'));

/**
 *
 */
class FileReader extends Object
{

    /**
     *
     */
    var $_file;

    /**
     *
     */
    var $_handle;

    /**
     *
     * @param mixed $file
     */
    function FileReader($file)
    {
        if (gettype($file) == 'object' && is_a($file, 'File'))
            $this->_file = $file->path();
        else
            $this->_file = $file;

        clearstatcache();
        if (is_readable($this->_file))
            $this->_handle = fopen($this->_file, 'rb', TRUE);
        else
            Error::raise(new FileNotReadableError($this->_file), __FILE__, __LINE__);

        //@flock($this->_handle, LOCK_SH);
    }

    /**
     * Close the file..
     */
    function close()
    {
        //@flock($this->_handle, LOCK_UN);
        return fclose($this->_handle);
    }

    /**
     * Reads the specified number of bytes of data from this file.
     */
    function read($len=1)
    {
        return fread($this->_handle, $len);
    }

    /**
     * Reads the entire file.
     */
    function readAll()
    {
        clearstatcache();
        return fread($this->_handle, filesize($this->_file));
    }

    /**
     * Reads the next line from this file.
     */
    function readLine()
    {
        return fgets($this->_handle);
    }

    /**
     * Resets the file pointer..
     */
    function reset()
    {
        rewind($this->_handle);
    }

    /**
     * Skips the specified number of characters.
     */
    function skip($n)
    {
        fseek($this->_handle, $n, SEEK_CUR);
    }

}

/**
 *
 */
class FileNotReadableError extends Error
{

    /**
     *
     */
    function FileNotReadableError($file)
    {
        $msg  = 'File not readable ("'.$file.'")';
        $code = E_USER_ERROR;
        parent::Error($msg, $code);
    }

}
?>
