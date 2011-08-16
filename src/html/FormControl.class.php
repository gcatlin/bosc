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

require_once(BOSC.'/html/FormElement.class.php');

/**
 *
 */
class FormControl extends Object
{

    /**
     *
     */
    var $_label;

    /**
     *
     */
    function FormControl($label=NULL)
    {
        $this->_label = $label;
    }

    /**
     *
     */
    function getLabel()
    {
        return $this->_label;
    }

    /**
     *
     */
    function setLabel($label)
    {
        $old_setting = $this->getLabel();
        $this->_label = $label;
        return $old_setting;
    }

}

/**
 *
 */
class ButtonControl extends FormControl
{

    /**
     *
     */
    var $_element;

    /**
     *
     */
    function ButtonControl($type='button', $name=NULL, $value=NULL)
    {
        parent::FormControl($name);
        $this->_element = new ButtonElement($type, $name);
        $this->setValue($value);
        parent::FormControl($name);
    }

    /**
     *
     */
    function getName()
    {
        return $this->_element->getAttribute('name');
    }

    /**
     *
     */
    function setAttribute($name, $value)
    {
        return $this->_element->setAttribute($name, $value);
    }

    /**
     *
     */
    function setContent($content)
    {
        return $this->_element->setContent($content);
    }

    /**
     *
     */
    function setValue($value)
    {
        return $this->_element->setAttribute('value', $value);
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
        return $this->_element->__toString();
    }

}

/**
 *
 */
class InputControl extends FormControl
{

    /**
     *
     */
    var $_element;

    /**
     *
     */
    function InputControl($type, $name, $value=NULL)
    {
        parent::FormControl($name);
        $this->_element = new InputElement($type, $name);
        $this->setValue($value);
    }

    /**
     *
     */
    function getName()
    {
        return $this->_element->getAttribute('name');
    }

    /**
     *
     */
    function getValue()
    {
        return $this->_element->getAttribute('value');
    }

    /**
     *
     */
    function setAttribute($name, $value)
    {
        return $this->_element->setAttribute($name, $value);
    }

    /**
     *
     */
    function setChecked($checked=TRUE)
    {
        return $this->setAttribute('checked', ($checked ? 'checked' : NULL));
    }

    /**
     *
     */
    function setMaxLength($maxlen)
    {
        return $this->setAttribute('maxlength', ($maxlen ? (int) $maxlen : NULL));
    }

    /**
     *
     */
    function setSize($size)
    {
        return $this->setAttribute('size', ($size ? (int) $size : NULL));
    }

    /**
     *
     */
    function setSource($src)
    {
        return $this->setAttribute('src', $src);
    }

    /**
     *
     */
    function setValue($value)
    {
        return $this->setAttribute('value', $value);
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
        return $this->_element->__toString();
    }

}



/**
 *
 */
class CheckboxControl extends InputControl
{

    /**
     *
     */
    function CheckboxControl($name, $value=NULL, $checked=NULL)
    {
        parent::InputControl('checkbox', $name, $value);
        parent::setChecked($checked !== NULL ? $checked : Request::getParameter($name) == $value);
    }

}

/**
 *
 */
class FileSelectControl extends InputControl
{

    /**
     *
     */
    function FileSelectControl($name, $size=NULL)
    {
        parent::InputControl('file', $name);
        parent::setSize($size);
    }

}

/**
 *
 */
class HiddenControl extends InputControl
{

    /**
     *
     */
    function HiddenControl($name, $value=NULL)
    {
        parent::InputControl('hidden', $name);
        parent::setValue($value !== NULL ? $value : Request::getParameter($name));
    }

}

/**
 *
 */
class ImageControl extends InputControl
{

    /**
     *
     */
    function ImageControl($name, $value=NULL, $src=NULL)
    {
        parent::InputControl('image', $name, $value);
        parent::setSource($src);
    }

}

/**
 *
 */
class MenuControl extends FormControl
{

    /**
     *
     */
    var $_element;

    /**
     *
     */
    var $_options = array();

    /**
     *
     */
    var $_selected = array();

    /**
     *
     */
    function MenuControl($name, $selected=NULL, $options=NULL, $size=NULL, $multiple=NULL)
    {
        parent::FormControl($name);
        $this->_element = new SelectElement($name, $size, $multiple);
        $options = (is_array($options) ? $options : array($options));
        foreach ($options as $k => $v)
            $this->addOption($k, $v);
        $this->setSelectedIndexes($selected !== NULL ? $selected : Request::getParameter($name));
    }

    /**
     *
     */
    function addOption($value, $content, $selected=NULL)
    {
        $this->_options[] = new OptionElement($value, $content, $selected);
    }

    /**
     *
     */
    function addSelectedIndex($value)
    {
        if ($value && ! in_array($value, $this->_selected))
        {
            if ($this->_element->getAttribute('multiple'))
                $this->_selected[] = $value;
            else
                $this->_selected[0] = $value;
        }
    }

    /**
     *
     */
    function getName()
    {
        return $this->_element->getAttribute('name');
    }

    /**
     *
     */
    function getSelectedIndexes()
    {
        return $this->_selected;
    }

    /**
     *
     */
    function getValue()
    {
        return $this->_selected[0];
    }

    /**
     *
     */
    function setAttribute($name, $value)
    {
        return $this->_element->setAttribute($name, $value);
    }

    /**
     *
     */
    function setSelectedIndexes($values)
    {
        $old_setting = $this->_selected;
        $values = array_unique(is_array($values) ? $values : array($values));
        $this->_selected = array();
        foreach ($values as $value)
            $this->addSelectedIndex($value);
        return $old_setting;
    }

    /**
     *
     */
    function setValue($value)
    {
        return $this->setSelectedIndexes($value);
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
        $selected = $this->_selected;
        $this->_element->setContent('');
        foreach ($this->_options as $option)
        {
            if (in_array($option->getAttribute('value'), $selected))
            {
                $option->setAttribute('selected', 'selected');
                list($key) = array_keys($selected, $option->getAttribute('value'));
                array_splice($selected, $key, 1);
            }
            $this->_element->addContent($option->toHtml());
        }
        return $this->_element->__toString();
    }

}

/**
 *
 */
class MultiLineTextControl extends FormControl
{

    /**
     *
     */
    var $_element;

    /**
     *
     */
    function MultiLineTextControl($name, $value=NULL, $cols=NULL, $rows=NULL)
    {
        parent::FormControl($name);
        $this->_element = new TextareaElement($name, $cols, $rows);
        $this->setValue($value !== NULL ? $value : Request::getParameter($name));
    }

    /**
     *
     */
    function getName()
    {
        return $this->_element->getAttribute('name');
    }

    /**
     *
     */
    function getValue()
    {
        return $this->_element->getContent();
    }

    /**
     *
     */
    function setAttribute($name, $value)
    {
        return $this->_element->setAttribute($name, $value);
    }

    /**
     *
     */
    function setValue($text)
    {
        return $this->_element->setContent($text);
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
        return $this->_element->__toString();
    }

}

/**
 *
 */
class PasswordControl extends InputControl
{

    /**
     *
     */
    function PasswordControl($name, $value=NULL, $size=NULL, $maxlength=NULL)
    {
        parent::InputControl('password', $name);
        parent::setMaxLength($maxlength);
        parent::setSize($size);
        parent::setValue($value !== NULL ? $value : Request::getParameter($name));
    }

}

/**
 *
 */
class RadioControl extends InputControl
{

    /**
     *
     */
    function RadioControl($name, $value=NULL, $checked=NULL)
    {
        parent::InputControl('radio', $name, $value);
        parent::setChecked(Request::getParameter($name) == $value);
    }

}

/**
 *
 */
class ResetButtonControl extends ButtonControl
{

    /**
     *
     */
    function ResetButtonControl($name=NULL, $value='Reset')
    {
        parent::ButtonControl('reset', $name, $value);
    }

}

/**
 *
 */
class ResetControl extends InputControl
{

    /**
     *
     */
    function ResetControl($name=NULL, $value=NULL)
    {
        parent::InputControl('reset', $name, $value);
    }

}

/**
 *
 */
class SingleLineTextControl extends InputControl
{

    /**
     *
     */
    function SingleLineTextControl($name, $value=NULL, $size=NULL, $maxlength=NULL)
    {
        parent::InputControl('text', $name);
        parent::setMaxLength($maxlength);
        parent::setSize($size);
        parent::setValue($value !== NULL ? $value : Request::getParameter($name));
    }

}

/**
 *
 */
class SubmitButtonControl extends ButtonControl
{

    /**
     *
     */
    function SubmitButtonControl($name=NULL, $value='Submit Query')
    {
        parent::ButtonControl('submit', $name, $value);
    }

}

/**
 *
 */
class SubmitControl extends InputControl
{

    /**
     *
     */
    function SubmitControl($name=NULL, $value=NULL)
    {
        parent::InputControl('submit', $name, $value);
    }

}

/**
 *
 */
class TextControl extends FormControl
{

    /**
     *
     */
    var $_control;

    /**
     *
     */
    function TextControl($name, $value=NULL, $cols=NULL, $rows=1)
    {
        parent::FormControl($name);
        if ($rows == 1)
            $this->_control = new SingleLineTextControl($name, NULL, $cols);
        else
            $this->_control = new MultiLineTextControl($name, NULL, $cols, $rows);
        $this->setValue($value !== NULL ? $value : Request::getParameter($name));
    }

    /**
     *
     */
    function getName()
    {
        return $this->_control->getName();
    }

    /**
     *
     */
    function getValue()
    {
        return $this->_control->getValue();
    }

    /**
     *
     */
    function setAttribute($name, $value)
    {
        return $this->_control->setAttribute($name, $value);
    }

    /**
     *
     */
    function setValue($text)
    {
        return $this->_control->setValue($text);
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
        return $this->_control->toHtml();
    }

}

/**
 *
 */
class TimeControl extends FormControl
{

    /**
     *
     */
    var $_controls = array();

    /**
     *
     */
    var $_fields = array('y'=>FALSE,'m'=>FALSE,'d'=>FALSE,'h'=>FALSE,'i'=>FALSE,'s'=>FALSE);

    /**
     *
     */
    var $_format;

    /**
     *
     */
    var $_script;

    /**
     *
     */
    function TimeControl($name, $value=NULL, $format='m d, y')
    {
        parent::FormControl($name);
        foreach ($this->_fields as $char=>$exists)
        {
            if (strpos($format, $char) !== FALSE)
                $this->_fields[$char] = TRUE;
            else
                $format .= $char;
        }
        $this->_format = $format;
        $this->_controls['t'] = new HiddenControl($name);
        $this->_controls['y'] = ($this->_fields['y'] ? new MenuControl($name.'__y', NULL, array('1970'=>'1970','1971'=>'1971','1972'=>'1972','1973'=>'1973','1974'=>'1974','1975'=>'1975','1976'=>'1976','1977'=>'1977','1978'=>'1978','1979'=>'1979','1980'=>'1980','1981'=>'1981','1982'=>'1982','1983'=>'1983','1984'=>'1984','1985'=>'1985','1986'=>'1986','1987'=>'1987','1988'=>'1988','1989'=>'1989','1990'=>'1990','1991'=>'1991','1992'=>'1992','1993'=>'1993','1994'=>'1994','1995'=>'1995','1996'=>'1996','1997'=>'1997','1998'=>'1998','1999'=>'1999','2000'=>'2000','2001'=>'2001','2002'=>'2002','2003'=>'2003','2004'=>'2004','2005'=>'2005','2006'=>'2006','2007'=>'2007','2008'=>'2008','2009'=>'2009','2010'=>'2010','2011'=>'2011','2012'=>'2012','2013'=>'2013','2014'=>'2014','2015'=>'2015','2016'=>'2016','2017'=>'2017','2018'=>'2018','2019'=>'2019','2020'=>'2020','2021'=>'2021','2022'=>'2022','2023'=>'2023','2024'=>'2024','2025'=>'2025','2026'=>'2026','2027'=>'2027','2028'=>'2028','2029'=>'2029','2030'=>'2030','2031'=>'2031','2032'=>'2032','2033'=>'2033','2034'=>'2034','2035'=>'2035','2036'=>'2036','2037'=>'2037','2038'=>'2038')) : new HiddenControl($name.'__y'));
        $this->_controls['m'] = ($this->_fields['m'] ? new MenuControl($name.'__m', NULL, array('01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December')) : new HiddenControl($name.'__m'));
        $this->_controls['d'] = ($this->_fields['d'] ? new MenuControl($name.'__d', NULL, array('01'=>'1','02'=>'2','03'=>'3','04'=>'4','05'=>'5','06'=>'6','07'=>'7','08'=>'8','09'=>'9','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25','26'=>'26','27'=>'27','28'=>'28','29'=>'29','30'=>'30','31'=>'31')) : new HiddenControl($name.'__d'));
        $this->_controls['h'] = ($this->_fields['h'] ? new MenuControl($name.'__h', NULL, array('00'=>'12am','01'=>'1am','02'=>'2am','03'=>'3am','04'=>'4am','05'=>'5am','06'=>'6am','07'=>'7am','08'=>'8am','09'=>'9am','10'=>'10am','11'=>'11am','12'=>'12pm','13'=>'1pm','14'=>'2pm','15'=>'3pm','16'=>'4pm','17'=>'5pm','18'=>'6pm','19'=>'7pm','20'=>'8pm','21'=>'9pm','22'=>'10pm','23'=>'11pm')) : new HiddenControl($name.'__h'));
        $this->_controls['i'] = ($this->_fields['i'] ? new MenuControl($name.'__i', NULL, array('00'=>'00','01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25','26'=>'26','27'=>'27','28'=>'28','29'=>'29','30'=>'30','31'=>'31','32'=>'32','33'=>'33','34'=>'34','35'=>'35','36'=>'36','37'=>'37','38'=>'38','39'=>'39','40'=>'40','41'=>'41','42'=>'42','43'=>'43','44'=>'44','45'=>'45','46'=>'46','47'=>'47','48'=>'48','49'=>'49','50'=>'50','51'=>'51','52'=>'52','53'=>'53','54'=>'54','55'=>'55','56'=>'56','57'=>'57','58'=>'58','59'=>'59')) : new HiddenControl($name.'__i'));
        $this->_controls['s'] = ($this->_fields['s'] ? new MenuControl($name.'__s', NULL, array('00'=>'00','01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25','26'=>'26','27'=>'27','28'=>'28','29'=>'29','30'=>'30','31'=>'31','32'=>'32','33'=>'33','34'=>'34','35'=>'35','36'=>'36','37'=>'37','38'=>'38','39'=>'39','40'=>'40','41'=>'41','42'=>'42','43'=>'43','44'=>'44','45'=>'45','46'=>'46','47'=>'47','48'=>'48','49'=>'49','50'=>'50','51'=>'51','52'=>'52','53'=>'53','54'=>'54','55'=>'55','56'=>'56','57'=>'57','58'=>'58','59'=>'59')) : new HiddenControl($name.'__s'));

        $this->_controls['y']->setAttribute('onchange', 'TimeControl_'.$name.'(this.form)');
        $this->_controls['m']->setAttribute('onchange', 'TimeControl_'.$name.'(this.form)');
        $this->_controls['d']->setAttribute('onchange', 'TimeControl_'.$name.'(this.form)');
        $this->_controls['h']->setAttribute('onchange', 'TimeControl_'.$name.'(this.form)');
        $this->_controls['i']->setAttribute('onchange', 'TimeControl_'.$name.'(this.form)');
        $this->_controls['s']->setAttribute('onchange', 'TimeControl_'.$name.'(this.form)');

        $this->_script = new ScriptElement();
        $this->_script->setContent("function TimeControl_{$name}(f){e=f.elements;d=new Date(e.{$name}__y.value,e.{$name}__m.value-1,e.{$name}__d.value,e.{$name}__h.value,e.{$name}__i.value,e.{$name}__s.value);e.{$name}.value=d.getTime()/1000;};");

        $this->setValue($value !== NULL ? $value : Request::getParameter($name));
    }

    /**
     *
     */
    function getName()
    {
        return $this->_controls['t']->getName();
    }

    /**
     *
     */
    function getValue()
    {
        return $this->_controls['t']->getValue();
    }

    /**
     *
     */
    function setValue($time)
    {
        $old_setting = $this->getValue();
        $time = ((int) $time ? (int) $time : time());
        $this->_controls['y']->setValue($this->_fields['y'] ? date('Y', $time) : date('Y', time()));
        $this->_controls['m']->setValue($this->_fields['m'] ? date('m', $time) : '01');
        $this->_controls['d']->setValue($this->_fields['d'] ? date('d', $time) : '01');
        $this->_controls['h']->setValue($this->_fields['h'] ? date('H', $time) : '00');
        $this->_controls['i']->setValue($this->_fields['i'] ? date('i', $time) : '00');
        $this->_controls['s']->setValue($this->_fields['s'] ? date('s', $time) : '00');
        $this->_controls['t']->setValue($time);
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
        $html   = $this->_script->toHtml().$this->_controls['t']->toHtml();
        $format = $this->_format;
        for ($i = 0, $max = strlen($format); $i < $max; $i++)
        {
            $char = $format{$i};
            switch ($char)
            {
                case 'y':
                case 'm':
                case 'd':
                case 'h':
                case 'i':
                case 's':
                    $html .= $this->_controls[$char]->toHtml();
                    break;
                case ' ':
                    $html .= '&nbsp;';
                    break;
                default:
                    $html .= htmlspecialchars($char);
            }
        }
        return $html;
    }

}

?>