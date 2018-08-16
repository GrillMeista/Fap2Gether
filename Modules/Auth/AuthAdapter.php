<?php

class AuthAdapter
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

    public function getUserByEmail($email)
    {
        $this->queryBox->select([UserModel::$id, UserModel::$password, UserModel::$admin]);
        $this->queryBox->from(UserModel::$table);
        $this->queryBox->where(UserModel::$email, "=", $email);

        return $this->database->sendSingleReturn($this->queryBox->getQuery(), UserEntity::class);
    }

    public function insertUser($username, $email, $password)
    {
        $this->queryBox->insertInto(UserModel::$table, [ UserModel::$username, UserModel::$email, UserModel::$password ], [$username, $email, $password]);

        $this->database->sendVoid($this->queryBox->getQuery());
    }


}