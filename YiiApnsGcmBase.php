<?php

abstract class YiiApnsGcmBase extends CApplicationComponent
{
    public $retryTimes = 3;
    public $dryRun = false;
    public $enableLogging = true;
    public $errors = array();
    public $success = false;

    public function log($tokens, $text, $payloadData = array(), $args = array())
    {
        $payloadData = http_build_query($payloadData);
        $args = http_build_query($args);
		$tokens = is_array($tokens) ? implode(', ' , $tokens) : $tokens;
        $msg = "Sending push notifications to " . $tokens . "\n" .
            "message: {$text}\n" .
            "payload data: " . str_replace('&', ', ', $payloadData) . "\n" .
            "arguments: " . str_replace('&', ', ', $args);
        Yii::log($msg, CLogger::LEVEL_INFO, 'YiiApnsGcm');
    }

    abstract public function send($token, $text, $payloadData = array(), $args = array());
    abstract public function sendMulti($tokens, $text, $payloadData = array(), $args = array());
}