<?php defined('C5_EXECUTE') or die("Access Denied.");

    $permissions = new Permissions( Page::getByPath('/dashboard/miss_greek') );

    // if now allowed, kill the script!
    if( ! $permissions->canViewPage() ){
        die('You do not have permission to add test data.'); exit;
    }

    // If we get here, good to roll... Define the random string generation function.
    function genRandomString( $length ){
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }


    // arguments are string, ie. "2014-02-01"
    function genRandomDate($startDate, $endDate){
        $min = strtotime($startDate);
        $max = strtotime($endDate);
        $val = mt_rand($min, $max);
        return date('Y-m-d H:i:s', $val);
    }

    // create new contestants?
    $addContestants = (int) $_REQUEST['contestants'];
    if( $addContestants >= 1 ){
        for($i = 1; $i <= $addContestants; $i++){
            $contestantObj = new MissGreekContestant(array(
                'firstName'         => genRandomString(10),
                'lastName'          => genRandomString(12),
                'houseName'         => '__test__' . genRandomString(15),
                'description'       => 'lorem ispum dolor sit amet consect et tetur lorem',
                'featuredPhotoID'   => rand(1,5)
            ));
            $contestantObj->save();
        }
    }


    // create new donation records?
    $addDonations = (int) $_REQUEST['donations'];
    if( $addDonations >= 1 ){
        $db = Loader::db();
        $contestantListObj = new MissGreekContestantList();
        $contestants       = $contestantListObj->get();
        for($i = 1; $i <= $addDonations; $i++){
            $randContestantKey = array_rand($contestants, 1);
            $donationObj = new MissGreekDonation(array(
                'contestantID'          => $contestants[$randContestantKey]->getContestantID(),
                'typeHandle'            => MissGreekDonation::TYPE_HANDLE_CREDIT_CARD,
                'firstName'             => genRandomString(mt_rand(5,20)),
                'lastName'              => genRandomString(mt_rand(5,20)),
                'email'                 => genRandomString(mt_rand(5,20)),
                'address1'              => genRandomString(mt_rand(5,20)),
                'address2'              => genRandomString(mt_rand(5,20)),
                'city'                  => genRandomString(mt_rand(5,20)),
                'state'                 => genRandomString(2),
                'zip'                   => mt_rand(10000,99999),
                'nameDisplayMethod'     => mt_rand(1,2),
                'customName'            => '___test___' . genRandomString(mt_rand(5,20)),
                'amount'                => mt_rand(15,150),
                'issueTicket'           => 0, // @todo: update for passing by input
                'authNetTransactionID'  => genRandomString(mt_rand(5,20))
            ));
            $donationObj->save();

            // now to randomize the date, we manually update the database row
            $randomDate = genRandomDate('2014-01-01', '2015-01-01');
            $db->Execute("UPDATE MissGreekDonation SET createdUTC = ?, modifiedUTC = ? WHERE id = ?", array(
                $randomDate, $randomDate, $donationObj->getDonationID()
            ));
        }
    }