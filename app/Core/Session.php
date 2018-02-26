<?php

namespace Core;

use DB\Handler;

class Session implements \SessionHandlerInterface {

    private $db;

    public function open($save_path, $name)
    {
        if ($this->db = Handler::create()) return true;
        else return false;
    }
    public function close()
    {
        $this->db = null;
        return true;
    }
    public function read($session_id)
    {
       if($this->db->has('sessions', ['id', $session_id]))
       {
           return $this->db->where('sessions', ['id', $session_id])['data'];
       } else {
           $this->db->insert('sessions', [$session_id, time(), '']);
           return '';
       }
    }
    public function write($session_id, $session_data)
    {
       return $this->db->update('sessions', ['data' => $session_data, 'access' => time()], ['id', $session_id]);
    }
    public function destroy($session_id)
    {
        return $this->db->delete('sessions', ['id', $session_id]);
    }
    public function gc($maxlifetime)
    {
       return $this->db->delete('sessions', ['access', '<', time() - $maxlifetime]);
    }
}