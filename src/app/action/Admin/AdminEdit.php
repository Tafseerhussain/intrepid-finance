<?php

class Action_Admin_AdminEdit extends Tell_Action implements Tell_Container_Csrf
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
            'email'        => $this->get('email'),
            'first_name'   => $this->get('first_name'),
            'last_name'    => $this->get('last_name'),
            'status'       => $this->get('status'),
            'access_level' => $this->get('access_level'),
            'modified'     => gmdate('Y-m-d H:i:s'),
        ];

        if ($this->get('password')) {
            $data['password'] = Tell_Password::hash($this->get('password'));
        }

        $this->admin->save($data);

        if ($this->isEditingSelf()) {
            $this->auth->refresh();
        }
    }

    public function verify()
    {
        if ( ! $this->admin->load($this->id)) {
            $this->error('id', 'Record not found.');
        }

        $this->rules('email')->required()->email();
        $this->rules('first_name')->required()->firstName();
        $this->rules('last_name')->required()->lastName();
        $this->rules('password')->optional()->lengthMin(7);
        $this->rules('password_again')->matches('password');
        $this->rules('status')->whitelist(array_keys(Lexicon::admins_status()));
        $this->rules('access_level')->whitelist(array_keys(Lexicon::admins_access_level()));

        if ( ! $this->admin->verifyEmailAvailable($this->get('email'))) {
            $this->error('email', 'This email is being used on another account.');
        }

        if ($this->isEditingSelf()) {
            if ($this->get('status') !== $this->auth->get('status')) {
                $this->error('status', 'You cannnot change your own status.');
            }

            if ($this->get('access_level') !== $this->auth->get('access_level')) {
                $this->error('access_level', 'You cannnot change your own access level.');
            }
        }
    }

    protected function isEditingSelf()
        : bool
    {
        return (int) $this->auth->get('id') === (int) $this->id;
    }
}
