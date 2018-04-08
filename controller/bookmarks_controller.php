<?php
class Bookmarks_Controller
require_once("..\model\Busservice_Model.php");
require_once("..\model\Busroute_Model.php");
require_once("..\model\Busstop_Model.php");
require_once("..\model\Busarrival_Model.php");

{
    public function addBusServiceBookmark(Object $user, Object $busService);
    public function removeBusServiceBookmark(Object $user, Object $busService);
    public function addBusstopBookmark(Object $user, Object $busStop);
    public function removeBusstopBookmark(Object $user, Object $busStop);

    public function retrieveBusServiceBookmarks (Object $user);
    public function retrieveBusstopBookmarks (Object $user);
}

?>
