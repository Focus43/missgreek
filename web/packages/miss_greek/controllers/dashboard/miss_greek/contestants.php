<?php defined('C5_EXECUTE') or die("Access Denied.");

    class DashboardMissGreekContestantsController extends MissGreekPageController {

        public function on_start(){
            $this->addHeaderItem($this->getHelper('html')->css('miss-greek.dashboard.css', 'miss_greek'));
            $this->addFooterItem($this->getHelper('html')->javascript('miss-greek.dashboard.js', 'miss_greek'));
        }


        public function view(){
            $this->set('formHelper', $this->getHelper('form'));
            $searchInstance = 'contestants' . time();
            $this->addFooterItem('<script type="text/javascript">$(function() { ccm_setupAdvancedSearch(\''.$searchInstance.'\'); });</script>');
            $this->set('searchInstance', $searchInstance);
            $this->set('listObject', $this->contestantsListObj());
            $this->set('listResults', $this->contestantsListObj()->get());
        }


        /**
         * Get the contestant list object.
         * @return MissGreekContestantList
         */
        public function contestantsListObj(){
            if( $this->_contestantsListObj === null ){
                $this->_contestantsListObj = new MissGreekContestantList();
                $this->applySearchFilters($this->_contestantsListObj);
            }
            return $this->_contestantsListObj;
        }


        /**
         * Set any filters, if applicable.
         * @param MissGreekContestantList $listObj
         * @return MissGreekContestantList
         */
        private function applySearchFilters( MissGreekContestantList $listObj ){
            if( !empty($_REQUEST['keywords']) ){
                $listObj->filterByKeywords( $_REQUEST['keywords'] );
            }

            if( !empty($_REQUEST['numResults']) ){
                $listObj->setItemsPerPage( $_REQUEST['numResults'] );
            }

            return $listObj;
        }

    }