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
 * An abstract representation of file and directory pathnames.
 *
 * User interfaces and operating systems use system-dependent pathname strings
 * to name files and directories. This class presents an abstract,
 * system-independent view of hierarchical pathnames. An abstract pathname has
 * two components:
 *
 *   1. An optional system-dependent prefix string, such as a disk-drive
 *      specifier, "/" for the UNIX root directory, or "\\" for a Microsoft
 *      Windows UNC pathname, and
 *
 *   2. A sequence of zero or more string names.
 *
 * Each name in an abstract pathname except for the last denotes a directory;
 * the last name may denote either a directory or a file. The empty abstract
 * pathname has no prefix and an empty name sequence.
 *
 * The conversion of a pathname string to or from an abstract pathname is
 * inherently system-dependent. When an abstract pathname is converted into a
 * pathname string, each name is separated from the next by a single copy of the
 * default separator character. The default name-separator character is defined
 * by the system property file.separator, and is made available in the public
 * static fields separator and separatorChar of this class. When a pathname
 * string is converted into an abstract pathname, the names within it may be
 * separated by the default name-separator character or by any other
 * name-separator character that is supported by the underlying system.
 *
 * A pathname, whether abstract or in string form, may be either absolute or
 * relative. An absolute pathname is complete in that no other information is
 * required in order to locate the file that it denotes. A relative pathname, in
 * contrast, must be interpreted in terms of information taken from some other
 * pathname. By default the classes in the java.io package always resolve
 * relative pathnames against the current user directory. This directory is
 * named by the system property user.dir, and is typically the directory in
 * which the Java virtual machine was invoked.
 *
 * The prefix concept is used to handle root directories on UNIX platforms, and
 * drive specifiers, root directories and UNC pathnames on Microsoft Windows
 * platforms, as follows:
 *
 *   o For UNIX platforms, the prefix of an absolute pathname is always "/".
 *     Relative pathnames have no prefix. The abstract pathname denoting the
 *     root directory has the prefix "/" and an empty name sequence.
 *
 *   o For Microsoft Windows platforms, the prefix of a pathname that contains a
 *     drive specifier consists of the drive letter followed by ":" and possibly
 *     followed by "\" if the pathname is absolute. The prefix of a UNC pathname
 *     is "\\"; the hostname and the share name are the first two names in the
 *     name sequence. A relative pathname that does not specify a drive has no
 *     prefix.
 *
 * Instances of the File class are immutable; that is, once created, the
 * abstract pathname represented by a File object will never change.
 */
class File extends Object
{

    /**
     *
     */
    var $_path;

    /**
     * Creates a new File instance by converting the given pathname string into
     * an abstract pathname. If the given string is the empty string, then the
     * result is the empty abstract pathname.
     */
    function File($path)
    {
        $path = str_replace('/', DIRECTORY_SEPARATOR, str_replace('\\', '/', $path));
        $this->_path = (substr($path, -1) == DIRECTORY_SEPARATOR ? substr($path, 0, -1) : $path);
    }

    /**
     * Copies the file denoted by this abstract pathname to the specified
     * destination.
     *
     * @return bool
     */
    function copyTo($dest)
    {
        // similar to renameTo()
        //   http://aidan.dotgeek.org/lib/?file=function.copyr.php
        $file = new File($dest);
        $dst = $file->getPath();
        $dir = dirname($dst);
        $src = $this->_path;
        clearstatcache();
        $dw = (file_exists($dir) && is_writable($dir));
        return (file_exists($src) && ! file_exists($dst) && $dw ? @copy($src, $dst) : FALSE);
    }

    /**
     * Atomically creates a new, empty file named by this abstract pathname if
     * and only if a file with this name does not yet exist.
     *
     * @return bool
     */
    function createNewFile()
    {
        $fh = @fopen($this->_path, 'x');
        @fclose($fh);
        return (bool) $fh;
    }

    /**
     * Creates a new empty file in the specified directory, using the given
     * prefix to generate its name.  If the directory does not exist, or is not
     * specified, it may may generate a file in the system's temporary
     * directory, and return its name.
     *
     * @return string
     */
    function createTempFile($prefix='', $dir='/tmp')
    {
        return new File(tempnam($dir, $prefix));
    }

    /**
     * Deletes the file or directory denoted by this abstract pathname. If this
     * pathname denotes a directory, then the directory must be empty in order
     * to be deleted.
     *
     * @return bool
     */
    function delete($recursive=FALSE)
    {
        $p = $this->_path;
        clearstatcache();
        if (file_exists($p))
        {
            if (is_file($p))
                return @unlink($p);
            if (is_dir($p))
            {
                if ($recursive)
                    // see:
                    //   http://aidan.dotgeek.org/lib/?file=function.rmdirr.php
                    return @rmdir($p);
                else
                    return @rmdir($p);
            }
        }
        return FALSE;
    }

    /**
     * Returns the absolute pathname string of this abstract pathname.
     *
     * @return string
     */
    function getAbsolutePath()
    {
        clearstatcache();
        return (file_exists($this->_path) ? realpath($this->_path) : $this->_path);
    }

    /**
     * Reads the entire file.
     */
    function getContents()
    {
        ob_start();
        readfile($this->_file);
        return ob_get_clean();
    }

    /**
     *
     *
     * @return string
     */
    function getExtension()
    {
        $pathinfo = pathinfo($this->_path);
        return (isset($pathinfo['extension']) ? $pathinfo['extension'] : '');
    }

    /**
     * Returns the time that the file denoted by this abstract pathname was last
     * modified.
     *
     * @return bool
     */
    function getLastModified()
    {
        clearstatcache();
        return (file_exists($this->_path) ? @filemtime($this->_path) : 0);
    }

    /**
     * Returns the name of the file or directory denoted by this abstract
     * pathname. This is just the last name in the pathname's name sequence. If
     * the pathname's name sequence is empty, then the empty string is returned.
     *
     * If the filename ends in suffix this will also be cut off.
     *
     * @param  string $suffix
     * @return string
     */
    function getName($suffix=NULL)
    {
        return basename($this->_path, $suffix);
    }

    /**
     * Returns the pathname string of this abstract pathname's parent, or NULL
     * if this pathname does not name a parent directory.
     *
     * The parent of an abstract pathname consists of the pathname's prefix, if
     * any, and each name in the pathname's name sequence except for the last.
     * If the name sequence is empty then the pathname does not name a parent
     * directory.
     *
     * @return string
     */
    function getParentPath()
    {
        return dirname($this->_path);
    }

    /**
     * Returns the abstract pathname.
     *
     * @return string
     */
    function getPath()
    {
        return $this->_path;
    }

    /**
     * Returns the size of the file denoted by this abstract pathname.
     *
     * @return bool
     */
    function getSize($units=NULL)
    {
        // directory size (recursize?)
        //   http://aidan.dotgeek.org/lib/?file=function.filesize_h.php

        clearstatcache();
        if (! file_exists($this->_path))
            return NULL;

        $bytes = @filesize($this->_path);
        $symbols = array('Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        if (in_array($units, $symbols))
        {
            $exp = 0;
            while (pow(1024, $exp) <= $bytes)
                $exp++;
            $exp--;
            $fsize = sprintf('%.3f', $bytes / pow(1024, $exp));
            $digits = strlen((int) $fsize);
            if ($digits == 3)
                $fsize = sprintf('%.0f', $fsize);
            elseif ($digits == 2)
                $fsize = sprintf('%.1f', $fsize);
            elseif ($digits == 1)
                $fsize = sprintf('%.2f', $fsize);
            return $fsize.' '.$symbols[$exp];
        }
        return $bytes;
    }

    /**
     * Tests whether the file denoted by this abstract pathname exists.
     *
     * @return bool
     */
    function isCreated()
    {
        clearstatcache();
        return file_exists($this->_path);
    }

    /**
     * Tests whether the file denoted by this abstract pathname is a directory.
     *
     * @return bool
     */
    function isDirectory()
    {
        clearstatcache();
        return is_dir($this->_path);
    }

    /**
     * Tests whether the file denoted by this abstract pathname is a normal
     * file.
     *
     * @return bool
     */
    function isFile()
    {
        clearstatcache();
        return is_file($this->_path);
    }

    /**
     * Tests whether the application can read the file denoted by this abstract
     * pathname.
     *
     * @return bool
     */
    function isReadable()
    {
        clearstatcache();
        return is_readable($this->_path);
    }

    /**
     * Tests whether the application can modify to the file denoted by this
     * abstract pathname.
     *
     * @return bool
     */
    function isWritable()
    {
        clearstatcache();
        return is_writable($this->_path);
    }

    /**
     * Returns an array of strings naming the files and directories in the
     * directory denoted by this abstract pathname.
     *
     * @return array
     */
    function listFiles()
    {
        $p = $this->_path;
        clearstatcache();
        if (is_dir($p) && is_readable($p) && ($dh = @opendir($p)))
        {
            $files = array();
            while (($file = @readdir($dh)) !== FALSE)
                if ($file != '.' && $file != '..')
                    $files[] = $file;
            @closedir($dh);
            return $files;
        }
        return FALSE;
    }

    /**
     * Creates the directory named by this abstract pathname, including any
     * necessary but nonexistent parent directories (optional).
     *
     * @return bool
     */
    function mkdir($mode=0775, $recursive=TRUE)
    {
        $p = $this->_path;
        $d = dirname($p);
        clearstatcache();
        if (file_exists($p))
            return TRUE;
        elseif (file_exists($d) && is_writable($d))
        {
            $u = umask(0);
            $md = mkdir($p, $mode);
            umask($u);
            return $md;
        }
        elseif ($recursive)
        {
            $parts = explode('/', $p);
            $path = '';
            $u = umask(0);
            foreach ($parts as $part)
            {
                if ($part != '')
                {
                    $path .= $part;
                    if (! file_exists($path))
                    {
                        if (! is_writable(dirname($path)))
                            break;
                        mkdir($path, $mode);
                    }
                }
                $path .= '/';
            }
            umask($u);
            return is_dir($p);
        }
        return FALSE;
    }

    /**
     * Renames the file denoted by this abstract pathname.
     *
     * @return bool
     */
    function renameTo($dest)
    {
        $file = new File($dest);
        $dst = $file->path();
        $dir = dirname($dst);
        $src = $this->_path;
        clearstatcache();
        $dw = (file_exists($dir) && is_writable($dir));
        $rn = (file_exists($src) && ! file_exists($dst) && $dw ? @rename($src, $dst) : FALSE);
        $this->_path = ($rn ? $dst : $src);
        return $rn;
    }

    /**
     * Sets the last-modified time of the file or directory named by this
     * abstract pathname.
     *
     * @return bool
     */
    function setLastModified($ts)
    {
        clearstatcache();
        return (file_exists($this->_path) ? @touch($this->_path, $ts) : FALSE);
    }

}

/**
 *
 */
class FileNotFoundError extends Error
{

    /**
     *
     */
    function FileNotFoundError($file=NULL, $line=NULL)
    {
        $msg  = 'File not found';
        $code = E_USER_ERROR;
        parent::Error($msg, $code, $file, $line);
    }

}

?>
