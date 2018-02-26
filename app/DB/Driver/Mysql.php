<?php

namespace DB\Driver;

use DB\Handler;

class Mysql extends Handler implements DriverTemplate
{
    protected $handle;
    protected static $conn;
    public function __construct()
    {
        if(!isset(self::$conn)) self::$conn = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_DB']);
        $this->handle = self::$conn;
        if (mysqli_connect_errno()) {
            throw new \ErrorException("Connect failed: ". mysqli_connect_error());
        }
    }

    public function get(string $tablename)
    {
       if ($result = $this->handle->query("SELECT * FROM $tablename"))
       {
           return $result->fetch_all(MYSQLI_ASSOC);
       } else {
           throw new \ErrorException("Prepare failed: (" . $this->handle->errno . ") " . $this->handle->error);
       }
    }

    public function where (string $tablename, array $search, bool $returnSet = false)
    {
       if ($result = $this->handle->query("SELECT * FROM $tablename WHERE $search[0] = '$search[1]'"))
       {
           if ($returnSet) return $result;
           else return $result->fetch_assoc();
       } else {
           throw new \ErrorException("Prepare failed: (" . $this->handle->errno . ") " . $this->handle->error);
       }
    }

    public function has(string $tablename, array $search)
    {
        if ($result = $this->handle->query("SELECT * FROM $tablename WHERE $search[0] = '$search[1]'"))
        {
            if(count($result->fetch_assoc()) > 0) return true;
            else return false;
        } else {
            throw new \ErrorException("Prepare failed: (" . $this->handle->errno . ") " . $this->handle->error);
        }
    }

    public function insert (string $tablename, array $vars)
    {
        $stmtvars = '(';
        $types = '';
        for ($i = 0; $i < count($vars); $i++) {
            $stmtvars = $stmtvars . "'$vars[$i]'";
            if ($i + 1 < count($vars)) $stmtvars = $stmtvars . ', ';
            else $stmtvars = $stmtvars . ')';
            switch (gettype($vars[$i]))
            {
                case 'string':
                    $types = $types . 's';
                    break;
                case 'integer':
                case 'boolean':
                    $types = $types . 'i';
                    break;
                case 'double':
                    $types = $types . 'd';
                    break;
                default:
                    throw new \ErrorException('Wrong Datatype in arguments: expected (string, integer, double, boolean), got ' . gettype($vars[$i]));
            }
        }
        if($this->handle->query("INSERT INTO $tablename VALUES $stmtvars"))
        {
            return true;
        } else {
            throw new \ErrorException("Prepare failed: (" . $this->handle->errno . ") " . $this->handle->error);
        }
    }

    public function delete (string $tablename, array $search)
    {
        $query = "DELETE FROM $tablename WHERE $search[0] = '$search[1]'";
        if (count($search) === 3) $query = "DELETE FROM $tablename WHERE $search[0] $search[1] '$search[2]'";
        if ($result = $this->handle->query($query))
        {
            return true;
        } else {
            throw new \ErrorException("Prepare failed: (" . $this->handle->errno . ") " . $this->handle->error);
        }
    }

    public function update(string $tablename, array $vars, array $search)
    {
        $stmtvars = '';
        $types = '';
        $i = 0;
        foreach ($vars as $item=>$value)
        {
            $stmtvars = $stmtvars . "$item = '$value'";
            if ($i + 1 < count($vars)) $stmtvars = $stmtvars . ', ';
            $i++;
        }
        if($this->handle->query("UPDATE $tablename SET $stmtvars WHERE $search[0] = '$search[1]'"))
        {
            return true;
        } else {
            throw new \ErrorException("Prepare failed: (" . $this->handle->errno . ") " . $this->handle->error);
        }
    }

    public function new (string $tablename, array $vars)
    {
        $stmtvars = '(';
        $types = '';
        for ($i = 0; $i < count($vars); $i++)
        {
            $stmtvars = $stmtvars . $vars[$i];
            if ($i + 1  < count($vars)) $stmtvars = $stmtvars . ', ';
            else $stmtvars = $stmtvars . ')';
            $types = $types . 's';
        }
        if($this->handle->query("CREATE TABLE IF NOT EXISTS $tablename $stmtvars"))
        {
            return true;
        } else {
            throw new \ErrorException("Query failed: (" . $this->handle->errno . ") " . $this->handle->error);
        }
    }

    public function drop (string $tablename)
    {
        if($this->handle->query("DROP TABLE IF EXISTS $tablename"))
        {
            return true;
        } else {
            throw new \ErrorException("Query failed: (" . $this->handle->errno . ") " . $this->handle->error);
        }
    }

    public function last_error ()
    {
        return $this->handle->error;
    }
}
