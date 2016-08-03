<?php

error_log('aaa = '.$_GET['aaa'].'
	',3,'/var/www/html/brane_framework/wp-content/debug.log') ;
error_log($_SERVER['REQUEST_URI'].'
	',3,'/var/www/html/brane_framework/wp-content/debug.log') ;
//error_log('IMG.PHP',3,'/var/www/html/brane_framework/wp-content/debug.log') ;