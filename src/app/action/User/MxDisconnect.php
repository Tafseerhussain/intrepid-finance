<?php

class Action_User_MxDisconnect extends Tell_Action
{
    protected $auth = NULL;
    protected $user = NULL;

    public function __construct(Auth_UserLogin $auth, User $user)
    {
        $this->auth = $auth;
        $this->user = $user;
    }

    public function run()
    {
        $this->user->doMxDisconnect();
    }

    public function verify()
    {
        if ( ! $this->auth->isAuthenticated()) {
            $this->error('auth', 'User not authenticated.');
        }

        if ( ! $this->user->load($this->auth->get('id'))) {
            $this->error('user', 'User record failed to load.');
        }
    }
}
