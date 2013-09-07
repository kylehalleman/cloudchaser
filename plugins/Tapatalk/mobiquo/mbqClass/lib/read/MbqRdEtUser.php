<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseRdEtUser');

/**
 * user read class
 * 
 * @since  2012-8-6
 * @author Wu ZeTao <578014287@qq.com>
 */
Class MbqRdEtUser extends MbqBaseRdEtUser {
    
    public function __construct() {
    }
    
    public function makeProperty(&$oMbqEtUser, $pName, $mbqOpt = array()) {
        switch ($pName) {
            default:
            MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_PNAME . ':' . $pName . '.');
            break;
        }
    }
    
    /**
     * get user objs
     *
     * @param  Mixed  $var
     * @param  Array  $mbqOpt
     * $mbqOpt['case'] = 'byUserIds' means get data by user ids.$var is the ids.
     * @mbqOpt['case'] = 'online' means get online user.
     * @return  Array
     */
    public function getObjsMbqEtUser($var, $mbqOpt) {
        if ($mbqOpt['case'] == 'byUserIds') {
            $userIds = $var;
            foreach ($var as $userId) {
                $objsStdUser[$userId] = Gdn::UserModel()->GetID($userId);
            }
            $objsMbqEtUser = array();
            foreach ($objsStdUser as $oStdUser) {
                $objsMbqEtUser[] = $this->initOMbqEtUser($oStdUser, array('case' => 'oStdUser'));
            }
            return $objsMbqEtUser;
        } elseif ($mbqOpt['case'] == 'online') {
            if (!MbqMain::$oMbqAppEnv->check3rdPluginEnabled('WhosOnline')) {
                return array();
            } else {
                require_once(MBQ_APPEXTENTION_PATH.'ExttMbqWhosOnlineModule.php');
                $oExttMbqWhosOnlineModule = new ExttMbqWhosOnlineModule();
                $oExttMbqWhosOnlineModule->GetData();
                $arr = $oExttMbqWhosOnlineModule->exttMbqGetUsers()->Result();
                $userIds = array();
                foreach ($arr as $v) {
                    $userIds[] = $v->UserID;
                }
                return $this->getObjsMbqEtUser($userIds, array('case' => 'byUserIds'));
            }
        }
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_CASE);
    }
    
    /**
     * init one user by condition
     *
     * @param  Mixed  $var
     * @param  Array  $mbqOpt
     * $mbqOpt['case'] = 'oStdUser' means init user by oStdUser.$var is oStdUser.
     * $mbqOpt['case'] = 'byUserId' means init user by user id.$var is user id.
     * $mbqOpt['case'] = 'byLoginName' means init user by login name.$var is login name.
     * @return  Mixed
     */
    public function initOMbqEtUser($var, $mbqOpt) {
        if ($mbqOpt['case'] == 'oStdUser') {
            $oMbqEtUser = MbqMain::$oClk->newObj('MbqEtUser');
            $oMbqEtUser->userId->setOriValue($var->UserID);
            $oMbqEtUser->loginName->setOriValue($var->Name);
            $oMbqEtUser->userName->setOriValue($var->Name);
            $oMbqEtUser->iconUrl->setOriValue($var->Photo ? MbqMain::$oMbqAppEnv->rootUrl.'uploads/'.ChangeBasename($var->Photo, 'n%s') : '');
            $oMbqEtUser->canSearch->setOriValue(MbqBaseFdt::getFdt('MbqFdtUser.MbqEtUser.canSearch.range.yes'));
            $oMbqEtUser->canPm->setOriValue(MbqBaseFdt::getFdt('MbqFdtUser.MbqEtUser.canPm.range.yes'));
            $oMbqEtUser->postCount->setOriValue($var->CountComments);
            $oMbqEtUser->displayText->setOriValue('Discussions '.$var->CountDiscussions.',Comments '.$var->CountComments);
            $oMbqEtUser->regTime->setOriValue(strtotime($var->DateFirstVisit));
            $oMbqEtUser->lastActivityTime->setOriValue(strtotime($var->DateLastActive));
            //$oMbqEtUser->isOnline->setOriValue($var['oKunenaUser']->isOnline() ? MbqBaseFdt::getFdt('MbqFdtUser.MbqEtUser.isOnline.range.yes') : MbqBaseFdt::getFdt('MbqFdtUser.MbqEtUser.isOnline.range.no'));
            //$oMbqEtUser->maxAttachment->setOriValue(MbqMain::$oMbqAppEnv->oKunenaConfig->attachment_limit);
            //$oMbqEtUser->maxPngSize->setOriValue(MbqMain::$oMbqAppEnv->oKunenaConfig->imagesize * 1024);
            //$oMbqEtUser->maxJpgSize->setOriValue(MbqMain::$oMbqAppEnv->oKunenaConfig->imagesize * 1024);
            $oMbqEtUser->mbqBind['oStdUser'] = $var;
            $oMbqEtUser->canWhosonline->setOriValue(MbqBaseFdt::getFdt('MbqFdtUser.MbqEtUser.canWhosonline.range.yes'));
            $oMbqEtUser->canSendPm->setOriValue(MbqBaseFdt::getFdt('MbqFdtUser.MbqEtUser.canSendPm.range.yes'));
            return $oMbqEtUser;
        } elseif ($mbqOpt['case'] == 'byUserId') {
            $userIds = array($var);
            $objsMbqEtUser = $this->getObjsMbqEtUser($userIds, array('case' => 'byUserIds'));
            if (is_array($objsMbqEtUser) && (count($objsMbqEtUser) == 1)) {
                return $objsMbqEtUser[0];
            }
            return false;
        } elseif ($mbqOpt['case'] == 'byLoginName') {
            $oStdUser = Gdn::UserModel()->GetByUsername($var);
            if ($oStdUser) {
                return $this->initOMbqEtUser($oStdUser->UserID, array('case' => 'byUserId'));
            } else {
                return false;
            }
        }
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_CASE);
    }
    
    /**
     * get user display name
     *
     * @param  Object  $oMbqEtUser
     * @return  String
     */
    public function getDisplayName($oMbqEtUser) {
        return $oMbqEtUser->loginName->oriValue;
    }
    
    /**
     * login
     *
     * @param  String  $loginName
     * @param  String  $password
     * @return  Boolean  return true when login success.
     */
    public function login($loginName, $password) {
        $oEntryController = new EntryController();
        $oEntryController->Initialize();
        /* modified from EntryController::SignIn() */
        $oEntryController->FireEvent('SignIn');
        //$Email = $this->Form->GetFormValue('Email');
        $Email = $loginName;
        $User = Gdn::UserModel()->GetByEmail($Email);
        if (!$User)
           $User = Gdn::UserModel()->GetByUsername($Email);

        if (!$User) {
           //$this->Form->AddError('ErrorCredentials');
           return false;
        } else {
           //$ClientHour = $this->Form->GetFormValue('ClientHour');
           $ClientHour = date('Y-m-d H:i');
           $HourOffset = Gdn_Format::ToTimestamp($ClientHour) - time();
           $HourOffset = round($HourOffset / 3600);

           // Check the password.
           $PasswordHash = new Gdn_PasswordHash();
           //if ($PasswordHash->CheckPassword($this->Form->GetFormValue('Password'), GetValue('Password', $User), GetValue('HashMethod', $User))) {
           if ($PasswordHash->CheckPassword($password, GetValue('Password', $User), GetValue('HashMethod', $User))) {
              //Gdn::Session()->Start(GetValue('UserID', $User), TRUE, (bool)$this->Form->GetFormValue('RememberMe'));
              Gdn::Session()->Start(GetValue('UserID', $User), TRUE, TRUE);
              if (!Gdn::Session()->CheckPermission('Garden.SignIn.Allow')) {
                 //$this->Form->AddError('ErrorPermission');
                 Gdn::Session()->End();
                 return false;
              } else {
                 if ($HourOffset != Gdn::Session()->User->HourOffset) {
                    Gdn::UserModel()->SetProperty(Gdn::Session()->UserID, 'HourOffset', $HourOffset);
                 }
                 MbqMain::$oMbqAppEnv->oCurStdUser = $User;

                 //$this->_SetRedirect();
                 $this->initOCurMbqEtUser();
                 return true;
              }
           } else {
              //$this->Form->AddError('ErrorCredentials');
              return false;
           }
        }
    }
    
    /**
     * logout
     *
     * @return  Boolean  return true when logout success.
     */
    public function logout() {
        $oEntryController = new EntryController();
        $oEntryController->Initialize();
        /* modified from EntryController::SignOut() */
        if (MbqMain::hasLogin()) {
             $User = Gdn::Session()->User;
             
             $oEntryController->EventArguments['SignoutUser'] = $User;
             $oEntryController->FireEvent("BeforeSignOut");
             
             // Sign the user right out.
             Gdn::Session()->End();
             
             $oEntryController->EventArguments['SignoutUser'] = $User;
             $oEntryController->FireEvent("SignOut");
        }
        $oEntryController->Leaving = FALSE;
        return true;
    }
    
    /**
     * init current user obj if login
     */
    public function initOCurMbqEtUser() {
        if (MbqMain::$oMbqAppEnv->oCurStdUser) {
            MbqMain::$oCurMbqEtUser = $this->initOMbqEtUser(MbqMain::$oMbqAppEnv->oCurStdUser, array('case' => 'oStdUser'));
        }
    }
  
}

?>