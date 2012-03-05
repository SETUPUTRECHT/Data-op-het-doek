<?php

//Page settings
$pagename = '404';

//Load the top and the mainmenu
include('views/top.php');

//Load all CMS elements  |  After load they are in $mm->elements
include('views/elements.php');

//Load 404 page
include('views/404.php');


?>