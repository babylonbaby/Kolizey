<?php
/**
 * @company PlatformaSoft, Ltd.
 * @author Maxim Tyuftin <maxim.t@platformasoft.ru>
 */


function d($arg, $depth = 2)
{
    echo "<pre style=\"background: #f4f4f4; padding: 10px; border: 1px solid #ccc;\">";
    \Doctrine\Common\Util\Debug::dump($arg, $depth);
    echo "</pre>";
}

function dd($arg, $depth = 2)
{
    d($arg, $depth);die();
}

function timerPrint($label = '')
{
    $lastCall = GetCallingMethodName();



    $time = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
    $ms = (int)($time * 1000);
    $txt = $ms . " ms";

    if(!empty($label)) {
        print $label . ': '.$txt;
    } else {
        print $txt;
    }

    print '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '. basename($lastCall['file']) . ':' . $lastCall['line'];

    print "<br />";
}

function GetCallingMethodName($traceNum = 1){
    $e = new Exception();
    $trace = $e->getTrace();
    //position 0 would be the line that called this function so we ignore it
    $last_call = $trace[$traceNum];
    return $last_call;
}
