<?php

class Action_Admin_UserAddressHome extends Tell_Action implements Tell_Container_Csrf
{
    protected $auth = NULL;

    protected $user = NULL;

    protected $id = NULL;

    protected $contactHome = NULL;

    public function __construct(Auth_AdminLogin $auth, User $user, $id)
    {
        $this->auth = $auth;
        $this->user = $user->setAuth($auth);
        $this->id   = $id;

        $this->contactHome = (new Tell_Bridge_Contact('home'))
            ->address1($this->get('home_address1'))
            ->address2($this->get('home_address2'))
            ->city($this->get('home_city'))
            ->province($this->get('home_province'))
            ->postal($this->get('home_postal'))
            ->country($this->get('home_country'));
    }

    public function run()
    {
        $data = [
            'home_address1' => $this->get('home_address1'),
            'home_address2' => $this->get('home_address2'),
            'home_city'     => $this->get('home_city'),
            'home_province' => $this->get('home_province'),
            'home_postal'   => $this->get('home_postal'),
            'home_country'  => $this->get('home_country'),
            'modified'      => gmdate('Y-m-d H:i:s'),
        ];

        $this->user->save($data);
    }

    public function verify()
    {
        if ( ! $this->user->isLoaded() && ! $this->user->load($this->id)) {
            $this->error('id', 'Record not found.');
        }

        $this->errors($this->contactHome->errors());
    }
}
