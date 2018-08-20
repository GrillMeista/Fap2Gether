<?php

class LobbyAdapter
{
    private $queryBox;

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
        $this->queryBox->select([UserModel::$id]);
        $this->queryBox->from(UserModel::$table);
        $this->queryBox->where(UserModel::$email, '=', $email);

        return $this->database->sendSingleReturn($this->queryBox->getQuery(), UserEntity::class);
    }

    /**
     * @param string $id
     * @return UserEntity
     */
    public function getUserById($id)
    {
        $this->queryBox->select([UserModel::$username]);
        $this->queryBox->from(UserModel::$table);
        $this->queryBox->where(UserModel::$id, '=', $id);

        return $this->database->sendSingleReturn($this->queryBox->getQuery(), UserEntity::class);
    }

    /**
     * @param string $id
     * @param string $key
     */
    public function createLobby($id, $key)
    {
        $this->queryBox->insertInto(LobbyModel::$table, [LobbyModel::$admin, LobbyModel::$lobbykey], [$id, $key]);

        $this->database->sendVoid($this->queryBox->getQuery());
    }

    /**
     * @param string $lobbykey
     * @return LobbyEntity
     */
    public function getLobbyByKey($lobbykey)
    {
        $this->queryBox->select([LobbyModel::$id, LobbyModel::$admin, LobbyModel::$members, LobbyModel::$time]);
        $this->queryBox->from(LobbyModel::$table);
        $this->queryBox->where(LobbyModel::$lobbykey, '=', $lobbykey);

        return $this->database->sendSingleReturn($this->queryBox->getQuery(), LobbyEntity::class);
    }

    /**
     * @param string $members
     * @param string $lobbykey
     */
    public function updateMembers($members, $lobbykey)
    {
        $this->queryBox->update(LobbyModel::$table);
        $this->queryBox->set([
            LobbyModel::$members => $members
        ]);
        $this->queryBox->where(LobbyModel::$lobbykey, '=', $lobbykey);

        $this->database->sendVoid($this->queryBox->getQuery());
    }

    public function getLobbyMemberList($memberIds)
    {
        $returnArray = [];

        foreach ($memberIds as $value){
            array_push($returnArray, $this->getUserById($value)->username);
        }

        return $returnArray;
    }
}