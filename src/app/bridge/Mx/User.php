<?php

/**
 * @link  https://docs.mx.com/api#core_resources_users_create_user
 */
class Mx_User extends Mx_Api
{
    protected $userGuid   = NULL;
    protected $email      = NULL;
    protected $id         = NULL;
    protected $isDisabled = FALSE;
    protected $metadata   = [];

    public function setUserGuid(?string $guid)
        : self
    {
        $this->userGuid = $guid;

        return $this;
    }

    public function setEmail(?string $email)
        : self
    {
        $this->email = $email;

        return $this;
    }

    public function setId($id)
        : self
    {
        $this->id = $id;

        return $this;
    }

    public function setIsDisabled(bool $disabled)
        : self
    {
        $this->isDisabled = $disabled;

        return $this;
    }

    public function setMetadata(array $meta)
        : self
    {
        $this->metadata = $meta;

        return $this;
    }

    public function createUser()
        : bool
    {
        $data = [
            'is_disabled' => $this->isDisabled,
        ];

        if ($this->email) {
            $data['email'] = $this->email;
        }

        if ($this->id) {
            $data['id'] = $this->id;
        }

        if ( ! empty($this->metadata)) {
            $data['metadata'] = Tell_Json::encode($this->metadata);
        }

        $data['user'] = $data;

        $url = $this->getEndpoint() . '/users';

        $request = $this->getRequest()->json($data);

        $response = (new Tell_Client())->post($url, $request);

        if (200 === $response->code()) {
            $this->userGuid = $response->body('user.guid');
        } else {
            throw (new Mx_Exception())
                ->setMessage('Failed to create user.')
                ->setCode($response->code())
                ->setResponse($response);
        }

        return TRUE;
    }

    public function deleteUser()
        : bool
    {
        if ( ! $this->userGuid) {
            throw new Mx_Exception('Cannot delete user. Missing user GUID.');
        }

        $url = $this->getEndpoint() . '/users/' . $this->userGuid;

        $request = $this->getRequest();

        $response = (new Tell_Client())->delete($url, $request);

        if (204 !== $response->code()) {
            throw (new Mx_Exception())
                ->setMessage('Failed to delete user.')
                ->setCode($response->code())
                ->setResponse($response);
        }

        return TRUE;
    }
}
