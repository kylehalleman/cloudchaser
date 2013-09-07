<?php

defined('MBQ_IN_IT') or exit;

define('MBQ_DS', DIRECTORY_SEPARATOR);
define('MBQ_PATH', dirname(__FILE__).MBQ_DS);    /* mobiquo path */
define('MBQ_DIRNAME', basename(MBQ_PATH));    /* mobiquo dir name */
define('MBQ_PARENT_PATH', realpath(dirname(__FILE__).MBQ_DS.'..').MBQ_DS);    /* mobiquo parent dir path */
define('MBQ_FRAME_PATH', MBQ_PATH.'mbqFrame'.MBQ_DS);    /* frame path */
require_once(MBQ_FRAME_PATH.'MbqBaseConfig.php');

$_SERVER['SCRIPT_FILENAME'] = str_replace(MBQ_DIRNAME.'/', '', $_SERVER['SCRIPT_FILENAME']);  /* Important!!! */
$_SERVER['PHP_SELF'] = str_replace(MBQ_DIRNAME.'/', '', $_SERVER['PHP_SELF']);  /* Important!!! */
$_SERVER['SCRIPT_NAME'] = str_replace(MBQ_DIRNAME.'/', '', $_SERVER['SCRIPT_NAME']);    /* Important!!! */
$_SERVER['REQUEST_URI'] = str_replace(MBQ_DIRNAME.'/', '', $_SERVER['REQUEST_URI']);    /* Important!!! */

/**
 * plugin config
 * 
 * @since  2012-7-2
 * @author Wu ZeTao <578014287@qq.com>
 */
Class MbqConfig extends MbqBaseConfig {

    public function __construct() {
        parent::__construct();
        /* include custom config */
        require_once(MBQ_CUSTOM_PATH.'customConfig.php');
        $this->initCfg();
    }
    
    /**
     * init cfg default value
     */
    protected function initCfg() {
        parent::initCfg();
    }
    
    /**
     * check whether tapatalk is installed and enabled on this site,if not valid then pop error info.
     */
    public function tapatalkEnabled() {
        if (!MbqMain::$oMbqAppEnv->check3rdPluginEnabled('Tapatalk')) {
            MbqError::alert('', "Tapatalk is not valid on this site,please install and enable it first!");
        }
    }
    
    /**
     * calculate the final config of $this->cfg through $this->cfg default value and MbqMain::$customConfig and MbqMain::$oMbqAppEnv and the plugin support degree
     */
    public function calCfg() {
        parent::calCfg();
      /* calculate the final config */
        $this->cfg['base']['sys_version']->setOriValue(APPLICATION_VERSION);
        /*
        if (!MbqMain::$oMbqAppEnv->check3rdPluginEnabled('AllViewed')) {
            $this->cfg['forum']['can_unread']->setOriValue(MbqBaseFdt::getFdt('MbqFdtConfig.forum.can_unread.range.support'));
        } else {
            //the AllViewed plugin has conflict with our plugin
            $this->cfg['forum']['can_unread']->setOriValue(MbqBaseFdt::getFdt('MbqFdtConfig.forum.can_unread.range.notSupport'));
        }
        */
    }
    
}

?>