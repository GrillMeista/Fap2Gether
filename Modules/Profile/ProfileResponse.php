<?php

class ProfileResponse extends Response
{
    const UNEQUAL_PASSWORD = 1011;
    const WRONG_PASSWORD = 1012;
    const EMPTY_INPUT = 1013;
    const EMAIL_ALREADY_REGISTERED = 1014;

    public function setId($id)
    {
        $this->data['id'] = $id;
    }

    public function setUsername($username)
    {
        $this->data['username'] = $username;
        $this->data['email'] = $_SESSION['email'];
    }

}