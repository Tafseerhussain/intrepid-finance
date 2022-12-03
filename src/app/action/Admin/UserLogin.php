<?php

class Action_Admin_UserLogin extends Tell_Action
{
    protected $auth = NULL;

    protected $id = NULL;

    public function __construct(Auth_UserLogin $auth, $id)
    {
        $this->auth = $auth;
        $this->id   = $id;
    }

    public function verify()
    {
        if ( ! $this->auth->loginDirect($this->id)) {
            $this->error('Failed to login as user: ' . $this->id);
        }
    }
}
