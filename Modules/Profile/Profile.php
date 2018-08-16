<?php
/**
 * Created by PhpStorm.
 * User: Jonas Schumacher
 * Date: 16.08.2018
 * Time: 12:46
 */

class Profile
{
    /** @var ProfileResponse */
    private $response;

    /** @var ProfileAdapter */
    private $adapter;

    public function __construct()
    {
        $this->response = new ProfileResponse();
        $this->adapter = new ProfileAdapter();
    }

    public function getUser()
    {
        session_start();

        if(!isset($_SESSION['email'])){
            $this->response->setStatusCode(Response::UNAUTHORIZED);
            $this->response->setMessage('User is not logged in');

            return $this->response;
        }

        /** @var UserEntity $user */
        $user = $this->adapter->getUserByEmail($_SESSION['email']);

        $this->response->setStatusCode(Response::SUCCESS);
        $this->response->setMessage('SUCCESS');
        $this->response->setId($user->id);
        $this->response->setUsername($user->username);

        return $this->response;
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $oldPassword
     * @param string $newPassword
     * @param string $passwordCheck
     * @return string
     */
    public function edit($username, $email, $oldPassword, $newPassword, $passwordCheck)
    {
        session_start();

        if(!isset($_SESSION['email'])){
            $this->response->setStatusCode(Response::UNAUTHORIZED);
            $this->response->setMessage('User is not logged in');

            return $this->response;
        }

        if(!isset($username) || !isset($email) || !isset($newPassword)){
            $this->response->setStatusCode(ProfileResponse::EMPTY_INPUT);
            $this->response->setMessage('Username, Email or the password is empty');

            return $this->response;
        }

        if($newPassword !== $passwordCheck){
            $this->response->setStatusCode(ProfileResponse::UNEQUAL_PASSWORD);
            $this->response->setMessage('The passwords are not equal');

            return $this->response;
        }

        $userCheck = $this->adapter->getUserByEmail($email);

        if($userCheck){
            $this->response->setStatusCode(ProfileResponse::EMAIL_ALREADY_REGISTERED);
            $this->response->setMessage('The email is already registered');

            return $this->response;
        }


        /** @var UserEntity $user */
        $user = $this->adapter->getUserByEmail($_SESSION['email']);

        //Password proof
        if(!password_verify($oldPassword, $user->password)){
            $this->response->setStatusCode(ProfileResponse::WRONG_PASSWORD);
            $this->response->setMessage('The old password is wrong');

            return $this->response;
        }

        //Input-validation and password-hashing
        $username = $this->validator($username);
        $email = $this->validator($email);
        $newPassword = $this->validator($newPassword);

        $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        //Updates the User-table
        $this->adapter->updateUser($username, $email, $newPassword);

        //Updates the Session-variable
        $_SESSION['email'] = $email;

        $this->response->setStatusCode(Response::SUCCESS);
        $this->response->setMessage('SUCCESS');

        return $this->response;
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