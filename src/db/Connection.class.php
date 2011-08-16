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
 *
 */
class Connection extends Object
{

    /**
     *
     *
     */
    var $_connection;

    /**
     *
     *
     */
    var $_dsn;

    /**
     *
     *
     * @param  string $dsn
     * @return void
     */
    function Connection($dsn)
    {
        $GLOBALS['ADODB_COUNTRECS']  = FALSE;
        $GLOBALS['ADODB_FETCH_MODE'] = 2; //ADODB_FETCH_ASSOC
        include_once(BOSC_EXT.'/adodb/adodb.inc.php');
        include_once(BOSC_EXT.'/adodb/adodb-errorhandler.inc.php');
        $this->_connection =& ADONewConnection($dsn);
        $this->_dsn = $dsn;
    }

    /**
     * Sets this connection's auto-commit mode to the given state.
     *
     * @param  bool $auto_commit
     * @return void
     */
    function autoCommit($auto_commit=TRUE)
    {
        $this->_connection->AutoCommit = (bool) $auto_commit;
    }

    /**
     * Starts a monitored transaction. As SQL statements are executed, they
     * will be monitored for SQL errors, and if any are detected, the
     * transaction is rolled back when complete() is called.
     *
     * @return void
     */
    function beginTransaction()
    {
        $this->_connection->StartTrans();
    }

    /**
     * Releases this Connection object's database link immediately instead of
     * waiting for them to be automatically released.
     *
     * @return void
     */
    function close()
    {
        $this->_connection->Close();
    }

    /**
     * Completes a monitored transaction called with beginTransaction(). This
     * function monitors for SQL errors, and will commit if no errors have
     * occured, otherwise it will rollback. Returns TRUE on commit, FALSE on
     * rollback.
     *
     * @param  bool $auto_complete
     * @return void
     */
    function completeTransaction($auto_complete=TRUE)
    {
        return $this->_connection->CompleteTrans($auto_complete);
    }

    /**
     * Creates a Statement object for sending SQL statements to the database.
     *
     * @param  string    $sql
     * @return Statement
     */
    function createStatement($sql)
    {
        return new Statement($sql, $this);
    }

    /**
     *
     *
     * @param  string    $sql
     * @param  array     $params
     * @param  int       $cache_lifetime
     * @param  Statement $statement
     * @return ResultSet
     */
    function execute($sql, $params, $cache_lifetime=NULL, &$statement)
    {
        $resultset =& $statement->getResultSet();
        if ($resultset !== NULL)
            $resultset->close();

        /*
            Execute() is the default way to run queries. You can use the
            low-level functions _Execute() and _query() to reduce query
            overhead. Both these functions share the same parameters as
            Execute().

            If you do not have any bind parameters or your database supports
            binding (without emulation), then you can call _Execute()
            directly. Calling this function bypasses bind emulation. Debugging
            is still supported in _Execute().

            If you do not require debugging facilities nor emulated binding,
            and do not require a recordset to be returned, then you can call
            _query. This is great for inserts, updates and deletes. Calling
            this function bypasses emulated binding, debugging, and recordset
            handling. Either the resultid, true or false are returned by
            _query().
        */
        $seconds = (int) $cache_lifetime;
        $max_rows = $statement->getMaxRows();
        $row_offset = $statement->getRowOffset();
        if ($max_rows || $row_offset)
        {
            if ($cache_lifetime !== NULL)
                $recordset =& $this->_connection->CacheSelectLimit($seconds, $sql, $max_rows, $row_offset);
            else
                $recordset =& $this->_connection->SelectLimit($sql, $max_rows, $row_offset);
        }
        else
        {
            if ($cache_lifetime !== NULL)
                $recordset =& $this->_connection->CacheExecute($seconds, $sql);
            elseif ($params)
                $recordset =& $this->_connection->Execute($sql, FALSE);
            elseif (preg_match('/^(DELETE|INSERT|UPDATE)/i', $sql))
                $recordset =& $this->_connection->_query($sql, FALSE);
            else
                $recordset =& $this->_connection->_Execute($sql, FALSE);
        }
        return new ResultSet($recordset, $statement, $this->_connection->Affected_Rows());
    }

    /**
     * Fail a transaction started with beginTransaction(). The rollback will
     * only occur when completeTransaction() is called.
     *
     * @return void
     */
    function failTransaction()
    {
        $this->_connection->FailTrans();
    }

    /**
     * Format the $date in the format the database accepts. This is used in
     * INSERT/UPDATE statements; for SELECT statements, use SQLDate. The $date
     * parameter can be a Unix integer timestamp or an ISO format Y-m-d. If
     * NULL or FALSE or '' is passed in, it will be converted to an SQL null.
     *
     * Returns the date as a quoted string.
     *
     * @param  string $date
     * @return bool
     */
    function formatDate($date, $field=NULL)
    {
        if ($field !== NULL)
            return $this->_connection->SQLDate($date, $field);
        return $this->_connection->DBDate($date);
    }

    /**
     * Format the timestamp $ts in the format the database accepts; this can
     * be a Unix integer timestamp or an ISO format Y-m-d H:i:s. If NULL or
     * FALSE or '' is passed in, it will be converted to an SQL null.
     *
     * Returns the date as a quoted string.
     *
     * @param  int  $timestamp
     * @return bool
     */
    function formatTimestamp($timestamp)
    {
        return $this->_connection->DBTimeStamp($timestamp);
    }

    /**
     * Generates a sequence number.
     *
     * @param  string $name
     * @return bool
     */
    function generateSequence($name)
    {
        return $this->_connection->GenID($name);
    }

    /**
     * Returns the datasource name (DSN) used to make this connection.
     *
     * @return void
     */
    function getDsn()
    {
        return $this->_dsn;
    }

    /**
     * Retrieves whether this Connection object is connected to a datasource.
     *
     * @return boolean
     */
    function isConnected()
    {
        return ($this->_connection->_connectionID !== FALSE);
    }

    /**
     * Creates a PreparedStatement object for sending parameterized SQL
     * statements to the database.
     *
     * @param  string            $sql
     * @return PreparedStatement
     */
    function prepare($sql)
    {
        return new PreparedStatement($sql, $this);
    }

    /**
     * Escapes special characters in a string for use in a SQL statement.
     *
     * @param  string $str
     * @return str
     */
    function quote($str)
    {
        return $this->_connection->qstr($str);
    }

}

/**
 *
 */
class ConnectionManager extends Object
{

    /**
     * Attempt to establish a database connection.
     *
     * @param  string     $dsn
     * @return Connection
     * @static
     */
    function &connect($dsn)
    {
        static $connections = array();
        $dsn = (strpos($dsn, '#') !== FALSE ? strrev(substr(strstr(strrev($dsn), '#'), 1)) : $dsn);
        if (! isset($connections[$dsn]))
            $connections[$dsn] = new Connection($dsn);
        return $connections[$dsn];
    }

}

/**
 *
 *
 */
class Statement extends Object
{

    /**
     *
     *
     */
    var $_connection;

    /**
     *
     *
     */
    var $_maxRows = 0;

    /**
     *
     *
     */
    var $_parameters = array();

    /**
     *
     *
     */
    var $_rowOffset = 0;

    /**
     *
     *
     */
    var $_resultSet = NULL;

    /**
     *
     *
     */
    var $_sql;

    /**
     *
     *
     * @param  string     $sql
     * @param  Connection $connection
     * @return void
     */
    function Statement($sql, &$connection)
    {
        $this->_connection =& $connection;
        $this->_sql = $sql;
    }

    /**
     * Executes the given SQL statement, which returns a single ResultSet
     * object.
     *
     * Executes the given SQL statement, which may be an INSERT, UPDATE, or
     * DELETE statement or an SQL statement that returns nothing, such as an SQL
     * DDL statement.
     *
     * @param  string    $sql
     * @param  int       $cache_lifetime
     * @return ResultSet
     */
    function &execute($cache_lifetime=NULL)
    {
        $sql = $this->_sql;
        if ($this->_parameters)
        {
            $parts = explode('?', $sql);
            $sql = $parts[0];
            for ($i = 1, $max = count($parts); $i < $max; $i++)
                $sql .=  $this->_parameters[$i].$parts[$i];
        }
        $this->_resultSet =& $this->_connection->execute($sql, array(), $cache_lifetime, $this);
        return $this->_resultSet;
    }

    /**
     * Retrieves the Connection object that produced this Statement object.
     *
     * @return Connection
     */
    function &getConnection()
    {
        return $this->_connection;
    }

    /**
     * Retrieves the maximum number of rows that a ResultSet object produced by
     * this Statement object can contain.
     *
     * Sets the limit for the maximum number of rows that any ResultSet object
     * can contain to the given number.
     *
     * @param  int $rows
     * @return int
     */
    function getMaxRows($rows=NULL)
    {
        return $this->_maxRows;
    }

    /**
     * Sets the designated parameter to the specified type.
     *
     * @param  int   $index
     * @return mixed
     */
    function getParameter($index)
    {
        $index = (int) $index;
        return (isset($this->_parameters[$index]) ? $this->_parameters[$index] : NULL);
    }

    /**
     * Retrieves the current ResultSet object.
     *
     * @return ResultSet
     */
    function &getResultSet()
    {
        return $this->_resultSet;
    }

    /**
     * Retrieves the offset of the initial row that a ResultSet object produced
     * by this Statement object can contain.
     *
     * @return int
     */
    function getRowOffset()
    {
        return $this->_rowOffset;
    }

    /**
     *
     *
     * @return string
     */
    function getSql()
    {
        return $this->_sql;
    }

    /**
     * Sets the limit for the maximum number of rows that any ResultSet object
     * can contain to the given number.
     *
     * @param  int $rows
     * @return void
     */
    function setMaxRows($rows)
    {
        $this->_maxRows = (int) $rows;
    }

    /**
     * Sets the designated parameter to the specified type.
     *
     * @param  int    $index
     * @param  mixed  $param
     * @param  string $type
     * @param  string $format_field
     * @return void
     */
    function setParameter($index, $param, $type, $format_field=NULL)
    {
        $type = strtolower($type);
        switch ($type)
        {
            case 'date':
                $this->_parameters[$index] = $this->_connection->formatDate($param, $format_field);
                break;
            case 'float':
                $this->_parameters[$index] = (float) $param;
                break;
            case 'int':
                $this->_parameters[$index] = (int) $param;
                break;
            case 'time':
                $this->_parameters[$index] = $this->_connection->formatDate($param);
                break;
            case 'timestamp':
                $this->_parameters[$index] = $this->_connection->formatTimestamp($param);
                break;
            case 'string':
            case 'text':
            default:
                $this->_parameters[$index] = $this->_connection->quote($param);
                break;
        }
    }

    /**
     *
     *
     * @param  int  $offset
     * @return void
     */
    function setRowOffset($offset)
    {
        $this->_rowOffset = (int) $offset;
    }

    /**
     *
     *
     * @param  string $sql
     * @return void
     */
    function setSql($sql)
    {
        $this->_sql = $sql;
    }

}

/**
 *
 *
 */
class PreparedStatement extends Object
{

    /**
     *
     *
     */
    var $_resultSet;

    /**
     *
     *
     */
    var $_statement;

    /**
     *
     *
     * @param  string     $sql
     * @param  Connection $connection
     * @return void
     */
    function PreparedStatement($sql, &$connection)
    {
        $this->_statement = new Statement($sql, $connection);
    }

    /**
     * Executes the SQL query in this PreparedStatement object and returns the
     * ResultSet object generated by the query.
     *
     * Executes the SQL statement in this PreparedStatement object, which must
     * be an SQL INSERT, UPDATE or DELETE statement; or an SQL statement that
     * returns nothing, such as a DDL statement.
     *
     * @param  int       $cache_lifetime
     * @return ResultSet
     */
    function &execute($cache_lifetime=NULL)
    {
        $connection =& $this->_statement->connection();
        $this->_resultSet =& $connection->execute($this->_statement->sql(), $this->_parameters, $cache_lifetime, $this);
        return $this->_resultSet;
    }

    /**
     * Retrieves the Connection object that produced this Statement object.
     *
     * @return Connection
     */
    function &getConnection()
    {
        return $this->_statement->connection();
    }

    /**
     * Retrieves the current ResultSet object.
     *
     * @return ResultSet
     */
    function &getResultSet()
    {
        return $this->_resultSet;
    }

    /**
     * Sets the designated parameter to the specified type.
     *
     * @param  int  $index
     * @return void
     */
    function setParameter($index, $param=NULL, $type=NULL, $format_field=NULL)
    {
        return $this->_statement->setParameter($index, $param, $type, $format_field);
    }

    /**
     *
     *
     * @param  string $sql
     * @return string
     */
    function getSql($sql=NULL)
    {
        return $this->_statement->sql();
    }

}

/**
 *
 *
 */
class ResultSet extends Object
{

    /**
     *
     *
     */
    var $_affectedRows;

    /**
     *
     *
     */
    var $_cursorAtStart = TRUE;

    /**
     *
     *
     */
    var $_recordSet;

    /**
     *
     *
     */
    var $_statement;

    /**
     *
     *
     * @param  ADORecordSet $recordset
     * @param  Statement    $statement
     * @return void
     */
    function ResultSet(&$recordset, &$statement, $affected_rows)
    {
        $this->_affectedRows = $affected_rows;
        $this->_recordSet =& $recordset;
        $this->_statement =& $statement;
    }

    /**
     * Releases this ResultSet object's database resources immediately instead
     * of waiting for this to happen when it is automatically closed.
     *
     * @return void
     */
    function close()
    {
        if ($this->_recordSet !== NULL)
            $this->_recordSet->Close();
    }

    /**
     * Returns the number of rows affected by this ResultSet object's Statement.
     *
     * @return int
     */
    function getAffectedRows()
    {
        return $this->_affectedRows;
    }

    /**
     * Retrieves the value of the designated column in the current row of this
     * ResultSet object as the specified type.
     *
     * @param  string $field
     * @param  string $type
     * @param  string $format
     * @return string
     */
    function getField($field, $type=NULL, $format=NULL)
    {
        if (! is_array($this->_recordSet->fields) || ! isset($this->_recordSet->fields[$field]))
            return NULL;

        $value = $this->_recordSet->fields[$field];
        $type = strtolower($type);
        switch ($type)
        {
            case 'date':
                $format = ($format ? $format : 'Y-m-d');
                $value = $this->_recordSet->UserDate($value, $format);
                break;
            case 'float':
                $value = (float) $value;
                break;
            case 'int':
                $value = (int) $value;
                break;
            case 'time':
                $format = ($format ? $format : 'H:i:s');
                $value = $this->_recordSet->UserDate($value, $format);
                break;
            case 'timestamp':
                $value = $this->_recordSet->UnixTimeStamp($value);
                break;
            case 'string':
            case 'text':
            case NULL:
            default:
                break;
        }
        return $value;
    }

    /**
     * Returns the number of records contained in this ResultSet object.
     *
     * @return int
     */
    function getRecordCount()
    {
        return $this->_recordSet->RecordCount();
    }

    /**
     * Retrieves the current row number. The first row is 1 (not zero).
     *
     * @return int
     */
    function getRow()
    {
        return $this->_recordSet->CurrentRow() + 1;
    }

    /**
     * Returns the SQL statement used to generate this ResultSet object.
     *
     * @return string
     */
    function getSql()
    {
        return $this->_recordSet->sql;
    }

    /**
     * Retrieves the Statement object that produced this ResultSet object.
     *
     * @return Statement
     */
    function &getStatement()
    {
        return $this->_statement;
    }

    /**
     * Retrieves the current row number.
     *
     * @param  int  $row
     * @return void
     */
    function moveTo($row)
    {
        $this->_recordSet->Move($row);
    }

    /**
     * Moves the cursor down one row from its current position.
     *
     * @return bool
     */
    function next()
    {
        if ($this->_cursorAtStart)
        {
            $this->_cursorAtStart = FALSE;
            return (! $this->_recordSet->EOF);
        }
        return $this->_recordSet->MoveNext();
    }

}

?>
