<?php

namespace DB;

class User implements Facette
{
    public $id;
    public $username;
    public $email;
    public $password;
    public $sharex_key;
    public $date;
    private $db;
    private $changed = [];
    private $new = false;

    public function __construct(array $search = null)
    {
        $this->db = Handler::create();
        if(isset($search))
        {
            if ($search && $user = $this->db->where('users', $search))
            {
                foreach ($user as $item=>$value)
                {
                    $this->$item = $value;
                }
            } else {
                throw new \TypeError("User not found: $search[1]");
            }
        } else {
            $this->new = true;
        }
    }

    public function __get(string $name)
    {
        if (isset($this->$name)) return $this->$name;
        else return null;
    }

    public function __set(string $name, string $value)
    {
        if ($this->new)
        {
            switch ($name)
            {
                case 'username':
                case 'email':
                case 'id':
                case 'password':
                case 'sharex_key':
                    $this->$name = $value;
                    break;
                default:
                    throw new \TypeError("Not part of user object: $name");
            }
        }
        else {
            switch ($name)
            {
                case 'username':
                case 'email':
                case 'sharex_key':
                    $this->$name = $value;
                    break;
                case 'id':
                case 'password':
                    throw new \Exception("Not allowed: $name");
                    break;
                default:
                    break;
            }
            array_push($this->changed, $name);
        }
    }

    public function save()
    {
        if ($this->new)
        {
            $this->date = time();
            $this->db->insert('users', [isset($this->id) ? $this->id : '', $this->username, $this->email, $this->password, $this->sharex_key, $this->date]);
            return new self(['username', $this->username]);
        } else {
            if (count($this->changed) === 0) return true;
        }
    }

    public function last_error()
    {
        return $this->db->last_error();
    }
}