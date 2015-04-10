<?php defined('C5_EXECUTE') or die("Access Denied.");

    class DashboardMissGreekDonationsEditController extends MissGreekPageController {

        public function on_start(){
            $permissions = new Permissions( Page::getByPath('/dashboard/miss_greek/donations/edit') );
            if( ! $permissions->canViewPage() ){
                $this->flash('You do not have permission to edit donations!', self::FLASH_TYPE_ERROR);
                $this->redirect('/dashboard/miss_greek/donations');
            }

            parent::on_start();
            $this->addHeaderItem($this->getHelper('html')->css('miss-greek.dashboard.css', 'miss_greek'));
            $this->addFooterItem($this->getHelper('html')->javascript('miss-greek.dashboard.js', 'miss_greek'));
        }


        public function view( $id = null ){
            $this->set('formHelper', $this->getHelper('form'));
            $contestantListObj = new MissGreekContestantList;
            $this->set('contestantList', $this->getHelper('list_transforms', 'miss_greek')->contestantSelectList($contestantListObj->get()));
            $this->set('donationObj', MissGreekDonation::getByID($id));
        }


        /**
         * Update a donation record. We break from the standard way of managing CRUD operations
         * through the model and update the database row directly from here.
         * @param int|null $donationID
         */
        public function save( $donationID = null ){
            $donationObj = MissGreekDonation::getByID($donationID);
            if( $donationObj->getDonationID() >= 1 ){
                $data = $_REQUEST['donate'];
                Loader::db()->Execute("UPDATE MissGreekDonation SET modifiedUTC = UTC_TIMESTAMP(), contestantID = ?, firstName = ?, lastName = ?, email = ?, nameDisplayMethod = ?, customName = ?, amount = ? WHERE id = ?", array(
                    $data['contestantID'], $data['firstName'], $data['lastName'], $data['email'], $data['nameDisplayMethod'], $data['customName'], (int)$data['amount'], $donationObj->getDonationID()
                ));
                Events::fire('update_donation_cache');
                // do another ...getByID so we reload the object after having been updatedd
                Events::fire('donation_completed', MissGreekDonation::getByID($donationObj->getDonationID()));
                $this->flash('Donation record updated.');
                $this->redirect('/dashboard/miss_greek/donations');
                return;
            }

            // failure
            $this->flash('The donation could not be updated...', self::FLASH_TYPE_ERROR);
            $this->redirect('/dashboard/miss_greek/donations');
        }

    }