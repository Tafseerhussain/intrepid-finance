<?php

class Action_Admin_UserDelete extends Tell_Action implements Tell_Container_Csrf
{
    protected $auth = NULL;

    protected $user = NULL;

    protected $id = NULL;

    public function __construct(Auth_AdminLogin $auth, User $user, $id) {
        $this->auth = $auth;
        $this->user = $user->setAuth($auth);
        $this->id   = $id;
    }

    public function run()
    {
        $data = [
            'modified' => gmdate('Y-m-d H:i:s'),
            'deleted'  => gmdate('Y-m-d H:i:s'),
        ];

        $this->user->save($data);

        $this->user->doMxDisconnect();
    }

    public function verify()
    {
        if ( ! $this->user->isLoaded() && ! $this->user->load($this->id)) {
            $this->error('id', 'Record not found.');
        }
    }
}
