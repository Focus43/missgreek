<?php defined('C5_EXECUTE') or die("Access Denied.");

/**
 * Automatically creates 5 dummy tickets, with donationID at -1
 */

    $permissions = new Permissions( Page::getByPath('/dashboard/miss_greek') );

    // if now allowed, kill the script!
    if( ! $permissions->canViewPage() ){
        die('You do not have permission to add test data.'); exit;
    }

    $db = Loader::db();
    for( $i = 1; $i <= 5; $i++ ){
        $db->Execute("INSERT INTO MissGreekTicket (ticketHash, donationID, scanStatus) VALUES(md5(UUID()),?,?)", array(
            '0', MissGreekTicket::SCAN_STATUS_UNSCANNED
        ));
    }

    echo 'Created 5 dummy tickets';