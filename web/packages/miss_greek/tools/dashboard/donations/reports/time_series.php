<?php defined('C5_EXECUTE') or die("Access Denied.");

    $results = Loader::db()->Execute("SELECT YEAR(DATE(createdUTC)) AS year,
    MONTH(DATE(createdUTC))-1 AS month,
    DAY(DATE(createdUTC)) AS day,
    ROUND(SUM(amount)) AS amount FROM MissGreekDonation
    GROUP BY DATE(createdUTC)
    ORDER BY createdUTC ASC");

    $data = array();
    foreach($results AS $row){
        array_push($data, (object) $row);
    }

    echo Loader::helper('json')->encode($data);