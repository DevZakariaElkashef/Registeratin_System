<?php

namespace Core;

use App\Config;

class Error
{
    public static function errorHandler($level, $message, $file, $line)
    {
        if (error_reporting() !== 0) {  // to keep the @ operator working
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }


    public static function exceptionHandler($exception)
    {
        $code = $exception->getCode();
        if($code != 404){
            $code = 500;
        }
        http_response_code($code);

        if(Config::SHOW_ERRORS){

            echo "<h1>Fatal error</h1>";
            echo "<p>Uncaught exception: '" . get_class($exception) . "'</p>";
            echo "<p>Message: '" . $exception->getMessage() . "'</p>";
            echo "<p>Stack trace:<pre>" . $exception->getTraceAsString() . "</pre></p>";
            echo "<p>Thrown in '" . $exception->getFile() . "' on line " . $exception->getLine() . "</p>";
        }else{
            $logs = dirname(__DIR__) . '/Logs/' . date('Y-m-d') . '.txt';
            ini_set('error_log', $logs);
            $message = "\n------------------------------------------------------------------------------------------------------------------------------------------\n";
            $message .= "Uncaught exception: '" . get_class($exception) . "'";
            $message .= " with message '" . $exception->getMessage() . "'";
            $message .= "\nStack trace: " . $exception->getTraceAsString();
            $message .= "\nThrown in '" . $exception->getFile() . "' on line " . $exception->getLine();
            $message .= "\n-----------------------------------------------------------------------------------------------------------------------------------------";

            error_log($message);

            view::templateRender($code. '.html');
        }

        
    }
}