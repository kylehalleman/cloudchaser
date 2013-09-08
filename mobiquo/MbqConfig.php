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
        require_once(MBQ_CUSTOM_PATH.'customDetectJs.php');
        $this->initCfg();
    }
    
    /**
     * init cfg default value
     */
    protected function initCfg() {
        parent::initCfg();
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
        if (!MbqMain::$oMbqAppEnv->check3rdPluginEnabled('Tapatalk')) {
            $this->cfg['base']['is_open']->setOriValue(MbqBaseFdt::getFdt('MbqFdtConfig.base.is_open.range.no'));
            $this->cfg['forum']['offline']->setOriValue(MbqBaseFdt::getFdt('MbqFdtConfig.forum.offline.range.yes'));    //for json
        }
        $this->cfg['forum']['system']->setOriValue(C('Garden.Title'));  //for json
        //calculate signature for json
        $signature =  array(
            'config' => array(
                'api' => array(
                    'field' => 'GET',
                    'type'  => 'boolean',
                    'Mandatory' => 0,
                    'default'   => 0,
                ),
            ),
            'forums' => array(
            ),
            'forum' => array(
                'fid' => array(
                    'field' => 'GET',
                    'type'  => 'string',
                    'Mandatory' => 1,
                    'default'   => '',
                ),
                'content' => array(
                    'field' => 'GET',
                    'type'  => 'string',
                    'Mandatory' => 0,
                    'default' => 'topic',
                    'optional' => array('topic'),
                ),
                'page' => array(
                    'field' => 'GET',
                    'type'  => 'int',
                    'Mandatory' => 0,
                    'default' => 1,
                ),
                'perpage' => array(
                    'field' => 'GET',
                    'type'  => 'int',
                    'Mandatory' => 0,
                    'default' => 20,
                ),
                'type' => array(
                    'field' => 'GET',
                    'type'  => 'string',
                    'Mandatory' => 0,
                    'default' => 'normal',
                    'optional' => array('sticky', 'normal'),
                ),
                'prefix' => array(
                    'field' => 'GET',
                    'type'  => 'int',
                    'Mandatory' => 0,
                    'default' => '',
                ),
            ),
            'topic' => array(
                'tid' => array(
                    'field' => 'GET',
                    'type'  => 'string',
                    'Mandatory' => 1,
                    'default' => '',
                ),
                'page' => array(
                    'field' => 'GET',
                    'type'  => 'int',
                    'Mandatory' => 0,
                    'default' => 1,
                ),
                'perpage' => array(
                    'field' => 'GET',
                    'type'  => 'int',
                    'Mandatory' => 0,
                    'default' => 20,
                ),
                'order' => array(
                    'field' => 'GET',
                    'type'  => 'string',
                    'Mandatory' => 0,
                    'default' => 'asc',
                    'optional' => array('asc'),
                ),
            ),
        );
        $this->cfg['base']['api']->setOriValue($signature);
    }
    
}

?>