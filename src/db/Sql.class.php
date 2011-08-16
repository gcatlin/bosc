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
 * @subpackage sql
 */
if (! defined('BOSC'))
    require_once(realpath(dirname(__FILE__).'/../bosc.inc.php'));

/**
 *
 */
class SqlDelete extends Object
{

    /**
     *
     */
    var $_table;

    /**
     *
     */
    var $_where = array();

    /**
     *
     * @param  string $table
     * @return void
     */
    function SqlDelete($table)
    {
        $this->setTable($table);
    }

    /**
     *
     * @param  string $expr
     * @return void
     */
    function addWhere($expr)
    {
        $this->_where[] = $expr;
    }

    /**
     *
     * @param  string $table
     * @return void
     */
    function setTable($table)
    {
        $this->_table = $table;
    }

    /**
     *
     * @return string
     */
    function __toString()
    {
        return 'DELETE '.
            ($this->_table ? ' FROM ' .$this->_table : 'unspecified').
            ($this->_where ? ' WHERE '.implode(' AND ', $this->_where) : '');

    }

}

/**
 *
 */
class SqlInsert extends Object
{

    /**
     *
     */
    var $_columns = array();

    /**
     *
     */
    var $_table;

    /**
     *
     * @param  string $table
     * @return void
     */
    function SqlInsert($table)
    {
        $this->setTable($table);
    }

    /**
     *
     * @param  string $col
     * @param  string $val
     * @return void
     */
    function addColumn($col, $val)
    {
        $this->_columns[$col] = $val;
    }

    /**
     *
     * @param  string $table
     * @return void
     */
    function setTable($table)
    {
        $this->_table = $table;
    }

    /**
     *
     * @return string
     */
    function __toString()
    {
        return 'INSERT '.
            ($this->_table   ? ' INTO '.$this->_table : 'unspecified').
            ($this->_columns ? ' ('.implode(', ', array_keys($this->_columns)).') VALUES('.implode(', ', array_values($this->_columns)).')': '');

    }

}

/**
 *
 */
class SqlSelect extends Object
{

    /**
     *
     */
    var $_columns = array();

    /**
     *
     */
    var $_from    = array();

    /**
     *
     */
    var $_groupBy = array();

    /**
     *
     */
    var $_having  = array();

    /**
     *
     */
    var $_orderBy = array();

    /**
     *
     */
    var $_where   = array();

    /**
     *
     * @param  string $table
     * @return void
     */
    function SqlSelect($table=NULL)
    {
        $this->_from = (is_array($table) ? $table : array($table));
    }

    /**
     *
     * @param  string $col
     * @return void
     */
    function addColumn($col)
    {
        $this->_columns[] = $col;
    }

    /**
     *
     * @param  string $table
     * @return void
     */
    function addFrom($table)
    {
        $this->_from[] = $table;
    }

    /**
     *
     * @param  string $expr
     * @return void
     */
    function addHaving($expr)
    {
        $this->_having[] = $expr;
    }

    /**
     *
     * @param  string $expr
     * @return void
     */
    function addGroupBy($expr)
    {
        $this->_groupBy[] = $expr;
    }

    /**
     *
     * @param  string $expr
     * @return void
     */
    function addOrderBy($expr)
    {
        $this->_orderBy[] = $expr;
    }

    /**
     *
     * @param  string $where
     * @return void
     */
    function addWhere($where)
    {
        $this->_where[] = $where;
    }

    /**
     *
     * @return string
     */
    function __toString()
    {
        return 'SELECT '.
            ($this->_columns ? implode(',', $this->_columns) : '*').
            ($this->_from    ? ' FROM '    .implode(', ',    $this->_from)    : '').
            ($this->_where   ? ' WHERE '   .implode(' AND ', $this->_where)   : '').
            ($this->_groupBy ? ' GROUP BY '.implode(', ',    $this->_groupBy) : '').
            ($this->_having  ? ' HAVING'   .implode(' AND ', $this->_having)  : '').
            ($this->_orderBy ? ' ORDER BY '.implode(', ',    $this->_orderBy) : '');

    }

}

/**
 *
 */
class SqlUpdate extends Object
{

    /**
     *
     */
    var $_columns = array();

    /**
     *
     */
    var $_table;

    /**
     *
     */
    var $_where   = array();

    /**
     *
     * @param  string $table
     * @return void
     */
    function SqlUpdate($table)
    {
        $this->setTable($table);
    }

    /**
     *
     * @param  string $col
     * @param  string $op
     * @param  string $val
     * @return void
     */
    function addColumn($col, $val, $op='=')
    {
        $this->_columns[] = $col.$op.$val;
    }

    /**
     *
     * @param  string $where
     * @return void
     */
    function addWhere($where)
    {
        $this->_where[] = $where;
    }

    /**
     *
     * @param  string $table
     * @return void
     */
    function setTable($table)
    {
        $this->_table = $table;
    }

    /**
     *
     * @return string
     */
    function __toString()
    {
        return 'UPDATE '.
            ($this->_table   ? $this->_table : 'unspecified').
            ($this->_columns ? ' SET '  .implode(', ',    $this->_columns) : '').
            ($this->_where   ? ' WHERE '.implode(' AND ', $this->_where)   : '');

    }

}

?>