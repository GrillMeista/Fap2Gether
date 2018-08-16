<?php

class ProfileAdapter
{
    /** @var QueryBox */
    private $queryBox;

    /** @var DBConnector */
    private $database;

    public function __construct()
    {
        $this->queryBox = new QueryBox();
        $this->database = new DBConnector();
    }

    /**
     * @param string $email
     * @return UserEntity
     */
    public function getUserByEmail($email)
    {
        $this->queryBox->select([UserModel::$id, UserModel::$username, UserModel::$password]);
        $this->queryBox->from(UserModel::$table);
        $this->queryBox->where(UserModel::$email, '=', $email);

        return $this->database->sendSingleReturn($this->queryBox->getQuery(), UserEntity::class);
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     */
    public function updateUser($username, $email, $password)
    {
        $this->queryBox->update(UserModel::$table);
        $this->queryBox->set([
            UserModel::$username => $username,
            UserModel::$email => $email,
            UserModel::$password => $password,
        ]);
        $this->queryBox->where(UserModel::$email, '=', $_SESSION['email']);

        $this->database->sendVoid($this->queryBox->getQuery());
    }
}