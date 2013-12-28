<?php
/**
 * @author Bryan Jayson Tan <admin@bryantan.info>
 * @link http://bryantan.info
 * @date 7/27/13
 * @time 3:43 PM
 */
class AAPNSLog implements ApnsPHP_Log_Interface
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
