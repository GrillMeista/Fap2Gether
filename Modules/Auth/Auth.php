<?php

class Auth
{

    /** @var AuthResponse */
    private $response;

    /** @var AuthAdapter */
    private $adapter;

    public function __construct()
    {
        $this->response = new AuthResponse();
    }

    public function register($username, $email, $password, $passwordCheck)
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            $this->response->setStatusCode(Response::METHOD_NOT_ALLOWED);
            $this->response->setMessage('This Service is POST');

            return $this->response;
        }

        if(!isset($username)){
            $this->response->setStatusCode(AuthResponse::NO_USERNAME);
            $this->response->setMessage('No username');

            return $this->response;
        }

        if(!isset($email)){
            $this->response->setStatusCode(AuthResponse::NO_EMAIL);
            $this->response->setMessage('No email');

            return $this->response;
        }

        //Comparing password and password_check
        if($password !== $passwordCheck){
            $this->response->setStatusCode(AuthResponse::UNEQUAL_PASSWORDS);
            $this->response->setMessage('Unequal passwords');

            return $this->response;
        }

        //Checks if the email is valid
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $this->response->setStatusCode(AuthResponse::NO_EMAIL);
            $this->response->setMessage('Email is not in the correct format');

            return $this->response;
        }

        //Validation of the used parameters
        $username = $this->validator($username);
        $email    = $this->validator($email);
        $password = $this->validator($password);

        //Password-cryption
        $password = password_hash($password, PASSWORD_DEFAULT);

        $this->getAdapter();

        /** @var UserEntity $result */
        $result = $this->adapter->getUserByEmail($email);

        //Check if the user exists
        if($result){

            $this->response->setStatusCode(AuthResponse::USER_ALREADY_EXISTS);
            $this->response->setMessage('User already exists');

            return $this->response;
        }

        $this->adapter->insertUser($username, $email, $password);

        //Returns SUCCESS status
        $this->response->setStatusCode(Response::SUCCESS);
        $this->response->setMessage('Successfully registered');

        return $this->response;
    }

    public function login($email, $password)
    {
        session_start();

        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            $this->response->setStatusCode(Response::METHOD_NOT_ALLOWED);
            $this->response->setMessage('This Service is POST');

            return $this->response;
        }

        //Check if the email includes ' @ ' and ' . '
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $this->response->setStatusCode(AuthResponse::NO_EMAIL);
            $this->response->setMessage('Email is not in the correct format');

            return $this->response;
        }

        //Validation of the used parameters
        $email    = $this->validator($email);
        $password = $this->validator($password);

        $this->getAdapter();
        /** @var UserEntity $result */
        $result = $this->adapter->getUserByEmail($email);

        //Checks if the user exists
        if(!$result){
            $this->response->setStatusCode(AuthResponse::UNKNOWN_USER);
            $this->response->setMessage('No user to that email found');

            return $this->response;
        }

        if(!password_verify($password, $result->password)){
            $this->response->setStatusCode(AuthResponse::WRONG_PASSWORD);
            $this->response->setMessage('Wrong Password');

            return $this->response;
        }

        $_SESSION['email'] = $email;
        $_SESSION['admin'] = $result->admin;

        $this->response->setStatusCode(Response::SUCCESS);
        $this->response->setMessage('Successfully logged in');

        return $this->response;
    }

    private function getAdapter()
    {
        $this->adapter = ($this->adapter == null) ? new AuthAdapter() : $this->adapter;
    }

    private function validator($input)
    {
        $input = htmlspecialchars($input);
        $input = strip_tags($input);
        $input = stripslashes($input);
        $input = trim($input);

        return $input;
    }
}