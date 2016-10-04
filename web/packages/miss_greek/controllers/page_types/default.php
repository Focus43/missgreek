<?php defined('C5_EXECUTE') or die("Access Denied.");

    class DefaultPageTypeController extends MissGreekPageController {

        protected $requireHttps = true;

        public function on_start(){
            parent::on_start();
            $this->addHeaderItem('<meta name="tools-uri" content="'.MISSGREEK_TOOLS_URL.'">');
            $this->addHeaderItem('<meta name="js-uri" content="'.MISSGREEK_PACKAGE_ROOT_URL.'">');
            $this->addHeaderItem( $this->getHelper('html')->css('miss-greek.css', 'miss_greek') );
            $this->addFooterItem( $this->getHelper('html')->javascript('miss-greek.js', 'miss_greek') );

            // include live reload for for grunt watch!
            if(isset($_SERVER['VAGRANT_VM']) && ((bool) $_SERVER['VAGRANT_VM'] === true)){
                $this->addFooterItem($this->getHelper('html')->javascript('donation_test_helper.js', 'miss_greek'));
            }
        }


        public function view(){
            $this->set('formHelper', $this->getHelper('form'));
            $this->set('listHelper', $this->getHelper('lists/states_provinces'));
            $this->set('imageHelper', $this->getHelper('image'));
            $this->set('contestantList', $this->contestantList()->get());
            $this->set('ticketPrice', (int)config::get('MG_TICKET_PRICE'));

            // if user is logged in
            $userObj = new User();
            if ($userObj->isLoggedIn()) {
              $this->set('htmlClass', 'cms-admin');
            }
        }


        private function contestantList(){
            if( $this->_contestantList === null ){
                $this->_contestantList = new MissGreekContestantList();
            }
            return $this->_contestantList;
        }

    }
