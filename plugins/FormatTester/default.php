<?php if (!defined('APPLICATION')) exit();
// Define the plugin:
$PluginInfo['FormatTester'] = array(
   'Name' => 'FormatTester',
   'Description' => ' my format tester',
   'Version' => '1',
   'Author' => "Peregrine",
);
class TesterPlugin extends Gdn_Plugin {
  public function DiscussionController_AfterCommentMeta_Handler($Sender) {
      //Put you screen name in the following statement and only you will see info
      $NAME = "BurkeNight";
      // --------------------------------------
       $Session = Gdn::Session();
      if ($Session->User->Name == $NAME) {
      $format = $Sender->EventArguments['Discussion']->Format;
       $Formatter = C('Garden.InputFormatter', 'Html');
        echo "config Format is $Formatter <br />";
        $cformat = $Sender->EventArguments['Comment']->Format;
        $dformat = $Sender->EventArguments['Discussion']->Format;
        if (!$cformat) 
       echo "this discussion is format - $dformat in discussion table<br />"; 
       if ($cformat) 
       echo "this comment is $cformat in comment table";
     }        
  }
   public function Setup() {
   }
}