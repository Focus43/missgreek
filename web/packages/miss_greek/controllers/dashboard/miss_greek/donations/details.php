<?php defined('C5_EXECUTE') or die("Access Denied.");

    class DashboardMissGreekDonationsDetailsController extends MissGreekPageController {

        public function on_start(){
            parent::on_start();
            $this->addHeaderItem($this->getHelper('html')->css('miss-greek.dashboard.css', 'miss_greek'));
            $this->addFooterItem($this->getHelper('html')->javascript('miss-greek.dashboard.js', 'miss_greek'));
        }


        public function view( $id = null ){
            $this->set('donationObj', MissGreekDonation::getByID($id));
        }

    }