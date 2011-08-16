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
 *
 */
class FormValidator extends Object
{

    /**
     *
     */
    var $_ctrls = array();

    /**
     *
     */
    var $_errors = array();

    /**
     *
     */
    var $_validated = FALSE;

    /**
     *
     */
    var $_rules = array();

    /**
     *
     */
    function FormValidator()
    {
    }

    /**
     *
     */
    function addControl(&$ctrl)
    {
        $this->_ctrls[$ctrl->getName()] =& $ctrl;
        $this->_validated = FALSE;
    }

    /**
     *
     */
    function addRule(&$ctrl, &$rule)
    {
        $name = $ctrl->getName();
        if (! isset($this->_ctrls[$name]))
            $this->_ctrls[$name] =& $ctrl;
        $this->_rules[$name][] =& $rule;
        $this->_validated = FALSE;
    }

    /**
     *
     */
    function getError(&$ctrl)
    {
        $name = $ctrl->getName();
        return (isset($this->_errors[$name]) ? $this->_errors[$name] : NULL);
    }

    /**
     *
     */
    function getErrors()
    {
        return $this->_errors;
    }

    /**
     *
     */
    function hasErrors()
    {
        return (! empty($this->_errors));
    }

    /**
     *
     */
    function isValid()
    {
        if (! $this->_validated)
            $this->validate();
        return (empty($this->_errors));
    }

    /**
     *
     */
    function isSubmitted()
    {
        return (bool) Request::getForm();
    }

    /**
     *
     */
    function validate()
    {
        $errors = array();
        foreach ($this->_rules as $ctrl_name => $rules)
        {
            $ctrl =& $this->_ctrls[$ctrl_name];
            foreach ($rules as $rule)
            {
                if (! $rule->isValid($ctrl->getValue()))
                {
                    $errors[$ctrl_name] = '"'.$ctrl->getLabel().'" is '.$rule->getErrorMessage();
                    break;
                }
            }
        }
        $this->_errors =& $errors;
        $this->_validated = TRUE;
    }

}

/**
 *
 */
class ValidatorRule extends Object
{

    /**
     *
     */
    var $_message;

    /**
     *
     */
    function ValidatorRule($msg='not valid')
    {
        $this->_message = $msg;
    }

    /**
     *
     */
    function getErrorMessage()
    {
        return $this->_message;
    }

    /**
     *
     */
    function isValid($subject)
    {
        return FALSE;
    }

}

/**
 *
 */
class MatchingRule extends ValidatorRule
{

    /**
     *
     */
    var $_ctrl;

    /**
     *
     */
    function MatchingRule(&$ctrl)
    {
        $msg = 'required to match "'.$ctrl->getLabel().'"';
        parent::ValidatorRule($msg);
        $this->_ctrl =& $ctrl;
    }

    /**
     *
     */
    function isValid($subject)
    {
        return ($subject ? $subject == $this->_ctrl->getValue() : TRUE);
    }

}

/**
 *
 */
class RequiredRule extends ValidatorRule
{

    /**
     *
     */
    function RequiredRule()
    {
        $msg = 'required';
        parent::ValidatorRule($msg);
    }

    /**
     *
     */
    function isValid($subject)
    {
        return (isset($subject) && $subject !== '');
    }

}

/**
 *
 */
class PatternRule extends ValidatorRule
{

    /**
     *
     */
    var $rgx = '';

    /**
     *
     */
    function PatternRule($msg='does not match pattern', $rgx='')
    {
        parent::ValidatorRule($msg);
        $this->rgx = $rgx;
    }

    /**
     *
     */
    function isValid($subject)
    {
        return ($subject ? preg_match($this->rgx, $subject) : TRUE);
    }

}

/**
 *
 */
class EmailRule extends PatternRule
{

    /**
     *
     */
    function EmailRule()
    {
        $msg = 'not a valid email address';
        $rgx = '/^[-!#$%&\'*+\\.\/0-9=?A-Za-z^_`{|}~]+@([-0-9A-Za-z]+\.)+([0-9A-Za-z]){2,6}$/';
        parent::PatternRule($msg, $rgx);
    }

}

/**
 *
 */
class CreditCardRule extends ValidatorRule
{

    /**
     *
     */
    function CreditCardRule()
    {
        $msg = 'not a valid credit card number';
        parent::ValidatorRule($msg);
    }

    /**
     *
     */
    function luhn($subject)
    {
        $subject = preg_replace ('[^0-9]+', '', $subject);
        for ($i = 0, $max = strlen($subject); $i < $max; ++$i)
            $d .= ($i % 2 ? $subject[$i]*2 : $subject[$i]);
        for ($i = 0, $max = strlen($d); $i < $max; ++$i)
            $s += $d[$i];
        return ($s % 10 ? FALSE : TRUE);
    }

    /**
     *
     */
    function isValid($subject)
    {
        return ($subject ? CreditCardRule::luhn($subject) : TRUE);
    }

}

/**
 *
 */
class UniqueRule extends ValidatorRule
{

    /**
     *
     */
    var $disallowed = array();

    /**
     *
     */
    function UniqueRule($disallowed)
    {
        $msg = 'required to be unique';
        parent::ValidatorRule($msg);
        $this->disallowed = (is_array($disallowed) ? $disallowed : array(disallowed));
    }

    /**
     *
     */
    function isValid($subject)
    {
        return (isset($subject) &&  in_array($subject, $this->disallowed) ? FALSE : TRUE);
    }

}

?>
