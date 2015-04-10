<?php defined('C5_EXECUTE') or die("Access Denied.");

    class MissGreekDonationEvents {

        /**
         * When a donation is completed, do this (right now used only to creating a
         * ticket record).
         * @param MissGreekDonation $donationObj
         * @return void
         */
        public function donationCompleted( MissGreekDonation $donationObj ){
            try {
                // Is a ticket being issued?
                if( (int)$donationObj->getIssueTicketStatus() === MissGreekDonation::ISSUE_TICKET_YES ){
                    MissGreekTicket::create($donationObj);
                }

                // update donors cache
                $list = Loader::db()->Execute("SELECT mgd.firstName, mgd.lastName, mgd.nameDisplayMethod, mgd.customName FROM MissGreekDonation mgd WHERE contestantID = ?", array(
                    $donationObj->getContestantID()
                ));

                $data = array();
                foreach($list AS $row){
                    array_push($data, (object) array(
                        'name'  => ((int)$row['nameDisplayMethod'] === MissGreekDonation::NAME_DISPLAY_METHOD_CUSTOM) ? $row['customName'] : sprintf('%s %s', $row['firstName'], $row['lastName'])
                    ));
                }

                ConcreteRedis::db()->hset('mg_donors', $donationObj->getContestantID(), Loader::helper('json')->encode($data));
            }catch(Exception $e){
                Log::addEntry("Error updating Donation Cache In Redis: \n {$e->getMessage()}", 'miss_greek');
            }
        }


        /**
         * Event hook that gets fired when a donation is successfully made. All this does
         * is invalidate/update the cached donation results in the Redis data store.
         * Nothing is actually done with the donation object passed in.
         * @param MissGreekDonation $donationObj
         * @return void
         */
        public function updateDonationCache(){
            try {
                // query for the results
                $totals = Loader::db()->Execute("SELECT c.id, c.firstName, c.lastName, c.houseName, sum(mgd.amount) AS totalRaised FROM MissGreekContestant c
                LEFT JOIN MissGreekDonation mgd ON mgd.contestantID = c.id
                GROUP BY c.id");

                // push to array, and then serialize it for storage
                $data = array();
                foreach($totals AS $row){
                    array_push($data, (object) array(
                        'contestantID'  => $row['id'],
                        'name'          => sprintf('%s %s', $row['firstName'], $row['lastName']),
                        'houseName'     => $row['houseName'],
                        'y'             => (float)$row['totalRaised']
                    ));
                }

                // cache results
                ConcreteRedis::db()->hset('mg_donation_cache', 'totals', Loader::helper('json')->encode($data));
            }catch(Exception $e){
                Log::addEntry("Error updating Donation Cache In Redis: \n {$e->getMessage()}", 'miss_greek');
            }
        }

    }