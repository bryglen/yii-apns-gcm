INSTALLATION
------------

in your **main.php** your configuration would look like this

```php
'components' => array(
    'apns' => array(
        'class' => 'ext.apns-gcm.YiiApns',
        'environment' => 'sandbox',
        'pemFile' => dirname(__FILE__).'/apnssert/apns-dev.pem',
        'dryRun' => false, // setting true will just do nothing when sending push notification
        // 'retryTimes' => 3,
        'options' => array(
            'sendRetryTimes' => 5
        ),
    ),
    'gcm' => array(
        'class' => 'ext.apns-gcm.YiiGcm',
        'apiKey' => 'your_api_key'
    ),
    // using both gcm and apns, make sure you have 'gcm' and 'apns' in your component
    'apnsGcm' => array(
        'class' => 'ext.apns-gcm.YiiApnsGcm',
        // custom name for the component, by default we will use 'gcm' and 'apns'
        //'gcm' => 'gcm',
        //'apns' => 'apns',
    ),
),
```

**Usage using APNS only**

```php
/* @var $apnsGcm YiiApns */
$apns = Yii::app()->apns;
$apns->send($push_tokens, $message,
  array(
    'customProperty_1' => 'Hello',
    'customProperty_2' => 'World'
  ),
  array(
    'sound'=>'default',
    'badge'=>1
  )
);
```

**Usage using GCM only**

```php
/* @var $apnsGcm YiiGcm */
$gcm = Yii::app()->gcm;
$gcm->send($push_tokens, $message,
  array(
    'customerProperty' => 1,
  ),
  array(
    'timeToLive' => 3
  ),
);
```

### Usage using APNS and GCM Together

**Send using Google Cloud Messaging**

```php
/* @var $apnsGcm YiiApnsGcm */
$apnsGcm = Yii::app()->apnsGcm;
$apnsGcm->send(YiiApnsGcm::TYPE_GCM, $push_tokens, $message,
  array(
    'customerProperty' => 1
  ),
  array(
    'timeToLive' => 3
  ),
)
```

**Send using Apple push notification service**

```php
/* @var $apnsGcm YiiApnsGcm */
$apnsGcm = Yii::app()->apnsGcm;
$apnsGcm->send(YiiApnsGcm::TYPE_APNS, $push_tokens, $message,
  array(
    'customerProperty' => 1
  ),
  array(
      'sound'=>'default',
      'badge'=>1
    )
)
```