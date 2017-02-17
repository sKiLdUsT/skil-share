<?php

function connectDB($config){
$mysqli = new mysqli($config['host'], $config['name'], $config['password'], $config['db']) or die("Failed to connect to DB. Check config.");
return($mysqli);
}
function checkUser(mysqli $mysqli, array $user){
    $dataIn = $mysqli->query("SELECT user, password, id FROM users WHERE user = '" . escapeshellcmd($user['name']) . "'");
    $dataOut = $dataIn->fetch_all(MYSQLI_ASSOC);
    if (password_verify($user['pass'], $dataOut['0']['password'])) {
        return($dataOut['0']['id']);
    } else {
        return($dataOut);
        return(FALSE);
    }
}

function registerID($name, $oldName, $uid, mysqli $mysqli){
    $fileID = substr(uniqid(), -6);
    if (!$dataIn = $mysqli->query("INSERT INTO files (name,oldName,uid,fileID) VALUES ('".$name."','".$oldName."','".$uid."','".$fileID."')")){
        echo $mysqli->error;
        return(FALSE);
    }else{
        return($fileID);
    }
}

function registerUser($username, $pass_hash, mysqli $mysqli)
{
    if (!$dataIn = $mysqli->query("INSERT INTO users (user, password) VALUES ('".$username."','".$pass_hash."')")){
        echo $mysqli->error;
        return(FALSE);
    }else{
        return(TRUE);
    }
}

function queryID($id, mysqli $mysqli){
    if($dataIn = $mysqli->query("SELECT name,oldName,uid,fileID FROM files WHERE fileID = '" . $id . "'")){
        $dataOut =  $dataIn->fetch_all(MYSQLI_ASSOC);
        return($dataOut);
    }else{
        die(FALSE);
    }
}