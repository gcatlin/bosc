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
 * @subpackage util
 */
if (! defined('BOSC'))
    require_once(realpath(dirname(__FILE__).'/../bosc.inc.php'));

/**
 *
 *
 * TODO:
 *     // PHP functions
 *     addslashes() and stripslashes() [quote?]
 *     chr() and ord()
 *     crypt() sha1() crc32() md5 // move to security class?
 *     htmlentities() // move to html class?
 *     htmlspecialchars() // move to html class?
 *     nl2br() // move to html class?
 *     number_format() // move to text/Format class?
 *     parse_str()
 *     quoted_printable_decode() // move to mail class?
 *     quotemeta() // move to regex class?
 *     sprintf()
 *     sscanf()
 *     str_pad()
 *     str_repeat()
 *     strip_tags()
 *     strspn() and strcspn()
 *     substr_count() or str_word_count [to count word occurences]
 *     substr_replace()  [might be good for the 'blah blah . . .' thing]
 *     wordwrap()
 *
 *     // others
 *     format($format)
 *
 *     // see flcc for several string/html functions
 */
class String extends Object
{

    /**
     *
     */
    var $_str;

    /**
     *
     * @param  string $str
     * @return void
     */
    function String($str)
    {
        $this->_str = $str;
    }

    /**
     * Returns the character at the specified index
     *
     * @param  int    $index
     * @return object
     */
    function &charAt($index)
    {
        return new String($this->_str{$index});
    }

    /**
     * Compares two strings lexicographically
     *
     * @param  string $str
     * @return int
     */
    function compareTo($str)
    {
        return strcmp($this->_str, $str);
    }

    /**
     * Compares two strings lexicographically, ignoring case differences
     *
     * @param  string $str
     * @return int
     */
    function compareToIgnoreCase($str)
    {
        return strcasecmp($this->_str, $str);
    }

    /**
     * Compares two strings using a "natural order" algorithm
     *
     * @param  string str
     * @return int
     */
    function compareToNatural($str)
    {
        return strnatcmp($this->_str, $str);
    }

    /**
     * Compares two strings using a "natural order" algorithm, ignoring case
     * differences
     *
     * @param  string $str
     * @return int
     */
    function compareToNaturalIgnoreCase($str)
    {
        return strnatcasecmp($this->_str, $str);
    }

    /**
     * Concatenates the specified string to the end of this string
     *
     * @param  string $str
     * @return object
     */
    function &concat($str)
    {
        return new String($this->_str . $str);
    }

    /**
     * Tests if this string contains the specified string
     *
     * @param  string $str
     * @return bool
     */
    function contains($str)
    {
        return (strpos($this->_str, $str) !== FALSE);
    }

    /**
     * Tests if this string contains the specified string, ignoring case
     * considerations
     *
     * @param  string $str
     * @return bool
     */
    function containsIgnoreCase($str)
    {
        return (preg_match('/'.quotemeta($str).'/i', $this->_str) == 1);
    }

    /**
     * Tests if this string ends with the specified suffix
     *
     * @param  string $str
     * @return bool
     */
    function endsWith($str)
    {
        return (strpos(strrev($this->_str), strrev($str) === 0);
        //return (strcmp(substr($this->_str, -strlen($str)), $str) == 0);
    }

    /**
     * Compares this string to the specified object
     *
     * @param  string $str
     * @return bool
     */
    function equals($str)
    {
        return (strcmp($this->_str, $str) == 0);
    }

    /**
     * Compares this string to another string, ignoring case considerations
     *
     * @param  string $str
     * @return bool
     */
    function equalsIgnoreCase($str)
    {
        return (strcasecmp($this->_str, $str) == 0);
    }

    /**
     * Returns the index within this string of the first occurrence of the
     * specified substring, starting at the specified index
     *
     * @param  string $str
     * @param  int    $off optional
     * @return int
     */
    function indexOf($str, $off=0)
    {
        $index = strpos($this->_str, $str, $off);
        return ($index === FALSE ? NULL : $index);
    }

    /**
     * Tests if this string is an empty string
     *
     * @return bool
     */
    function isEmpty()
    {
        return (trim($this->_str) == '');
    }

    /**
     * Tests if this string is null
     *
     * @return bool
     */
    function isNull()
    {
        return ($this->_str === NULL);
    }

    /**
     * Tests if the argument is a primitive string or an instance of String
     *
     * @param  mixed $arg
     * @return bool
     * @static
     */
    function isString($arg)
    {
        return (is_object($arg) && $arg->isInstanceOf('String'));
    }

    /**
     * Returns the index within this string of the last occurrence of the
     * specified string
     *
     * @param  string $str
     * @param  int    $off optional
     * @return int
     */
    function lastIndexOf($str, $off=NULL)
    {
        $off = ($off === NULL ? 0 : strlen($this->_str) - 1 - $off);
        $index = strpos(strrev($this->_str), strrev($str), $off);
        return ($index === FALSE ? NULL : strlen($this->_str) - $index - strlen($str));
    }

    /**
     * Returns the length of this string
     *
     * @return int
     */
    function length()
    {
        return strlen($this->_str);
    }

    /**
     * Tells whether or not this string matches the given regular expression
     *
     * @param  string $regex
     * @return bool
     */
    function matches($regex)
    {
        return (preg_match($regex->, $this->_str) == 1);
    }

    /**
     * Tests if two string regions are equal
     *
     * @param  int    $off
     * @param  string $str
     * @param  int    $str_offset
     * @param  int    $len
     * @return bool
     */
    function regionMatches($off, $str, $str_offset, $len)
    {
        return (strcmp(substr($this->_str, $off, $len), substr($str, $str_offset, $len)) == 0);
    }

    /**
     * Tests if two string regions are equal, ignoring case considerations
     *
     * @param  int    $off
     * @param  string $str
     * @param  int    $str_offset
     * @param  int    $len
     * @return bool
     */
    function regionMatchesIgnoreCase($off, $str, $str_offset, $len)
    {
        return (strcasecmp(substr($this->_str, $off, $len), substr($str, $str_offset, $len)) == 0);
    }

    /**
     * Returns a new string resulting from replacing all occurrences of str in
     * this string with replacement
     *
     * @param  string $str
     * @param  string $replacement
     * @return String
     */
    function &replace($str, $replacement)
    {
        return new String(str_replace($str, $replacement, $this->_str));
    }

    /**
     * Returns a new string resulting from replacing a substring of this string
     * with the specified replacement string
     *
     * @param  int    $from
     * @param  int    $to
     * @param  String $replacement
     * @return String
     */
    function &replaceSubstring($from, $to, $replacement)
    {
        return new String(substr_replace($this->_str, $replacement, $from, $to - $from));
    }

    /**
     * Replaces each substring of this string that matches the given regular
     * expression with the given replacement
     *
     * @param  string $regex
     * @param  string $replacement
     * @return String
     */
    function &replaceAll($regex, $replacement)
    {
        return new String(preg_replace($regex, $replacement, $this->_str));
    }

    /**
     * Replaces the first substring of this string that matches the given regular
     * expression with the given replacement
     *
     * @param  string $regex
     * @param  string $replacement
     * @return String
     */
    function &replaceFirst($regex, $replacement)
    {
        return new String(preg_replace($regex, $replacement, $this->_str, 1));
    }

    /**
     * The character sequence contained in this string is replaced by the reverse
     * of the sequence
     *
     * @return String
     */
    function &reverse()
    {
        return new String(strrev($this->_str));
    }

    /**
     * Splits this string around matches of the given regular expression
     *
     * @param  string $replacement
     * @param  int    $limit       optional
     * @return array
     */
    function split($regex, $limit=-1)
    {
        $substrings = preg_split($regex, $this->_str, $limit);
        foreach ($substrings as $index => $substring)
            $substrings[$index] = new String($substring);
        return $substrings;
    }

    /**
     * Tests if this string starts with the specified prefix
     *
     * @param  string $prefix
     * @param  int    $off optional
     * @return bool
     */
    function startsWith($prefix, $off=0)
    {
        return (strpos($this->_str), $prefix, $off) === 0);
        //return (strcmp(substr($this->_str, $off, $off + strlen($prefix)), $prefix == 0));
    }

    /**
     * Returns a new string that is a substring of this string
     *
     * @param  int $from
     * @param  int $to   optional
     * @return String
     */
    function &substring($from, $to=NULL)
    {
        $len = ($to === NULL ? strlen($this->_str) : $to) - $from;
        return new String(substr($this->_str, $from, $len));
    }

    /**
     * Converts all of the characters in this string to lower case
     *
     * @return String
     */
    function &toLowerCase()
    {
        return new String(strtolower($this->_str));
    }

    /**
     * Converts this string to sentence case (first letter of each sentence
     * capitalized)
     *
     * @return String
     */
    function &toSentenceCase()
    {
        return new String(ucfirst(strtolower($this->_str)));
    }

    /**
     * Returns the primitive string representation of this object
     *
     * @return string
     */
    function __toString()
    {
        return $this->_str;
    }

    /**
     * Converts this string to title case (first letter of each word
     * capitalized)
     *
     * @return String
     */
    function &toTitleCase()
    {
        return new String(ucwords(strtolower($this->_str)));
    }

    /**
     * Converts all of the characters in this string to upper case
     *
     * @return String
     */
    function &toUpperCase()
    {
        return new String(strtoupper($this->_str));
    }

    /**
     * Removes leading and trailing whitespace
     *
     * @return String
     */
    function &trim()
    {
        return new String(trim($this->_str));
    }

}

?>
