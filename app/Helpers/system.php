<?php
use External\Bar\Auth\LoginService;
use External\Baz\Auth\Authenticator;
use External\Foo\Auth\AuthWS;
use External\Foo\Exceptions\AuthenticationFailedException;


function chooseSystem($login, $password)
{
    $barClass = new LoginService();
    $bazClass = new Authenticator();
    $fooClass = new AuthWS();

    if ($barClass->login($login, $password) == true) {
        return 'BAR';
    } elseif (substr(strrchr(get_class($bazClass->auth($login, $password)), "\\"), 1) == 'Success') {
        return "BAZ";
    } else {
        try {
            $fooClass->authenticate($login, $password);
            return "FOO";
        } catch (AuthenticationFailedException $e) {
            return false;
        }
    }
}
