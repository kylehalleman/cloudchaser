<?php

defined('MBQ_IN_IT') or exit;

/**
 * user custom config,to replace some config of MbqMain::$oMbqConfig->cfg.
 * you can change any config if you need,please refer to MbqConfig.php for more details.
 * 
 * @since  2012-7-19
 * @author Wu ZeTao <578014287@qq.com>
 */
MbqMain::$customConfig['base']['is_open'] = MbqBaseFdt::getFdt('MbqFdtConfig.base.is_open.range.yes');
MbqMain::$customConfig['base']['version'] = 'vn20_1.1.0';
MbqMain::$customConfig['base']['api_level'] = 3;

MbqMain::$customConfig['user']['guest_okay'] = MbqBaseFdt::getFdt('MbqFdtConfig.user.guest_okay.range.support');
MbqMain::$customConfig['user']['user_id'] = MbqBaseFdt::getFdt('MbqFdtConfig.user.user_id.range.support');

MbqMain::$customConfig['forum']['get_latest_topic'] = MbqBaseFdt::getFdt('MbqFdtConfig.forum.get_latest_topic.range.support');
MbqMain::$customConfig['forum']['guest_search'] = MbqBaseFdt::getFdt('MbqFdtConfig.forum.guest_search.range.support');
MbqMain::$customConfig['forum']['can_unread'] = MbqBaseFdt::getFdt('MbqFdtConfig.forum.can_unread.range.support');

MbqMain::$customConfig['pc']['module_enable'] = MbqMain::$oClk->newObj('MbqValue', array('oriValue' => MbqBaseFdt::getFdt('MbqFdtConfig.pc.module_enable.range.enable')));
MbqMain::$customConfig['pc']['conversation'] = MbqBaseFdt::getFdt('MbqFdtConfig.pc.conversation.range.support');

?>