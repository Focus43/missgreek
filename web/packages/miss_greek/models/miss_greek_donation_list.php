<?php

    /**
     * Class MissGreekDonationList. Query-able class for contestants.
     */
    class MissGreekDonationList extends DatabaseItemList {

        const DB_FRIENDLY_DATE = 'Y-m-d H:i:s';

        protected $autoSortColumns = array('createdUTC', 'modifiedUTC', 'firstName', 'lastName', 'email', 'city', 'state', 'amount', 'customName', 'typeHandle'),
                  $itemsPerPage    = 25;


        public function filterByKeywords($keywords) {
            $db = Loader::db();
            $this->searchKeywords = $db->quote($keywords);
            $qkeywords = $db->quote('%' . $keywords . '%');
            $this->filter(false, "(mgd.firstName LIKE $qkeywords OR mgd.lastName LIKE $qkeywords OR mgd.email LIKE $qkeywords OR customName LIKE $qkeywords)");
        }


        public function filterByTypeHandle( $handle ){
            $this->filter('mgd.typeHandle', $handle, '=');
        }


        public function get( $itemsToGet = 100, $offset = 0 ){
            $donations = array();
            $this->createQuery();
            $r = parent::get($itemsToGet, $offset);
            foreach($r AS $row){
                $donations[] = MissGreekDonation::getByID( $row['id'] );
            }
            return $donations;
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
            $this->setQuery("SELECT mgd.id FROM MissGreekDonation mgd");
        }

    }


    /**
     * Class MissGreekDonationColumnSet.
     */
    class MissGreekDonationColumnSet extends DatabaseItemListColumnSet {

        public function __construct(){
            $this->addColumn(new DatabaseItemListColumn('firstName', t('First Name'), 'getFirstName'));
            $this->addColumn(new DatabaseItemListColumn('lastName', t('Last Name'), 'getLastName'));
            $this->addColumn(new DatabaseItemListColumn('email', t('Email'), 'getEmail'));
            $this->addColumn(new DatabaseItemListColumn('amount', t('Amount'), array('MissGreekDonationColumnSet', 'formattedAmount')));
            $this->addColumn(new DatabaseItemListColumn('typeHandle', t('Method'), 'getTypeHandle'));
        }


        public function formattedAmount( MissGreekDonation $donationObj ){
            return number_format($donationObj->getAmount(), 2);
        }


        public function getCurrent(){
            return new self;
        }

    }