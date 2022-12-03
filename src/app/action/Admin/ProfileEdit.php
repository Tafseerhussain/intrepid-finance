<?php

class Action_Admin_ProfileEdit extends Tell_Action
{
    protected $auth = NULL;

    protected $admin = NULL;

    public function __construct(Auth_AdminLogin $auth, Admin $admin)
    {
        $this->auth  = $auth;
        $this->admin = $admin;
    }

    public function run()
    {
        $data = [
            'email'      => $this->get('email'),
            'first_name' => $this->get('first_name'),
            'last_name'  => $this->get('last_name'),
            'modified'   => gmdate('Y-m-d H:i:s'),
        ];

        if ($this->get('password')) {
            $data['password'] = Tell_Password::hash($this->get('password'));
        }

        $this->admin->save($data);

        $this->auth->refresh();
    }

    public function verify()
    {
        if ( ! $this->admin->load($this->auth->get('id'))) {
            $this->error('id', 'Record not found.');
        }

        $this->rules('email')->required()->email();
        $this->rules('first_name')->required()->firstName();
        $this->rules('last_name')->required()->lastName();
        $this->rules('password')->optional()->lengthMin(7);
        $this->rules('password_again')->matches('password');

        if ( ! $this->admin->verifyEmailAvailable($this->get('email'))) {
            $this->error('email', 'This email is being used on another account.');
        }
    }
}
