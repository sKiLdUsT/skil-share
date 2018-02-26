<?php

namespace DB;

class FileBag implements \Iterator, \Countable, \ArrayAccess
{
    private $db;
    private $position = 0;
    private $array = [];
    private $count = 0;

    public function __construct(array $search = null)
    {
        $this->position = 0;
        $this->count = 0;
        $this->array = [];
        $this->db = Handler::create();
        if(isset($search))
        {
            if ($search && $file = $this->db->where('files', $search, true))
            {
                while ($row = $file->fetch_assoc()) {
                    if (!boolval($row['disabled']))
                    {
                        $cFile = new File();
                        foreach($row as $key=>$value)
                        {
                            $cFile->$key = $value;
                        }
                        $this->array[] = $cFile;
                        $this->count++;
                    }
                }
            } else {
                throw new \TypeError("No files found: $search[1]");
            }
        } else {
            throw new \TypeError("No input given");
        }
    }

    public function rewind() {
        $this->position = 0;
    }

    public function current() {
        return $this->array[$this->position];
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    public function valid() {
        return isset($this->array[$this->position]);
    }

    public function count() {
        return $this->count;
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->array[] = $value;
        } else {
            $this->array[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->array[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->array[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->array[$offset]) ? $this->array[$offset] : null;
    }
}