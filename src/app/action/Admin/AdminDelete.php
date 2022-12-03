<?php

class Action_Admin_AdminDelete extends Tell_Action implements Tell_Container_Csrf
{
    protected $auth = NULL;

    protected $admin = NULL;

    protected $id = NULL;

    public function __construct(Auth_AdminLogin $auth, Admin $admin, $id)
    {
        $this->auth  = $auth;
        $this->admin = $admin;
        $this->id    = $id;
    }

    public function run()
    {
        $data = [
            'deleted' => gmdate('Y-m-d H:i:s'),
        ];

        $this->admin->save($data);
    }

    public function verify()
    {
        if ( ! $this->admin->load($this->id)) {
            $this->error('id', 'Record not found.');
        }

        if ($this->isEditingSelf()) {
            $this->error('id', 'You cannot delete your own account.');
        }
    }

    protected function isEditingSelf()
        : bool
    {
        return (int) $this->auth->get('id') === (int) $this->id;
    }
}
