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
 * @subpackage mail
 */
if (! defined('BOSC'))
    require_once(realpath(dirname(__FILE__).'/../bosc.inc.php'));

/**
 *
 */
require_once(BOSC_EXT.'/phpmailer/class.phpmailer.php');

class Mailer extends Object
{

    /**
     *
     */
    var $_mailer;

    /**
     *
     */
    function Mailer()
    {
        $this->_mailer = new PHPMailer();
    }

    /**
     *
     */
    function addAttachment($path, $name='', $encoding='base64', $type='application/octet-stream')
    {
        $this->_mailer->AddAttachment($path, $name, $encoding, $type);
    }

    /**
     *
     */
    function addBccRecipient($addr, $name='')
    {
        $this->_mailer->AddBCC($addr, $name);
    }

    /**
     *
     */
    function addCcRecipient($addr, $name='')
    {
        $this->_mailer->AddCC($addr, $name);
    }

    /**
     *
     */
    function addRecipient($addr, $name='')
    {
        $this->_mailer->AddAddress($addr, $name);
    }

    /**
     *
     */
    function addReplyTo($addr, $name='')
    {
        $this->_mailer->AddReplyTo($addr, $name);
    }

    /**
     *
     */
    function clearAttachments()
    {
        $this->_mailer->ClearAttachments();
    }

    /**
     *
     */
    function clearRecipients()
    {
        $this->_mailer->ClearAllRecipients();
    }

    /**
     *
     */
    function embedImage($path, $cid, $name='', $encoding='base64', $type='application/octet-stream')
    {
        $this->_mailer->AddEmbeddedImage($path, $cid, $name, $encoding, $type);
    }

    /**
     *
     */
    function setBody($body, $alt='')
    {
        $this->_mailer->Body = $body;
        $this->_mailer->Alt  = $alt;
    }

    /**
     *
     */
    function setCharSet($charset)
    {
        $this->_mailer->CharSet = $charset;
    }

    /**
     *
     * @param string $type (text/plain | text/html)
     */
    function setContentType($type)
    {
        $this->_mailer->ContentType = $type;
    }

    /**
     *
     * @param string $enc (8bit | 7bit | binary | base64 | quoted-printable)
     */
    function setEncoding($enc)
    {
        $this->_mailer->Encoding = $enc;
    }

    /**
     *
     */
    function setSender($addr, $name=NULL)
    {
        $this->_mailer->From = $addr;
        $this->_mailer->FromName = $name;
    }

    /**
     *
     */
    function setSubject($subject)
    {
        $this->_mailer->Subject = $subject;
    }

    /**
     *
     */
    function send()
    {
        $this->_mailer->Send();
    }

}

?>
