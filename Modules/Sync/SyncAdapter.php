<?php

class SyncAdapter
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

    public function updateTime($time, $lobbykey)
    {
        $this->queryBox->update(LobbyModel::$table);
        $this->queryBox->set([
            LobbyModel::$time => $time,
        ]);
        $this->queryBox->where(LobbyModel::$lobbykey, '=', $lobbykey);

        $this->database->sendVoid($this->queryBox->getQuery());
    }

    public function getLobbyByKey($lobbykey)
    {
        $this->queryBox->select([LobbyModel::$time]);
        $this->queryBox->from(LobbyModel::$table);
        $this->queryBox->where(LobbyModel::$lobbykey, '=', $lobbykey);

        return $this->database->sendSingleReturn($this->queryBox->getQuery(), LobbyEntity::class);
    }
}