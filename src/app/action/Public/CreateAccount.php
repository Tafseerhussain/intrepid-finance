<?php

class Action_Public_CreateAccount extends Tell_Action implements Tell_Container_Csrf
{
    public $user = NULL;

    public $token = NULL;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function run()
    {
        $data = [
            'password' => Tell_Password::hash($this->get('password')),
            'modified' => gmdate('Y-m-d H:i:s'),
        ];

        $this->user->save($data);
    }

    public function verify()
    {
        if ( ! $this->user->isLoaded()) {
            $this->token = $this->get('form_token');

            $this->user->loadByFormToken($this->token);
        } else {
            $this->token = $this->user->get('form_token');
        }

        if ( ! $this->user->isLoaded()) {
            $this->error('form_token', 'Record not found.');
            return;
        }

        if ( ! $this->user->needsAccountCreated()) {
            $this->error('email', 'Account already created.');
            return;
        }

        $this->rules('password')->lengthMin(7);
        $this->rules('password_again')->matches('password');
    }
}
