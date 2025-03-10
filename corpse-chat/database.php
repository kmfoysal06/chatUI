<?php

require 'dbh.php';

$dbh = new DatabaseHandler();
/**
* Initial DB Queries To Perform
*/
if(!$dbh->tableExists('patterns')) {
	$init_patterns_query = "CREATE TABLE `corpse-chat`.`patterns` (`id` INT NOT NULL AUTO_INCREMENT , `pattern` TEXT NOT NULL , `time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`))";
if(!$dbh->query($init_patterns_query)) {
die("their is error with the model database");	
	}
}
