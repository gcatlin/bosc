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

/*
    http://cvs.php.net/cvs.php/pear/File/File.php

    http://developer.netscape.com/docs/manuals/communicator/jsref/oth1.htm#1011891

    file locking:
        http://www.pair.com/pair/current/insider/0702/coding.html
        http://perlmonks.thepen.com/7058.html
        http://www.perl.com/language/newdocs/pod/perlopentut.html#File_Locking


    TODO:
        read, readChar, readLine need work
        write, writeChar, writeLine need work
        need a buffer/readBuffer/readLength property
        stat, rewind, tell
*/

/**
 *
 */
class RandomAccessFile extends Object
{

    var $fp;
    var $lockFile;
    var $lockFp;

    /**
     * Creates a random access file stream to read from, and optionally to write
     * to, a file with the specified name.
     */
    function RandomAccessFile($filename, $mode='rb+')
    {
        //$op = (preg_match('/^rb?$/', $mode) ? LOCK_SH : LOCK_EX);
        //$this->lockFile =& $filename . '.lock';
        //@flock($this->lockFp = @fopen($this->lockFile, 'w+', 1), $op);
        $this->fp = @fopen($filename, $mode, TRUE);
    }

    /**
     * Closes this random access file stream and releases any system resources
     * associated with the stream.
     */
    function close()
    {
        @fclose($this->fp);
        //@flock($this->lockFp, LOCK_UN);
        //@fclose($this->lockFp);
        //@unlink($this->lockFile);
        //$this->lockFp = NULL;
        $this->fp = NULL;
    }

    /**
     * Returns the current offset in this file.
     */
    function offset()
    {
        return @ftell($this->fp);
    }

    /**
     * Returns the length of this file.
     */
    function length()
    {
        $offset = $this->getOffset();
        @fseek($this->fp, 0, SEEK_END);
        $length = $this->getOffset();
        $this->seek($offset);
        return $length;
    }

    /**
     * Reads a byte of data from this file.
     */
    function read($length=1)
    {
        return @fread($this->fp, $length);
    }

    /**
     * Reads the next line of text from this file.
     */
    function readLine()
    {
        return @fgets($this->fp);
    }

    /**
     * Resets the file stream.
     */
    function reset()
    {
        @rewind($this->fp);
    }

    /**
     * Sets the file-pointer offset, measured from the beginning of this file,
     * at which the next read or write occurs.
     */
    function seek($pos)
    {
        return @fseek($this->fp, $pos, SEEK_SET);
    }

    /**
     * Sets the length of this file.
     */
    function setLength($length)
    {
        $this->seek(min(max(0, $length), $this->getOffset() + 1));
        @ftruncate($this->fp, $length);
    }

    /**
     * Writes a string to the file as a sequence of characters.
     */
    function write($str='')
    {
        return @fwrite($this->fp, $str, strlen($str));
    }

}

?>