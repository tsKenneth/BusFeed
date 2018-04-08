<?php
interface iInformation_Controller
{
    // Returns an array of information
    public static function retrieveAllInfo();
    public static function retrieveInfo(string $specifier);
}

?>
