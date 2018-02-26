<?php

namespace Core\Http;

use DB\User;

class AuthHandler extends Handler{
    public function login (\stdClass $request) {
        if (!isset($request->input->token) || $request->input->token !== $_SESSION['token'])
        {
            http_response_code(401);
            (new SiteHandler())->login($request, 'CSRF Token Mismatch!');
            return;
        }
        if (count(array_filter((array) $request->input)) < 3)
        {
            http_response_code(400);
            $missing = join(', ', array_keys(array_diff((array) $request->input ?: [], array_filter((array) $request->input))));
            (new SiteHandler())->login($request, "Missing $missing");
            return;
        }
        if (isset($request->input->remember_me))
        {
            session_write_close();
            session_set_cookie_params((60*60*24)*31);
            session_start();
        }
        try {
            $user = new User(['email',  $request->input->email]);
            if (password_verify($request->input->password, $user->password))
            {
                $_SESSION['auth'] = true;
                $_SESSION['uid'] = $user->id;
                header('Location: /');
                return;
            } else {
                http_response_code(401);
                (new SiteHandler())->login($request, "Wrong credentials");
                return;
            }
        } catch (\TypeError $e)
        {
            http_response_code(401);
            (new SiteHandler())->login($request, "Wrong credentials");
            return;
        }
    }
    public function logout (\stdClass $request) {
        session_destroy();
        header('Location: /');
    }
    public function register(\stdClass $request) {
        if (!isset($request->input->token) || $request->input->token !== $_SESSION['token'])
        {
            http_response_code(401);
            (new SiteHandler())->register($request, 'CSRF Token Mismatch!');
            return;
        }
        if (count(array_filter((array) $request->input)) !== 5)
        {
            http_response_code(400);
            $missing = join(', ', array_keys(array_diff((array) $request->input ?: [], array_filter((array) $request->input))));
            (new SiteHandler())->register($request, "Missing $missing");
            return;
        }
        $result = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, stream_context_create(['http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query(['secret' => $_ENV['RECAPTCHA_SECRET'], 'response' => $request->input->{'g-recaptcha-response'}])]])));
        if ($result->success === true) {
            $user = new User();
            $user->username = $request->input->username;
            $user->email = $request->input->email;
            $user->password = password_hash($request->input->password, PASSWORD_BCRYPT);
            $user->sharex_key = bin2hex(openssl_random_pseudo_bytes(64));
            try {
                if ($user = $user->save())
                {
                    $_SESSION['auth'] = true;
                    $_SESSION['uid'] = $user->id;
                    header('Location: /');
                } else {
                    http_response_code(400);
                    (new SiteHandler())->register($request, $user->last_error());
                }
            } catch (\Exception $exception)
            {
                http_response_code(400);
                (new SiteHandler())->register($request, 'User already present or other error.');
            }
        } else {
            http_response_code(400);
            (new SiteHandler())->register($request, $result->{'error-codes'}[0]);
        }
    }
}