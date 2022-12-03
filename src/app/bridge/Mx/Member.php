<?php

/**
 * @link  https://docs.mx.com/api#core_resources_members_list_members
 */
class Mx_Member extends Mx_Api
{
    protected $userGuid   = NULL;
    protected $memberGuid = NULL;

    public function setUserGuid(?string $guid)
        : self
    {
        $this->userGuid = $guid;

        return $this;
    }

    public function setMemberGuid(?string $guid)
        : self
    {
        $this->memberGuid = $guid;

        return $this;
    }

    public function deleteMember()
    {
    }

    public function listMembers()
    {
    }

    public function readMember()
    {
    }

    public function readMemberStatus()
    {
    }
}
