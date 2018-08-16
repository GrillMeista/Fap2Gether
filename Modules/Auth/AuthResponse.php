<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 14.08.2018
 * Time: 19:10
 */

class AuthResponse extends Response
{
    const UNEQUAL_PASSWORDS     = 1001;
    const WRONG_PASSWORD        = 1002;
    const NO_PASSWORD           = 1003;
    const NO_EMAIL              = 1004;
    const NOT_AN_EMAIL          = 1005;
    const USER_ALREADY_EXISTS   = 1006;
    const USER_NOT_FOUND        = 1007;
}