<?php

class Action_Admin_AdminAdd extends Tell_Action implements Tell_Container_Csrf
{
    protected $admin = NULL;

    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }

    public function run()
    {
        $this->admin->save([
            'email'        => $this->get('email'),
            'first_name'   => $this->get('first_name'),
            'last_name'    => $this->get('last_name'),
            'password'     => Tell_Password::hash($this->get('password')),
            'status'       => $this->get('status'),
            'access_level' => $this->get('access_level'),
            'modified'     => gmdate('Y-m-d H:i:s'),
            'created'      => gmdate('Y-m-d H:i:s'),
        ]);
    }

    public function verify()
    {
        $this->rules('email')->required()->email();
        $this->rules('first_name')->required()->firstName();
        $this->rules('last_name')->required()->lastName();
        $this->rules('password')->lengthMin(7);
        $this->rules('password_again')->matches('password');
        $this->rules('status')->whitelist(array_keys(Lexicon::admins_status()));
        $this->rules('access_level')->whitelist(array_keys(Lexicon::admins_access_level()));

        if ( ! $this->admin->verifyEmailAvailable($this->get('email'))) {
            $this->error('email', 'This email is being used on another account.');
        }
    }
}
