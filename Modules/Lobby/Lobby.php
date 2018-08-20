<?php

class Lobby
{
    /** @var LobbyResponse */
    private $response;

    /** @var LobbyAdapter  */
    private $adapter;


    public function __construct()
    {
        $this->response = new LobbyResponse();
    }

    public function create()
    {
        session_start();

        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            $this->response->setStatusCode(Response::METHOD_NOT_ALLOWED);
            $this->response->setMessage('This Service is POST');

            return $this->response;
        }

        if(!isset($_SESSION['email'])){
            $this->response->setStatusCode(Response::UNAUTHORIZED);
            $this->response->setMessage('Not logged in');

            return $this->response;
        }

        $this->getAdapter();
        $userId = $this->adapter->getUserByEmail($_SESSION['email'])->id;

        $key = uniqid();

        $_SESSION['lobbykey'] = $key;

        $this->adapter->createLobby($userId, $key);

        $this->response->setStatusCode(Response::SUCCESS);
        $this->response->setMessage('Successfully created');

        return $this->response;
    }

    public function join($lobbyKey)
    {
        session_start();

        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            $this->response->setStatusCode(Response::METHOD_NOT_ALLOWED);
            $this->response->setMessage('This Service is POST');

            return $this->response;
        }

        if(!isset($_SESSION['email'])){
            $this->response->setStatusCode(Response::UNAUTHORIZED);
            $this->response->setMessage('User is not logged in');

            return $this->response;
        }

        if(!isset($lobbyKey)){
            $this->response->setStatusCode(Response::BAD_REQUEST);
            $this->response->setMessage('No lobbykey found');

            return $this->response;
        }

        $this->getAdapter();
        /** @var LobbyEntity $result */
        $result = $this->adapter->getLobbyByKey($lobbyKey);

        if(!$result){
            $this->response->setStatusCode(Response::UNAUTHORIZED);
            $this->response->setMessage('No Lobby found');

            return $this->response;
        }

        /** @var UserEntity $user */
        $user = $this->adapter->getUserByEmail($_SESSION['email']);

        // Reads the members string and parses it to an array
        $lobbyMembers = $this->members2array($result->members);

        //Checks if the user is already lobbyadmin
        if($user->id == $result->admin){
            $this->response->setStatusCode(LobbyResponse::USER_EQUAL_TO_LOBBYADMIN);
            $this->response->setMessage('User is lobbyadmin');

            $_SESSION['lobbykey'] = $lobbyKey;

            return $this->response;
        }

        //Checks if the user is already in the lobby
        foreach ($lobbyMembers as $val){

            if($val == $user->id){
                $this->response->setStatusCode(LobbyResponse::USER_ALREADY_IN_LOBBY);
                $this->response->setMessage('User is already in the lobby');

                $_SESSION['lobbykey'] = $lobbyKey;

                return $this->response;
            }

        }

        // If members is null just the id gets added, else it adds a comma plus the id
        $inputString = (!$result->members) ? $user->id : $result->members . ',' . $user->id;

        //Updates the column members
        $this->adapter->updateMembers($inputString, $lobbyKey);

        //Adds the User to the lobbysession
        $_SESSION['lobbykey'] = $lobbyKey;


        $this->response->setStatusCode(Response::SUCCESS);
        $this->response->setMessage('SUCCESS');

        return $this->response;
    }

    public function getUser()
    {
        session_start();

        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            $this->response->setStatusCode(Response::METHOD_NOT_ALLOWED);
            $this->response->setMessage('This Service is POST');

            return $this->response;
        }

        if(!isset($_SESSION['lobbykey'])){
            $this->response->setStatusCode(Response::UNAUTHORIZED);
            $this->response->setMessage('User is not a part of the lobby');

            return $this->response;
        }

        $this->getAdapter();
        /** @var LobbyEntity $result */
        $result = $this->adapter->getLobbyByKey($_SESSION['lobbykey']);

        $this->response->setStatusCode(Response::SUCCESS);
        $this->response->setMessage('SUCCESS');
        $this->response->setAdmin($this->adapter->getUserById($result->admin)->username);
        $this->response->setMembers($this->adapter->getLobbyMemberList($this->members2array($result->members)));
        $this->response->setTime($result->time);

        return $this->response;
    }

    private function getAdapter()
    {
        $this->adapter = ($this->adapter == null) ? new LobbyAdapter() : $this->adapter;
    }

    /**
     * Converts string to array
     *
     * String : 5,1,7,13,3,8
     * To Array : [5,1,7,13,3,8]
     *
     * @param string $members
     * @return array
     */
    private function members2array($members)
    {
        return explode(',', $members);
    }
}