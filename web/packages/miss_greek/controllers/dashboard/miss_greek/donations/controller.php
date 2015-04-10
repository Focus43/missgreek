<?php defined('C5_EXECUTE') or die("Access Denied.");

    class DashboardMissGreekDonationsController extends MissGreekPageController {

        public function on_start(){
            parent::on_start();
            $this->addHeaderItem($this->getHelper('html')->css('miss-greek.dashboard.css', 'miss_greek'));
            $this->addFooterItem($this->getHelper('html')->javascript('miss-greek.dashboard.js', 'miss_greek'));
        }


        public function view(){
            $this->set('formHelper', $this->getHelper('form'));
            $searchInstance = 'donations' . time();
            $this->addFooterItem('<script type="text/javascript">$(function() { ccm_setupAdvancedSearch(\''.$searchInstance.'\'); });</script>');
            $this->set('searchInstance', $searchInstance);
            $this->set('listObject', $this->donationsListObj());
            $this->set('listResults', $this->donationsListObj()->getPage());
        }


        /**
         * Get the donation list object.
         * @return MissGreekDonationList
         */
        public function donationsListObj(){
            if( $this->_donationsListObj === null ){
                $this->_donationsListObj = new MissGreekDonationList();
                $this->applySearchFilters($this->_donationsListObj);
            }
            return $this->_donationsListObj;
        }


        /**
         * Set any filters, if applicable.
         * @param MissGreekDonationList $listObj
         * @return void
         */
        private function applySearchFilters( MissGreekDonationList $listObj ){
            if( !empty($_REQUEST['keywords']) ){
                $listObj->filterByKeywords( $_REQUEST['keywords'] );
            }

            if( !empty($_REQUEST['numResults']) ){
                $listObj->setItemsPerPage( $_REQUEST['numResults'] );
            }

            if( !empty($_REQUEST['typeHandle']) ){
                $listObj->filterByTypeHandle($_REQUEST['typeHandle']);
            }
        }

    }