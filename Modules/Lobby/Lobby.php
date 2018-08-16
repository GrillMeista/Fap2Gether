<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 15.08.2018
 * Time: 19:51
 */

class Lobby
{
    private $response;

    private $qb;

    private $db;

    public function __construct()
    {
        $this->response = new LobbyResponse();
        $this->qb = new QueryBox();
        $this->db = new DBConnector();
    }

    public function create()
    {
        session_start();

        if(!isset($_SESSION['email'])){
            $this->response->setStatusCode(Response::UNAUTHORIZED);
            $this->response->setMessage('Not logged in');

            return $this->response;
        }

        $this->qb->select([UserModel::$id]);
        $this->qb->from(UserModel::$table);
        $this->qb->where(UserModel::$email, '=', $_SESSION['email']);

        /** @var LobbyEntity $result */
        $result = $this->db->sendSingleReturn($this->qb->getQuery(), LobbyEntity::class);

        $key = uniqid();

        $this->qb->insertInto(LobbyModel::$table, [LobbyModel::$admin, LobbyModel::$lobbykey], [$result->id, $key]);

        $this->db->sendVoid($this->qb->getQuery());

        $this->response->setStatusCode(Response::SUCCESS);
        $this->response->setMessage('SUCCESS');

        return $this->response;
    }
}