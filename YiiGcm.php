<?php

require_once(dirname(__FILE__) . '/YiiApnsGcmBase.php');

define('YII_GCM_LIB_PATH', dirname(__FILE__) . '/lib');

class YiiGCM extends YiiApnsGcmBase
{
    public $apiKey;

    private $_client = null;

    public function init()
    {
        if (!$this->apiKey) {
            throw new CException('Api key cannot be empty');
        }

        $this->initAutoloader();

        parent::init();
    }

    public function getClient()
    {
        if ($this->_client === null) {
            $this->_client = new \PHP_GCM\Sender($this->apiKey);
        }

        return $this->_client;
    }

    /**
     * send a push notification for android using GCM client
     *
     * Usage 1:
     * <code>
     * $this->send('some-valid-token','some-message',
     * array(
     *   'custom_data_key_1'=>'custom_data_value_1',
     *   'custom_data_key_2'=>'custom_data_value_2',
     * ));
     * </code>
     * @param string $token
     * @param $text
     * @param array $payloadData
     * @param array $args
     * @return null|\PHP_GCM\Message
     */
    public function send($token, $text, $payloadData = array(), $args = array())
    {
        // check if its dry run or not
        if ($this->dryRun === true) {
            $this->log($token, $text, $payloadData, $args);
            return null;
        }

        $message = new PHP_GCM\Message();
        foreach($args as $method => $value) {
            $value = is_array($value) ? $value : array($value);
            call_user_func_array(array($message, $method), $value);
        }
        // set a custom payload data
        $payloadData['message'] = $text;
        foreach($payloadData as $key=>$value){
            $message->addData($key,$value);
        }

        try {
            // send a message
            $result = $this->getClient()->send($message, $token, $this->retryTimes);
            $this->success = $result ? true : false;
        } catch (InvalidArgumentException $e) {
            $this->errors[] = $e->getMessage();
            // $deviceRegistrationId was null
        } catch (PHP_GCM\InvalidRequestException $e) {
            $this->errors[] = $e->getMessage();
            // server returned HTTP code other than 200 or 503
        } catch (CException $e) {
            $this->errors[] = $e->getMessage();
            // message could not be sent
        }

        return $message;
    }

    /**
     * send a push notification for android using GCM client
     *
     * Usage 1:
     * <code>
     * $this->sendMulti('some-valid-token','some-message',
     * array(
     *   'custom_data_key_1'=>'custom_data_value_1',
     *   'custom_data_key_2'=>'custom_data_value_2',
     * ));
     * </code>
     *
     * Usage 2:
     * <code>
     * $this->sendMulti(array('valid-token-1','valid-token-2','valid-token-3'),'some-message',
     * array(
     *   'custom_data_key_1'=>'custom_data_value_1',
     *   'custom_data_key_2'=>'custom_data_value_2',
     * ));
     * </code>
     * @param string|array $tokens
     * @param $text
     * @param array $payloadData
     * @param array $args
     * @return null|\PHP_GCM\Message
     */
    public function sendMulti($tokens, $text, $payloadData = array(), $args = array())
    {
        $tokens = is_array($tokens) ? $tokens : array($tokens);
        // check if its dry run or not
        if ($this->dryRun === true) {
            $this->log($tokens, $text, $payloadData, $args);
			$this->success = true;
            return null;
        }

        $message = new PHP_GCM\Message();
        foreach($args as $method => $value) {
            $value = is_array($value) ? $value : array($value);
            call_user_func_array(array($message, $method), $value);
        }
        // set a custom payload data
        $payloadData['message'] = $text;
        foreach($payloadData as $key=>$value){
            $message->addData($key,$value);
        }
        try {
            // send a message
            $result = $this->getClient()->sendMulti($message, $tokens, $this->retryTimes);

            $this->success = $result->getSuccess();;
        } catch (InvalidArgumentException $e) {
            $this->errors[] = $e->getMessage();
            // $deviceRegistrationId was null
        } catch (PHP_GCM\InvalidRequestException $e) {
            $this->errors[] = $e->getMessage();
            // server returned HTTP code other than 200 or 503
        } catch (CException $e) {
            $this->errors[] = $e->getMessage();
            // message could not be sent
        }

        return $message;
    }

    /**
     * auto load all files under gcm folder
     */
    public function initAutoloader()
    {
        $dir = dirname(__FILE__).'/gcm';
        $files = glob( $dir . '/*.php' );
        foreach ( $files as $file ) {
            require_once( $file );
        }
    }

    public function __call($method, $params)
    {
        $client = $this->getClient();
        if (method_exists($client, $method))
            return call_user_func_array(array($client, $method), $params);

        return parent::__call($method, $params);
    }
} 