<?php defined('C5_EXECUTE') or die("Access Denied.");

    header('Content-Type: text/json');
    echo ConcreteRedis::db()->hget('mg_donation_cache', 'totals');