<?php

class Action_User_PasswordReset extends Tell_Action implements Tell_Container_Csrf
{
    protected $user = NULL;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function run()
    {
        return $this->user->save([
            'password'       => Tell_Password::hash($this->get('password')),
            'password_token' => NULL,
            'password_date'  => NULL,
            'modified'       => gmdate('Y-m-d H:i:s'),
        ]);
    }

    public function verify()
    {
        $this->rules('password_token')->required();

        if ( ! $this->user->where('password_token = ? AND deleted IS NULL', $this->get('password_token'))) {
            $this->error('password_token', 'Invalid password reset token.');
        } elseif ( ! $this->user->isUsableAccount()
            || strtotime($this->user->get('password_date')) < strtotime('-24 hours')
        ) {
            $this->error('password_token', 'Invalid password reset token.');
        }

        $this->rules('password')->lengthMin(7);
        $this->rules('password_again')->matches('password');
    }
}
