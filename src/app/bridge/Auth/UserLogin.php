<?php

class Auth_UserLogin extends Tell_Auth_Login
{
    const PERSIST_DURATION = 604800;
    const RECORD_TABLE     = 'users';
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

        return (new Tell_Response_Redirect())->url('/clients');
    }

    public function onAuthenticate(array $row)
        : bool
    {
        return isset($row['password']) && 'Abandoned' !== ($row['status'] ?? NULL);
    }

    public function loginDirect(int $id)
        : bool
    {
        $data = $this->findUserId($id);

        if ( ! $data || ! ($data[static::RECORD_PASSWORD] ?? NULL)
        ) {
            $this->event('onAuthenticateFailure', $data);

            return FALSE;
        }

        if (FALSE === $this->event('onAuthenticate', $data)) {
            $this->event('onAuthenticateFailure', $data);

            return FALSE;
        }

        return $this->persistCreate($data, 0);
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
