<?php

class AuthResponse extends Response
{
    const UNEQUAL_PASSWORDS     = 1001;
    const WRONG_PASSWORD        = 1002;
    const NO_PASSWORD           = 1003;
    const NO_EMAIL              = 1004;
    const NOT_AN_EMAIL          = 1005;
    const USER_ALREADY_EXISTS   = 1006;
    const UNKNOWN_USER          = 1008;
    const NO_USERNAME           = 1009;
}