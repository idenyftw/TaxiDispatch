<?php

class Notification
{
    private string      $_msg;
    public string       $msg   { 
        get{ return $this->_msg; }            
        set(string $value) { 

            if($value)
                $this->_msg = $value;
            else
            {
                $this->_msg = "Default Message";
            }
        } 
    }

    public function __construct(string $msg)
    {
        $this->msg        = $msg;
    }

    public function __toString() : string
    {
        return $this->msg; 
    }
}
