<?php


class LobbyResponse extends Response
{
    public function setAdmin($admin)
    {
        $this->data['admin'] = $admin;
    }

    public function setMembers($members)
    {
        $this->data['members'] = $members;
    }
}