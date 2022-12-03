<?php

class Action_User_MxMemberConnected extends Tell_Action
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
        $this->user->save([
            'mx_user_guid'    => $this->get('user_guid'),
            'mx_member_guid'  => $this->get('member_guid'),
            'mx_needs_widget' => 'N',
            'modified'        => gmdate('Y-m-d H:i:s'),
        ]);
    }

    public function verify()
    {
        if ( ! $this->auth->isAuthenticated()) {
            $this->error('auth', 'User not authenticated.');
        }

        if ( ! $this->user->load($this->auth->get('id'))) {
            $this->error('user', 'User record failed to load.');
        }

        $this->rules('user_guid')->required();
        $this->rules('session_guid')->required();
        $this->rules('member_guid')->required();
    }
}
