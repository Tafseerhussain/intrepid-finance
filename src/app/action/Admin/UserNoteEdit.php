<?php

class Action_Admin_UserNoteEdit extends Tell_Action implements Tell_Container_Csrf
{
    protected $auth = NULL;

    protected $note = NULL;

    protected $crypto = NULL;

    protected $id = NULL;

    public function __construct(Auth_AdminLogin $auth, UserNote $note, Tell_Crypto $crypto, $id)
    {
        $this->auth   = $auth;
        $this->note   = $note;
        $this->crypto = $crypto;
        $this->id     = $id;
    }

    public function run()
    {
        $this->note->save([
            'note'     => $this->crypto->encrypt($this->get('note_edit')),
            'modified' => gmdate('Y-m-d H:i:s'),
        ]);
    }

    public function verify()
    {
        if ( ! $this->note->isLoaded() && ! $this->note->load($this->id)) {
            $this->error('note_edit', 'Record not found.');
        }

        $this->rules('note_edit')->lengthBetween(2, 3000);
    }
}
