<?php

class Log
{
    private DateTime    $_date;
    private string      $_msg;
    private bool        $_is_enabled;

    public DateTime     $date       { get{ return $this->_date; }           set(string $value) { $this->_date       = new DateTime($value); } }
    public string       $msg        { get{ return $this->_msg; }            set($value) { $this->_msg        = $value; } }
    public bool         $is_enabled { get{ return $this->_is_enabled; }     set($value) { $this->_is_enabled = $value; } }

    public function __construct($date, $msg)
    {
        $this->date       = $date;
        $this->msg        = $msg;
        $this->is_enabled = false;
    }

    public function toString() : string
    {
        return "LOG ".$this->date. ": ". $this->msg; 
    }
}
