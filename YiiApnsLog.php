<?php

class YiiApnsLog implements ApnsPHP_Log_Interface
{
    /**
     * Logs a message.
     * @param  $sMessage @type string The message.
     */
    public function log($sMessage)
    {
        Yii::log($sMessage, CLogger::LEVEL_INFO, 'AAPNS');
    }
}
