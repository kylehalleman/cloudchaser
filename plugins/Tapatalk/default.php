<?php if (!defined('APPLICATION')) exit();
 
$PluginInfo['Tapatalk'] = array(
   'Name' => 'Tapatalk',
   'Description' => 'Tapatalk Plugin for Vanilla 2',
   'Version' => 'vn20_1.3.8',
   'Author' => "Tapatalk",
   'AuthorEmail' => 'admin@tapatalk.com',
   'AuthorUrl' => 'http://tapatalk.com',
   'MobileFriendly' => true
);

class TapatalkPlugin extends Gdn_Plugin {
    
    /*
    public function DiscussionController_BeforeDiscussionRender_Handler($Sender) {
        $Sender->AddJsFile('/mobiquo/appExtt/ExttMbqBeforeRunDetectJs.php');
        $Sender->AddJsFile('/mobiquo/tapadetect.js');
    }
    */
    
    public function Base_Render_Before($Sender) {
        if (defined('MBQ_IN_IT')) return;   //filter mobiquo folder
        if (($_REQUEST['p'] && strpos($_REQUEST['p'], '/dashboard') === 0) || $Sender->MasterView == 'admin') { //filter the backend page
            return;
        }
        $isSsl = false;
        if($_SERVER['HTTPS'] === 1){  //Apache
            $isSsl = true;
        }elseif($_SERVER['HTTPS'] === 'on'){ //IIS
            $isSsl = true;
        }elseif($_SERVER['SERVER_PORT'] == 443){ //other
            $isSsl = true;
        }
        $protocol = $isSsl ? 'https' : 'http';
        $phpSelf = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
        $base = $protocol.'://'.$_SERVER['SERVER_NAME'].preg_replace('/index\.php.*/i', '', $phpSelf);
        $filePath = dirname(__FILE__).'/../../mobiquo/custom/customSmartbanner.php';
        if (is_file($filePath)) {
            require_once($filePath);
            MbqSmartbanner::$functionCallAfterWindowLoad = 1;
            $functionCallAfterWindowLoad = MbqSmartbanner::$functionCallAfterWindowLoad;
    		if (MbqSmartbanner::$IS_MOBILE_SKIN) $is_mobile_skin = MbqSmartbanner::$IS_MOBILE_SKIN;
    		if (MbqSmartbanner::$APP_IOS_ID) $app_ios_id = MbqSmartbanner::$APP_IOS_ID;
    		if (MbqSmartbanner::$APP_ANDROID_ID) $app_android_id = MbqSmartbanner::$APP_ANDROID_ID;
    		if (MbqSmartbanner::$APP_KINDLE_URL) $app_kindle_url = MbqSmartbanner::$APP_KINDLE_URL;
    		if (MbqSmartbanner::$APP_BANNER_MESSAGE) $app_banner_message = MbqSmartbanner::$APP_BANNER_MESSAGE;
    		MbqSmartbanner::$APP_FORUM_NAME = C('Garden.Title');
    		$app_forum_name = MbqSmartbanner::$APP_FORUM_NAME;
            MbqSmartbanner::$APP_LOCATION_URL = 'tapatalk://'.preg_replace('/http[s]?\:\/\/(.*?)/i', '$1', $base).'?location=index';
            $app_location_url = MbqSmartbanner::$APP_LOCATION_URL;
            MbqSmartbanner::$BOARD_URL = substr($base, 0, strlen($base) - 1);       //!
            $board_url = MbqSmartbanner::$BOARD_URL;
            $tapatalk_dir = MbqSmartbanner::$TAPATALKDIR;
            MbqSmartbanner::$TAPATALKDIR_URL = $base.MbqSmartbanner::$TAPATALKDIR;
            $tapatalk_dir_url = MbqSmartbanner::$TAPATALKDIR_URL;
            if (file_exists($tapatalk_dir . '/smartbanner/head.inc.php'))
                require_once($tapatalk_dir . '/smartbanner/head.inc.php');
            //header code
            $Sender->Head->AddString($app_head_include);
            $Sender->Head->AddString('
            <script type="text/javascript" language="Javascript">
            jQuery(document).ready(function($){
                tapatalkDetect();
            })
            </script>
            ');
        }
    }
    
    /*
    public function Base_Render_Before($Sender) {
        //$forumTitle = C('Garden.Title');
        //$forumTitle = Gdn::Config('Garden.Title');
        if (($_REQUEST['p'] && strpos($_REQUEST['p'], '/dashboard') === 0) || $Sender->MasterView == 'admin') { //filter the backend page
            return;
        }
        $Sender->AddCssFile('/mobiquo/smartbanner/appbanner.css');
        $Sender->AddJsFile('/mobiquo/appExtt/ExttMbqBeforeRunSmartbanner.php');
        $Sender->AddJsFile('/mobiquo/smartbanner/appbanner.js');
        $Sender->Head->AddString('
        <script type="text/javascript" language="Javascript">
        jQuery(document).ready(function($){
            tapatalkDetect();
        })
        </script>
        ');
    }
    */
    
    /*
    public function DiscussionController_BeforeDiscussionRender_Handler($Sender) {
        //$forumTitle = C('Garden.Title');
        //$forumTitle = Gdn::Config('Garden.Title');
        $Sender->AddCssFile('/mobiquo/smartbanner/appbanner.css');
        $Sender->AddJsFile('/mobiquo/appExtt/ExttMbqBeforeRunSmartbanner.php');
        $Sender->AddJsFile('/mobiquo/smartbanner/appbanner.js');
        $Sender->Head->AddString('
        <script type="text/javascript" language="Javascript">
        jQuery(document).ready(function($){
            tapatalkDetect();
        })
        </script>
        ');
    }
    */
    
    /*
    public function DiscussionController_BeforeDiscussionRender_Handler($Sender) {
        $filePath = dirname(__FILE__).'/../../mobiquo/custom/customSmartbanner.php';
        if (is_file($filePath)) {
            require_once($filePath);
            $Sender->AddCssFile('/mobiquo/smartbanner/appbanner.css');
            MbqSmartbanner::$MBQ_SMARTBANNER_APP_FORUM_NAME = C('Garden.Title');
            $str = '';
            if (MbqSmartbanner::$MBQ_SMARTBANNER_IS_MOBILE_SKIN)
            $str .= 'var is_mobile_skin = '.MbqSmartbanner::$MBQ_SMARTBANNER_IS_MOBILE_SKIN.';';
            if (MbqSmartbanner::$MBQ_SMARTBANNER_APP_IOS_ID)
            $str .= 'var app_ios_id = "'.MbqSmartbanner::$MBQ_SMARTBANNER_APP_IOS_ID.'";';
            if (MbqSmartbanner::$MBQ_SMARTBANNER_APP_ANDROID_URL)
            $str .= 'var app_android_url = "'.MbqSmartbanner::$MBQ_SMARTBANNER_APP_ANDROID_URL.'";';
            if (MbqSmartbanner::$MBQ_SMARTBANNER_APP_KINDLE_URL)
            $str .= 'var app_kindle_url = "'.MbqSmartbanner::$MBQ_SMARTBANNER_APP_KINDLE_URL.'";';
            if (MbqSmartbanner::$MBQ_SMARTBANNER_APP_BANNER_MESSAGE)
            $str .= 'var app_banner_message = "'.MbqSmartbanner::$MBQ_SMARTBANNER_APP_BANNER_MESSAGE.'";';
            if (MbqSmartbanner::$MBQ_SMARTBANNER_APP_FORUM_NAME)
            $str .= 'var app_forum_name = "'.MbqSmartbanner::$MBQ_SMARTBANNER_APP_FORUM_NAME.'";';
            if (MbqSmartbanner::$MBQ_SMARTBANNER_APP_LOCATION_URL)
            $str .= 'var app_location_url = "'.MbqSmartbanner::$MBQ_SMARTBANNER_APP_LOCATION_URL.'";';
            //since the added string is always behind the added js file,so it is useless for the smartbanner js code
            $Sender->Head->AddString('
            <script type="text/javascript" language="Javascript">
            '.$str.'
            </script>
            ');
            $Sender->AddJsFile('/mobiquo/smartbanner/appbanner.js');
        }
    }
    */

	public function Setup() {
	}
	
}

?>