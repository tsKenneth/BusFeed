<?php
class Bookmarks_Controller
{
    public function addBusServiceBookmark(Object $user, Object $busService);
    public function removeBusServiceBookmark(Object $user, Object $busService);
    public function addBusstopBookmark(Object $user, Object $busStop);
    public function removeBusstopBookmark(Object $user, Object $busStop);
    
    public function retrieveBusServiceBookmarks (Object $user);
    public function retrieveBusstopBookmarks (Object $user);
}

?>