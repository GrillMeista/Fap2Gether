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





        $this->response->setStatusCode(Response::SUCCESS);
        $this->response->setMessage('SUCCESS');

        return $this->response;
    }

    public function getTime()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            $this->response->setStatusCode(Response::METHOD_NOT_ALLOWED);
            $this->response->setMessage('This Service is POST');

            return $this->response;
        }





        $this->response->setStatusCode(Response::SUCCESS);
        $this->response->setMessage('SUCCESS');

        return $this->response;
    }

    private function getAdapter()
    {
        $this->adapter = ($this->adapter == null) ? new SyncAdapter() : $this->adapter;
    }
}