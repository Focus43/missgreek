<?php defined('C5_EXECUTE') or die("Access Denied.");

    class DashboardMissGreekDonationsAddController extends MissGreekPageController {

        public function on_start(){
            parent::on_start();
            $this->addHeaderItem($this->getHelper('html')->css('miss-greek.dashboard.css', 'miss_greek'));
            $this->addFooterItem($this->getHelper('html')->javascript('miss-greek.dashboard.js', 'miss_greek'));
        }


        public function view(){
            $this->set('formHelper', $this->getHelper('form'));
            $contestantListObj = new MissGreekContestantList;
            $this->set('contestantList', $this->getHelper('list_transforms', 'miss_greek')->contestantSelectList($contestantListObj->get()));
        }


        /**
         * @todo: add validation in!
         */
        public function save(){
            try {
                $formData    = $_POST['donate'];
                $donationObj = new MissGreekDonation(array(
                    'contestantID'      => $formData['contestantID'],
                    'typeHandle'        => MissGreekDonation::TYPE_HANDLE_CASH_CHECK,
                    'firstName'         => $formData['firstName'],
                    'lastName'          => $formData['lastName'],
                    'email'             => $formData['email'],
                    'nameDisplayMethod' => $formData['nameDisplayMethod'],
                    'customName'        => $formData['customName'],
                    'amount'            => $formData['amount'],
                    'issueTicket'       => MissGreekDonation::ISSUE_TICKET_NO
                ));
                $donationObj->save();
                $this->flash('Cash/Check Donation Added.');
                $this->redirect('/dashboard/miss_greek/donations');
            }catch(Exception $e){
                die('An error occurred! Please call Jon Hartman @ 443-956-5918 right away.');
            }
        }

    }