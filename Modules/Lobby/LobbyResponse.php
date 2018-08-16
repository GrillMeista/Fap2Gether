<?php


class LobbyResponse extends Response
{
    const USER_EQUAL_TO_LOBBYADMIN = 1007;
    const USER_ALREADY_IN_LOBBY = 1008;

    public function setAdmin($admin)
    {
        $this->data['admin'] = $admin;
    }

    public function setMembers($members)
    {
        $this->data['members'] = $members;
    }

    public function setTime($time)
    {
        $this->data['time'] = $time;
    }
}