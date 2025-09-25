<?php

class Log
{
    private DateTime    $_date;
    private string      $_msg;
    private bool        $_is_enabled;

    public string  $date { 
        get
        { 
            return $this->_date->format("Y-m-d H:i:s"); 
        } 
        set(string $value) 
        { 
            $this->_date = new DateTime($value); 
        }
    }

    public string  $msg        { 
        get{ return $this->_msg; }            
        set(string $value) 
        {             
            if($value)
            {
                $this->_msg = $value;
            }
            else
            {
                $this->_msg = "Default Message";
            }
        } 
    }

    public bool         $is_enabled { get{ return $this->_is_enabled; }     set(bool $value) { $this->_is_enabled = $value; } }

    public function __construct(string $date, string $msg)
    {
        $this->date       = $date;
        $this->msg        = $msg;
        $this->is_enabled = false;
    }
    
    public function __toString(){
        return $this->date. ": ". $this->msg. " \n"; 
    }

    public function write()
    {
        $fileName = "LOG-".date("Y.m.d").".txt";
        $filePath = "../../logs/".$fileName;

        $file = fopen($filePath, "a") or die("Unable to open file!");;

        fwrite($file, $this->__toString());
        fclose($file);
    }
}
