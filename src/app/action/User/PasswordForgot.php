<?php

class Action_User_PasswordForgot extends Tell_Action implements Tell_Container_Csrf
{
    protected $user  = NULL;
    protected $email = NULL;

    public function __construct(User $user, Tell_Email $email)
    {
        $this->user  = $user;
        $this->email = $email;
    }

    public function run()
    {
        if ( ! $this->user->where('email = ? AND deleted IS NULL', $this->get('email'))) {
            usleep(mt_rand(100, 900));
            return;
        }

        $token = Tell_Security::random(32);

        $this->user->save([
            'password_token' => $token,
            'password_date'  => gmdate('Y-m-d H:i:s'),
        ]);

        $data = [
            'token' => $token,
        ];

        $this->email->to($this->get('email'))
            ->format('emails/password-forgot', $data)
            ->subject('Intrepid Finance - Reset Your Password')
            ->send();
    }

    public function verify()
    {
        $this->rules('email')->required()->email();
    }
}
