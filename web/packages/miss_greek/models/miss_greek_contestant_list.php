<?php

    /**
     * Class MissGreekContestantList. Query-able class for contestants.
     */
    class MissGreekContestantList extends DatabaseItemList {

        const DB_FRIENDLY_DATE = 'Y-m-d H:i:s';

        protected $autoSortColumns = array('createdUTC', 'modifiedUTC', 'firstName', 'lastName', 'houseName'),
                  $itemsPerPage    = 10;


        public function filterByKeywords($keywords) {
            $db = Loader::db();
            $this->searchKeywords = $db->quote($keywords);
            $qkeywords = $db->quote('%' . $keywords . '%');
            $this->filter(false, "(mgc.firstName LIKE $qkeywords OR mgc.lastName LIKE $qkeywords)");
        }


        public function get( $itemsToGet = 100, $offset = 0 ){
            $contestants = array();
            $this->createQuery();
            $r = parent::get($itemsToGet, $offset);
            foreach($r AS $row){
                $contestants[] = MissGreekContestant::getByID( $row['id'] );
            }
            return $contestants;
        }


        public function getTotal(){
            $this->createQuery();
            return parent::getTotal();
        }


        protected function createQuery(){
            if( ! $this->queryCreated ){
                $this->setBaseQuery();
                $this->queryCreated = true;
            }
        }


        protected function setBaseQuery(){
            $this->setQuery("SELECT mgc.id FROM MissGreekContestant mgc");
        }

    }


    /**
     * Class MissGreekContestantColumnSet.
     */
    class MissGreekContestantColumnSet extends DatabaseItemListColumnSet {

        public function __construct(){
            $this->addColumn(new DatabaseItemListColumn('lastName', t('Name'), array('MissGreekContestantColumnSet', 'getNameAsLast')));
            $this->addColumn(new DatabaseItemListColumn('houseName', t('House Name'), 'getHouseName'));
        }

        public function getNameAsLast( MissGreekContestant $contestantObj ){
            $name = "{$contestantObj->getLastName()}, {$contestantObj->getFirstName()}";
            return '<a href="'.View::url('dashboard/miss_greek/edit_contestant', $contestantObj->getContestantID()).'">'.$name.'</a>';
        }

        public function getCurrent(){
            return new self;
        }

    }