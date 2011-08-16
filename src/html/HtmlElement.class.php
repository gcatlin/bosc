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
 * @subpackage html
 */
if (! defined('BOSC'))
    require_once(realpath(dirname(__FILE__).'/../bosc.inc.php'));

/**
 * Resources:
 *   o http://www.w3.org/TR/REC-html40/
 *   o http://www.w3.org/TR/xhtml1/
 *   o http://www.w3.org/TR/REC-xml
 */
class HtmlElement extends Object
{

    /**
     *
     */
    var $_attributes = array();

    /**
     *
     */
    var $_content;

    /**
     *
     */
    var $_type;

    /**
     *
     */
    function HtmlElement($type=NULL)
    {
        $this->_type = $type;
        $this->addAttribute(new ElementAttribute('class',       NULL));
        $this->addAttribute(new ElementAttribute('dir',         NULL));
        $this->addAttribute(new ElementAttribute('id',          NULL));
        $this->addAttribute(new ElementAttribute('lang',        NULL));
        $this->addAttribute(new ElementAttribute('onclick',     NULL));
        $this->addAttribute(new ElementAttribute('ondblclick',  NULL));
        $this->addAttribute(new ElementAttribute('onkeydown',   NULL));
        $this->addAttribute(new ElementAttribute('onkeypress',  NULL));
        $this->addAttribute(new ElementAttribute('onkeyup',     NULL));
        $this->addAttribute(new ElementAttribute('onmousedown', NULL));
        $this->addAttribute(new ElementAttribute('onmousemove', NULL));
        $this->addAttribute(new ElementAttribute('onmouseout',  NULL));
        $this->addAttribute(new ElementAttribute('onmouseover', NULL));
        $this->addAttribute(new ElementAttribute('onmouseup',   NULL));
        $this->addAttribute(new ElementAttribute('style',       NULL));
        $this->addAttribute(new ElementAttribute('title',       NULL));
    }

    /**
     *
     */
    function addAttribute(&$attribute)
    {
        $this->_attributes[$attribute->getName()] =& $attribute;
    }

    /**
     *
     */
    function addContent($content)
    {
        $this->_content .= $content;
    }

    /**
     *
     */
    function attributesToHtml()
    {
        $atts = '';
        foreach ($this->_attributes as $att)
            if ($att->getValue() !== NULL)
                $atts .= ' '.$att->__toString();
        return $atts;
    }

    /**
     *
     */
    function getAttribute($name)
    {
        return (isset($this->_attributes[$name]) ? $this->_attributes[$name]->getValue() : NULL);
    }

    /**
     *
     */
    function getAttributeNames()
    {
        return (array_keys($this->_attributes));
    }

    /**
     *
     */
    function getContent()
    {
        return $this->_content;
    }

    /**
     *
     */
    function getType()
    {
        return $this->_type;
    }

    /**
     *
     */
    function hasAttribute($name)
    {
        return (in_array($name, $this->_attributes));
    }

    /**
     *
     */
    function isEmpty()
    {
        return FALSE;
    }

    /**
     *
     */
    function setAttribute($name, $value)
    {
        $old_setting = $this->getAttribute($name);
        if (isset($this->_attributes[$name]))
            $this->_attributes[$name]->setValue($value);
        return $old_setting;
    }

    /**
     *
     */
    function setContent($content)
    {
        $old_setting = $this->getContent();
        $this->_content = $content;
        return $old_setting;
    }

    /**
     *
     */
    function toHtml()
    {
        return $this->__toString();
    }

    /**
     *
     */
    function __toString()
    {
        return '<'.$this->_type.
               $this->attributesToHtml().
               ($this->isEmpty() ? ' />' : '>'.
               $this->_content.
               '</'.$this->_type.'>');
    }

}

/**
 *
 */
class ElementAttribute extends Object
{

    /**
     *
     */
    var $_name;

    /**
     *
     */
    var $_value;

    /**
     *
     */
    function ElementAttribute($name, $value=NULL)
    {
        $this->_name = $name;
        $this->_value = $value;
    }

    /**
     *
     */
    function getName()
    {
        return $this->_name;
    }

    /**
     *
     */
    function getValue()
    {
        return $this->_value;
    }

    /**
     *
     */
    function setValue($value)
    {
        $this->_value = $value;
    }

    /**
     *
     */
    function toHtml()
    {
        return $this->__toString();
    }

    /**
     *
     */
    function __toString()
    {
        return htmlentities($this->_name).'="'.htmlentities($this->_value).'"';
    }

}

/**
 *
 */
class EmptyHtmlElement extends HtmlElement
{

    /**
     *
     */
    function EmptyHtmlElement($type=NULL)
    {
        parent::HtmlElement($type);
    }

    /**
     *
     */
    function getContent()
    {
        return NULL;
    }

    /**
     *
     */
    function isEmpty()
    {
        return TRUE;
    }

    /**
     *
     */
    function setContent()
    {
        return NULL;
    }

}

/**
 *
 */
class AnchorElement extends HtmlElement
{

    /**
     *
     */
    function AnchorElement($content, $href)
    {
        $type = 'a';
        parent::HtmlElement($type);
        parent::addAttribute(new ElementAttribute('href', $href));
        parent::setContent($content);
    }

}

/**
 *
 */
class ImageElement extends EmptyHtmlElement
{

    /**
     *
     */
    function ImageElement($src, $alt=NULL)
    {
        $type = 'img';
        parent::HtmlElement($type);
        parent::addAttribute(new ElementAttribute('alt',    $alt));
        parent::addAttribute(new ElementAttribute('border', 0));
        parent::addAttribute(new ElementAttribute('height', NULL));
        parent::addAttribute(new ElementAttribute('weight', NULL));
        parent::addAttribute(new ElementAttribute('src',    $src));
    }

}

?>
