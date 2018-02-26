<?php

require(__DIR__ . '/vendor/autoload.php');
use DB\File;
use DB\User;
use Dotenv\Dotenv;

$dotenv = new Dotenv(__DIR__);
$dotenv->load();

$olddb = readline('Old Database Name: ');
$oldstorage = readline('Old Storage Path: ');
$moveFiles = preg_match('/(y|yes)/', readline('Move old files over?'));

$olddb = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $olddb);

echo "Working...\n";

$users = $olddb->query('SELECT * FROM users');
while($user = $users->fetch_assoc())
{
    $user = (object) $user;
    $newUser = new User();
    $newUser->id = $user->id;
    $newUser->username = $user->user;
    $newUser->email = $user->user . '@example.com';
    $newUser->password = $user->password;
    $newUser->sharex_key = bin2hex(openssl_random_pseudo_bytes(64));
    $newUser->save();
}
$files = $olddb->query('SELECT * FROM files');
while($file = $files->fetch_assoc())
{
    $file = (object) $file;
    $newFile = new File();
    $newFile->id = $file->fileID;
    $newFile->uid = $file->uid;
    $newFile->store = explode('.', $file->name)[0];
    $newFile->name = $file->oldName;
    if (file_exists($oldstorage . '/' . $file->name)) {
        $mime = mime_content_type($oldstorage . '/' . $file->name);
        switch(true)
        {
            case preg_match('/image\/.+/', $mime):
                $file->type = 1;
                break;
            case preg_match('/video\/.+/', $mime):
                $file->type = 2;
                break;
            case preg_match('/audio\/.+/', $mime):
                $file->type = 3;
                break;
            case preg_match('/text\/.+/', $mime):
                $file->type = 4;
                break;
            case preg_match('/application\/pdf/', $mime):
                $file->type = 5;
                break;
            default:
                $file->type = 0;
                break;
        }
        if ($moveFiles) {
            if(!rename($oldstorage . '/' . $file->name, __DIR__ . '/storage/' . $newFile->store)) echo 'Couldn\'t move ' . $oldstorage . '/' . $file->name;
        }
    } else {
        $newFile->type = 0 ;
    }
    $newFile->save();
}

echo "Done!\n";

exit();