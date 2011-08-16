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

require_once(BOSC.'/html/HtmlElement.class.php');

/**
 * Resources:
 *   o http://www.w3.org/TR/REC-html40/interact/forms.html
 */
class FormElement extends HtmlElement
{

    /**
     *
     */
    function FormElement($method=NULL, $action=NULL)
    {
        parent::HtmlElement('form');
        $this->addAttribute(new ElementAttribute('accept',         NULL));
        $this->addAttribute(new ElementAttribute('action',         ($action ? $action : Request::getPath())));
        $this->addAttribute(new ElementAttribute('accept-charset', NULL));
        $this->addAttribute(new ElementAttribute('enctype',        NULL));
        $this->addAttribute(new ElementAttribute('method',         ($method ? $method : 'get')));
        $this->addAttribute(new ElementAttribute('name',           NULL));
        $this->addAttribute(new ElementAttribute('onreset',        NULL));
        $this->addAttribute(new ElementAttribute('onsubmit',       NULL));
    }

}

/**
 *
 */
class ButtonElement extends HtmlElement
{

    /**
     *
     */
    function ButtonElement($type=NULL, $name=NULL)
    {
        parent::HtmlElement('button');
        $this->addAttribute(new ElementAttribute('accesskey', NULL));
        $this->addAttribute(new ElementAttribute('disabled',  NULL));
        $this->addAttribute(new ElementAttribute('name',      $name));
        $this->addAttribute(new ElementAttribute('onblur',    NULL));
        $this->addAttribute(new ElementAttribute('onfocus',   NULL));
        $this->addAttribute(new ElementAttribute('tabindex',  NULL));
        $this->addAttribute(new ElementAttribute('type',      $type));
        $this->addAttribute(new ElementAttribute('value',     NULL));
    }

}

/**
 *
 */
class InputElement extends EmptyHtmlElement
{

    /**
     *
     */
    function InputElement($type=NULL, $name=NULL)
    {
        parent::HtmlElement('input');
        $this->addAttribute(new ElementAttribute('accept',    NULL));
        $this->addAttribute(new ElementAttribute('accesskey', NULL));
        $this->addAttribute(new ElementAttribute('alt',       NULL));
        $this->addAttribute(new ElementAttribute('checked',   NULL));
        $this->addAttribute(new ElementAttribute('disabled',  NULL));
        $this->addAttribute(new ElementAttribute('ismap',     NULL));
        $this->addAttribute(new ElementAttribute('maxlength', NULL));
        $this->addAttribute(new ElementAttribute('name',      $name));
        $this->addAttribute(new ElementAttribute('onblur',    NULL));
        $this->addAttribute(new ElementAttribute('onchange',  NULL));
        $this->addAttribute(new ElementAttribute('onfocus',   NULL));
        $this->addAttribute(new ElementAttribute('onselect',  NULL));
        $this->addAttribute(new ElementAttribute('readonly',  NULL));
        $this->addAttribute(new ElementAttribute('size',      NULL));
        $this->addAttribute(new ElementAttribute('src',       NULL));
        $this->addAttribute(new ElementAttribute('tabindex',  NULL));
        $this->addAttribute(new ElementAttribute('type',      $type));
        $this->addAttribute(new ElementAttribute('usemap',    NULL));
        $this->addAttribute(new ElementAttribute('value',     NULL));
    }

}

/**
 *
 */
class NoscriptElement extends HtmlElement
{

    /**
     *
     */
    function NoscriptElement()
    {
        parent::HtmlElement('noscript');
    }

}

/**
 *
 */
class OptionElement extends HtmlElement
{

    /**
     *
     */
    function OptionElement($value=NULL, $content='', $selected=NULL)
    {
        parent::HtmlElement('option');
        $this->addAttribute(new ElementAttribute('disabled',  NULL));
        $this->addAttribute(new ElementAttribute('label',     NULL));
        $this->addAttribute(new ElementAttribute('selected',  ($selected ? 'selected' : NULL)));
        $this->addAttribute(new ElementAttribute('value',     $value));
        $this->setContent($content);
    }

}

/**
 *
 */
class SelectElement extends HtmlElement
{

    /**
     *
     */
    function SelectElement($name=NULL, $size=NULL, $multiple=NULL)
    {
        parent::HtmlElement('select');
        $this->addAttribute(new ElementAttribute('disabled',  NULL));
        $this->addAttribute(new ElementAttribute('multiple',  ($multiple ? 'multiple' : NULL)));
        $this->addAttribute(new ElementAttribute('name',      $name.($multiple ? '[]' : '')));
        $this->addAttribute(new ElementAttribute('onblur',    NULL));
        $this->addAttribute(new ElementAttribute('onchange',  NULL));
        $this->addAttribute(new ElementAttribute('onfocus',   NULL));
        $this->addAttribute(new ElementAttribute('size',      ($size ? max(($multiple?2:1), (int) $size) : NULL)));
        $this->addAttribute(new ElementAttribute('tabindex',  NULL));
    }

}

/**
 *
 */
class ScriptElement extends HtmlElement
{

    /**
     *
     */
    function ScriptElement($type='text/javascript', $src=NULL, $defer=NULL)
    {
        parent::HtmlElement('script');
        $this->attributes = array();
        $this->addAttribute(new ElementAttribute('charset',   NULL));
        $this->addAttribute(new ElementAttribute('defer',     ($defer ? 'defer' : NULL)));
        $this->addAttribute(new ElementAttribute('src',       ($src ? $src : NULL)));
        $this->addAttribute(new ElementAttribute('type',      $type));
    }

}

/**
 *
 */
class TextareaElement extends HtmlElement
{

    /**
     *
     */
    function TextareaElement($name=NULL, $cols=NULL, $rows=NULL)
    {
        parent::HtmlElement('textarea');
        $this->addAttribute(new ElementAttribute('accesskey', NULL));
        $this->addAttribute(new ElementAttribute('cols',      ($cols ? (int) $cols : NULL)));
        $this->addAttribute(new ElementAttribute('disabled',  NULL));
        $this->addAttribute(new ElementAttribute('name',      $name));
        $this->addAttribute(new ElementAttribute('onblur',    NULL));
        $this->addAttribute(new ElementAttribute('onchange',  NULL));
        $this->addAttribute(new ElementAttribute('onfocus',   NULL));
        $this->addAttribute(new ElementAttribute('onselect',  NULL));
        $this->addAttribute(new ElementAttribute('readonly',  NULL));
        $this->addAttribute(new ElementAttribute('rows',      ($rows ? (int) $rows : NULL)));
        $this->addAttribute(new ElementAttribute('tabindex',  NULL));
    }

}

?>