<?php defined('C5_EXECUTE') or die("Access Denied.");

    $results = Loader::db()->Execute("SELECT c.id, c.firstName, c.lastName, c.houseName, ROUND(sum(mgd.amount)) AS totalRaised
    FROM MissGreekContestant c
    LEFT JOIN MissGreekDonation mgd ON mgd.contestantID = c.id
    GROUP BY c.id");

    $data = array();
    foreach($results AS $row){
        array_push($data, (object) $row);
    }

    echo Loader::helper('json')->encode($data);