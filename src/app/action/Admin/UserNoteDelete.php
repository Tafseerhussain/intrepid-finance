<?php

class Action_Admin_UserNoteDelete extends Tell_Action implements Tell_Container_Csrf
{
    protected $auth = NULL;

    protected $note = NULL;

    protected $id = NULL;

    public function __construct(Auth_AdminLogin $auth, UserNote $note, $id)
    {
        $this->auth = $auth;
        $this->note = $note;
        $this->id   = $id;
    }

    public function run()
    {
        $data = [
            'deleted' => gmdate('Y-m-d H:i:s'),
        ];

        $this->note->save($data);
    }

    public function verify()
    {
        if ( ! $this->note->load($this->id)) {
            $this->error('id', 'Record not found.');
        }
    }
}
