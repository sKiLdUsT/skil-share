<?php

namespace DB;

class File implements Facette
{
    public $id;
    public $uid;
    public $name;
    public $store;
    public $type;
    public $date;
    public $disabled;
    private $db;
    private $new;
    private $shallow = [];

    public function __construct(array $search = null)
    {
        $this->db = Handler::create();
        if(isset($search))
        {
            if ($search && $file = $this->db->where('files', $search))
            {
                foreach ($file as $item=>$value)
                {
                    $this->$item = $value;
                    $this->shallow[$item] = $value;
                }
            } else {
                throw new \TypeError("File not found: $search[1]");
            }
        } else {
            $this->disabled = false;
            $this->new = true;
        }
    }

    public function save()
    {
        if ($this->new)
        {
            $this->date = time();
            $this->id = $this->findId();
            $this->db->insert('files', [$this->id, $this->uid, $this->name, $this->store, $this->type, $this->date, $this->disabled]);
        } else {
            $compare = [];
            foreach($this->shallow as $key=>$value)
            {
                $compare[$key] = $this->$key;
            }
            if (count($compare) > 0) $this->db->update('files', array_diff_assoc($compare, $this->shallow), ['id', $this->id]);
        }
    }
    public function last_error()
    {
        return $this->db->last_error();
    }

    protected function findId()
    {
        $id = bin2hex(random_bytes(3));
        if ($this->db->has('files', ['id', $id]))
        {
            $this->findId();
        } else {
            return $id;
        }
    }
}