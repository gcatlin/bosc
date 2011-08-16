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

require_once(BOSC.'/io/File.class.php');

/**
 * 
 * Resources:
 *   o http://php.net/manual/en/features.file-upload.php
 *   o http://php.net/manual/en/function.is-uploaded-file.php
 *   o http://php.net/manual/en/function.move-uploaded-file.php
 */
class UploadFile extends File
{

    /**
     *
     */
    var $_error;
    
    /**
     *
     */
    var $_name;
    
    /**
     *
     */
    var $_size;
    
    /**
     *
     */
    var $_type;
    
    /**
     * @param  string $name
     * @return void
     */
    function UploadFile($name)
    {
        $file = (isset($_FILES[$name]) ? $_FILES[$name] : NULL);
        $this->_error = $file['error'];
        $this->_name  = $file['name'];
        $this->_size  = (int) $file['size'];
        $this->_type  = $file['type'];
        parent::File($file['tmp_name']);
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
    function isUploaded()
    {
        return ($this->_error === UPLOAD_ERR_OK);
    }

    /**
     * 
     * @param  string $dir
     * @return bool
     */
    function moveTo($dir)
    {
        $dest = new File($dir);
        if ($this->isUploaded() && is_uploaded_file(parent::getPath()) && $dest->isWritable())
        {
            $file = new File($dest->getPath().'/'.$this->_name);
            if ($file->isFile() && $dest->isReadable())
            {
                $ext = '.'.$file->getExtension();
                $base = $file->getName($ext);
                $matches = array_values(preg_grep('/'.$base.'\[\d+\]'.$ext.'/', $dest->listFiles()));
                $counts = preg_replace('/'.$base.'\[(\d+)\]'.$ext.'/', '\1', $matches);
                $counts[] = 0;
                $file = new File($dest->getPath().'/'.$base.'['.(max($counts) + 1).']'.$ext);
            }
            if (@move_uploaded_file(parent::getPath(), $file->getPath()))
            {
                parent::File($file->getPath());
                return TRUE;
            }
        }
        return FALSE;
    }

}

?>