<?php

class Auth_AdminLogin extends Tell_Auth_Login
{
    const PERSIST_DURATION = 604800;
    const RECORD_TABLE     = 'admins';
    const RECORD_PRIMARY   = 'id';
    const RECORD_USERNAME  = 'email';
    const RECORD_PASSWORD  = 'password';

    public function onAuthorizeFailure()
        : Tell_Response_Interface
    {
        if ( ! $this->isAuthenticated()) {
            Tell_Message::error('You must be logged in to access that area.');
        } else {
            Tell_Message::error("Sorry, you don't have access to that area.");
        }

        return (new Tell_Response_Redirect())->url('/admin');
    }

    public function onAuthenticate(array $row)
        : bool
    {
        return 'Active' === ($row['status'] ?? NULL);
    }

    public function onAuthenticateSuccess(array $row)
    {
        $this->db->query('UPDATE admins WHERE id = ? LIMIT 1')->data([
            'last_ip' => Ip::trusted(),
        ])->bind($row['id'])->run();
    }

    public function isLevel1()
        : bool
    {
        return ((int) $this->get('access_level')) >= 1;
    }

    public function isLevel2()
        : bool
    {
        return ((int) $this->get('access_level')) >= 2;
    }

    public function isLevel3()
        : bool
    {
        return 3 === (int) $this->get('access_level');
    }

    public function getGravatar()
        : string
    {
        if ( ! $this->isAuthenticated()) {
            throw new Exception('Not authenticated.');
        }

        $base = 'https://www.gravatar.com/avatar/';
        $hash = md5(strtolower(trim($this->get('email'))));
        $none = '?s=200&d=mp';

        return $base . $hash . $none;
    }
}
