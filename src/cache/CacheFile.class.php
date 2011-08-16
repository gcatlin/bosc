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
 * @subpackage cache
 */
if (! defined('BOSC'))
    require_once(realpath(dirname(__FILE__).'/../bosc.inc.php'));

require_once(BOSC.'/cache/Cache.class.php');

/**
 *
 */
class CacheFile extends Cache
{

    /**
     *
     */
    var $_file;

    /**
     *
     */
    function CacheFile($id, $lifetime=1, $dir='/tmp')
    {
        $id = md5($id);
        parent::Cache($id, $ttl);
        $this->setFile($dir.'/'.$id);
    }

    /**
     *
     */
    function delete()
    {
        return $this->_file->delete();
    }

    /**
     *
     */
    function getFile()
    {
        return $this->_file->path();
    }

    /**
     *
     */
    function getLastModified()
    {
        return $this->_file->lastModified();
    }

    /**
     *
     */
    function isCached()
    {
        return $this->_file->isFile();
    }

    /**
     *
     */
    function read()
    {
        if ($this->getLastModified() < $this->getExpiration())
            return NULL;

        include_once(BOSC.'/io/FileReader.class.php');
        $reader = new FileReader($this->_file);
        $hash1 = $reader->read(32);
        $cache = $reader->read($this->_file->getSize() - 32);
        $reader->close();
        $hash2 = sprintf('% 32d', crc32($cache));
        if ($hash1 != $hash2)
        {
            $this->setLastModified(time() - (2 * abs(parent::getLifetime())));
            return NULL;
        }
        return $cache;
    }

    /**
     *
     * @param  string $file
     * @return void
     */
    function setFile($file)
    {
        include_once(BOSC.'/io/File.class.php');
        $this->_file = new File($file);
    }

    /**
     *
     * @param  int  $timestamp
     * @return void
     */
    function setLastModified($timestamp)
    {
        $this->_file->setLastModified($timestamp);
    }

    /**
     *
     * @param  string $cache
     * @return void
     */
    function write($cache)
    {
        include_once(BOSC.'/io/FileWriter.class.php');
        $writer = new FileWriter($this->_file);
        $writer->write(sprintf('% 32d', crc32($cache)).$cache);
        $writer->close();
    }

}

?>
