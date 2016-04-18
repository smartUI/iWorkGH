<?php

namespace Org;

class Exception {

    protected $message;
    private $string;
    protected $code;
    protected $file;
    protected $line;
    private $trace;
    private $previous;

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Clone the exception
     * @link http://php.net/manual/en/exception.clone.php
     * @return void No value is returned.
     */
    final private function __clone() {
        
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Construct the exception
     * @link http://php.net/manual/en/exception.construct.php
     * @param $message [optional]
     * @param $code [optional]
     * @param $previous [optional]
     */
    public function __construct($message, $code, $previous) {
        
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Gets the Exception message
     * @link http://php.net/manual/en/exception.getmessage.php
     * @return string the Exception message as a string.
     */
    final public function getMessage() {
        
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Gets the Exception code
     * @link http://php.net/manual/en/exception.getcode.php
     * @return mixed the exception code as integer in
     * <b>Exception</b> but possibly as other type in
     * <b>Exception</b> descendants (for example as
     * string in <b>PDOException</b>).
     */
    final public function getCode() {
        
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Gets the file in which the exception occurred
     * @link http://php.net/manual/en/exception.getfile.php
     * @return string the filename in which the exception was created.
     */
    final public function getFile() {
        
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Gets the line in which the exception occurred
     * @link http://php.net/manual/en/exception.getline.php
     * @return int the line number where the exception was created.
     */
    final public function getLine() {
        
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Gets the stack trace
     * @link http://php.net/manual/en/exception.gettrace.php
     * @return array the Exception stack trace as an array.
     */
    final public function getTrace() {
        
    }

    /**
     * (PHP 5 &gt;= 5.3.0)<br/>
     * Returns previous Exception
     * @link http://php.net/manual/en/exception.getprevious.php
     * @return Exception the previous <b>Exception</b> if available
     * or <b>NULL</b> otherwise.
     */
    final public function getPrevious() {
        
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Gets the stack trace as a string
     * @link http://php.net/manual/en/exception.gettraceasstring.php
     * @return string the Exception stack trace as a string.
     */
    final public function getTraceAsString() {
        
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * String representation of the exception
     * @link http://php.net/manual/en/exception.tostring.php
     * @return string the string representation of the exception.
     */
    public function __toString() {
        
    }

}
