<?php

class YiiApnsGcm extends CApplicationComponent
{
    const TYPE_GCM = 'gcm';
    const TYPE_APNS = 'apns';

    /**
     * component name for the apns
     * @var string
     */
    public $apns = 'apns';

    /**
     * component name for gcm
     * @var string
     */
    public $gcm = 'gcm';

    public $errors = array();
    public $success = false;

    private $_gcmClient;
    private $_apnsClient;

    /**
     * @return  YiiApnsGcmBase|YiiGcm
     */
    public function getGcmClient()
    {
        if ($this->_gcmClient === null) {
            $component = $this->gcm;
            $client = Yii::app()->$component;

            $this->_gcmClient = $client;
        }

        return $this->_gcmClient;
    }

    /**
     * @return YiiApnsGcmBase|YiiApns
     */
    public function getApnsClient()
    {
        if ($this->_apnsClient === null) {
            $component = $this->apns;
            $client = Yii::app()->$component;

            $this->_apnsClient = $client;
        }

        return $this->_apnsClient;
    }

    /**
     * send a push notification depending on type
     * @param $type
     * @param $token
     * @param $text
     * @param array $payloadData
     * @param array $args
     * @return null|\PHP_GCM\Message
     */
    public function send($type, $token, $text, $payloadData = array(), $args = array())
    {
        $client = null;
        $result = null;
        if ($type == self::TYPE_GCM) {
            $client = $this->getGcmClient();
            $result = $client->send($token, $text, $payloadData, $args);
            $this->success = $client->success;
        } elseif ($type == self::TYPE_APNS) {
            $client = $this->getApnsClient();
            $result = $client->send($token, $text, $payloadData, $args);

            $this->success = $client->success;
        }

        return $result;
    }

    public function sendMulti($type, $tokens, $text, $payloadData = array(), $args = array())
    {
        $client = null;
        $result = null;
        if ($type == self::TYPE_GCM) {
            $client = $this->getGcmClient();
            $result = $client->sendMulti($tokens, $text, $payloadData, $args);
            $this->success = $client->success;
        } elseif ($type == self::TYPE_APNS) {
            $client = $this->getApnsClient();
            $result = $client->sendMulti($tokens, $text, $payloadData, $args);

            $this->success = $client->success;
        }

        return $result;
    }
}