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
        $logDir = __DIR__ . '/../../logs';
        $fileName = 'LOG-' . date('Y.m.d') . '.txt';
        $filePath = $logDir . '/' . $fileName;

        // Ensure log directory exists
        if (!is_dir($logDir)) {
            mkdir($logDir, 0775, true);
        }

        // Open file for append and handle permission issues gracefully
        $file = @fopen($filePath, 'a');

        if (!$file) {
            error_log("[Logger] Unable to open log file: $filePath");
            return false;
        }

        fwrite($file, $this->__toString() . PHP_EOL);
        fclose($file);

        return true;
    }
}
