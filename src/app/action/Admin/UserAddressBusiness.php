<?php

class Action_Admin_UserAddressBusiness extends Tell_Action implements Tell_Container_Csrf
{
    protected $auth = NULL;

    protected $user = NULL;

    protected $id = NULL;

    protected $contactBusiness = NULL;

    public function __construct(Auth_AdminLogin $auth, User $user, $id)
    {
        $this->auth = $auth;
        $this->user = $user->setAuth($auth);
        $this->id   = $id;

        $this->contactBusiness = (new Tell_Bridge_Contact('business'))
            ->address1($this->get('business_address1'))
            ->address2($this->get('business_address2'))
            ->city($this->get('business_city'))
            ->province($this->get('business_province'))
            ->postal($this->get('business_postal'))
            ->country($this->get('business_country'));
    }

    public function run()
    {
        $data = [
            'business_address1' => $this->get('business_address1'),
            'business_address2' => $this->get('business_address2'),
            'business_city'     => $this->get('business_city'),
            'business_province' => $this->get('business_province'),
            'business_postal'   => $this->get('business_postal'),
            'business_country'  => $this->get('business_country'),
            'modified'          => gmdate('Y-m-d H:i:s'),
        ];

        $this->user->save($data);
    }

    public function verify()
    {
        if ( ! $this->user->isLoaded() && ! $this->user->load($this->id)) {
            $this->error('id', 'Record not found.');
        }

        $this->errors($this->contactBusiness->errors());
    }
}
