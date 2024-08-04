<?php

// config.php
// version 1.1

// This dictates developer mode, in which we use
// a different connection to the database and some
// extra logging
$DEVELOPER = true;

if ($DEVELOPER) {
    $DB_USER = 'user';
    $DB_PASS = 'pass';
    $DB_HOST = 'localhost';
    $DB_NAME = 'dbname';
} else {
    $DB_USER = 'user';
    $DB_PASS = 'pass';
    $DB_HOST = 'server';
    $DB_NAME = 'dbname';
}
