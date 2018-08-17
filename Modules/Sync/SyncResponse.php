<?php

class SyncResponse extends Response
{
    const NO_TIME = 1019;

    public function setTime($time)
    {
        $this->data['time'] = $time;
    }
}