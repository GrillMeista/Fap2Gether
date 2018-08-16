<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 14.08.2018
 * Time: 19:08
 */


class Auth
{
    /** @var AuthResponse  */
    private $response;

    /** @var DBConnector  */
    private $db;

    /** @var QueryBox  */
    private $qb;

    public function __construct()
    {
        $this->response = new AuthResponse();
        $this->db       = new DBConnector();
        $this->qb       = new QueryBox();
    }

    /**
     * Register Action
     *
     * @param string $email
     * @param string $password
     * @param string $passwordCheck
     * @return AuthResponse
     */
    public function register($email, $password, $passwordCheck) : AuthResponse
    {
        //Checks if the passwords are equal
        if($password !== $passwordCheck){
            $this->response->setStatusCode(AuthResponse::UNEQUAL_PASSWORDS);
            $this->response->setMessage('The passwords are not equal');

            return $this->response;
        }

        $passwordCheck = "";

        //Checks if the email is valid
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $this->response->setStatusCode(AuthResponse::NO_EMAIL);
            $this->response->setMessage('The given value was not an email');

            return $this->response;
        }

        //Input-validation
        $email = $this->validate($email);
        $password = $this->validate($password);

        //Password-hashing
        $password = password_hash($password, PASSWORD_DEFAULT);

        //Build query to check if the user exists
        $this->qb->select( [UserModel::$id] );
        $this->qb->from( UserModel::$table );
        $this->qb->where(UserModel::$email, '=', $email);

        /** @var UserEntity $result */
        $result = $this->db->sendSingleReturn($this->qb->getQuery(), UserEntity::class);

        //Check if the user exists
        if($result){
            $this->response->setStatusCode(AuthResponse::USER_ALREADY_EXISTS);
            $this->response->setMessage('user already exists');

            return $this->response;
        }

        //Build query to insert out user in the database
        $this->qb->insertInto(UserModel::$table, [UserModel::$email, UserModel::$password], [$email, $password]);
        $this->db->sendVoid($this->qb->getQuery());

        $this->response->setStatusCode(Response::SUCCESS);
        $this->response->setMessage('successfully registered');

        return $this->response;
    }

    /**
     * Login Action
     *
     * @param string $email
     * @param string $password
     * @return AuthResponse
     */
    public function login($email, $password) : AuthResponse
    {
        session_start();

        //Checks if the email is valid
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $this->response->setStatusCode(AuthResponse::NO_EMAIL);
            $this->response->setMessage('The given value was not an email');

            return $this->response;
        }

        //Input-validation
        $email = $this->validate($email);
        $password = $this->validate($password);

        $this->qb->select([UserModel::$password]);
        $this->qb->from(UserModel::$table);
        $this->qb->where(UserModel::$email, '=', $email);

        /** @var UserEntity $result */
        $result = $this->db->sendSingleReturn($this->qb->getQuery(), UserEntity::class);

        //Checks if user exists
        if(!$result){
            $this->response->setStatusCode(AuthResponse::USER_NOT_FOUND);
            $this->response->setMessage('user not found');

            return $this->response;
        }

        if(!password_verify($password, $result->password)){
            $this->response->setStatusCode(AuthResponse::WRONG_PASSWORD);
            $this->response->setStatusCode('wrong password');

            return $this->response;
        }

        $_SESSION['email'] = $email;

        $this->response->setStatusCode(Response::SUCCESS);
        $this->response->setMessage('successfully logged in');

        return $this->response;
    }

    /**
     * @param string $input
     * @return string
     */
    private function validate($input) : string
    {
        $input = htmlspecialchars($input);
        $input = strip_tags($input);
        $input = stripcslashes($input);
        $input = trim($input);

        return $input;
    }

}