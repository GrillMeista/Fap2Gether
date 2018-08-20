<?php

class Sync
{
    /** @var SyncResponse  */
    private $response;

    /** @var SyncAdapter */
    private $adapter;

    public function __construct()
    {
        $this->response = new SyncResponse();
    }

    public function setTime($time)
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            $this->response->setStatusCode(Response::METHOD_NOT_ALLOWED);
            $this->response->setMessage('This Service is POST');

            return $this->response;
        }

        if(!isset($_SESSION['lobbykey'])){
            $this->response->setStatusCode(Response::UNAUTHORIZED);
            $this->response->setMessage('No lobbykey found');

            return $this->response;
        }

        // Parameter-validation
        $time = $this->validate($time);

        //Gets an adapter
        $this->getAdapter();

        //Updates the current time
        $this->adapter->updateTime($time, $_SESSION['lobbykey']);


        $this->response->setStatusCode(Response::SUCCESS);
        $this->response->setMessage('SUCCESS');

        return $this->response;
    }

    public function getTime()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'GET'){
            $this->response->setStatusCode(Response::METHOD_NOT_ALLOWED);
            $this->response->setMessage('This Service is GET');

            return $this->response;
        }

        if(!isset($_SESSION['lobbykey'])){
            $this->response->setStatusCode(Response::UNAUTHORIZED);
            $this->response->setMessage('No lobbykey found');

            return $this->response;
        }

        //Gets an adapter
        $this->getAdapter();

        /** @var LobbyEntity $result */
        $result = $this->adapter->getLobbyByKey($_SESSION['lobbykey']);


        $this->response->setStatusCode(Response::SUCCESS);
        $this->response->setMessage('SUCCESS');
        $this->response->setTime($result->time);

        return $this->response;
    }

    private function validate($input)
    {
        $input = htmlspecialchars($input);
        $input = strip_tags($input);
        $input = stripslashes($input);
        $input = trim($input);

        return $input;
    }

    private function getAdapter()
    {
        $this->adapter = ($this->adapter == null) ? new SyncAdapter() : $this->adapter;
    }
}