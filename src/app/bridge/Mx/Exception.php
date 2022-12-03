<?php

class Mx_Exception extends Exception implements Tell_Cast_Arr
{
    protected $response = NULL;

    public function setMessage(string $message)
        : self
    {
        $this->message = $message;

        return $this;
    }

    public function setCode(int $code)
        : self
    {
        $this->code = $code;

        return $this;
    }

    public function setResponse(Tell_Client_Response $response)
        : self
    {
        $this->response = $response;

        return $this;
    }

    public function toArr()
        : array
    {
        return [
            'code'     => $this->code,
            'message'  => $this->message,
            'response' => $this->response->body(),
        ];
    }
}
