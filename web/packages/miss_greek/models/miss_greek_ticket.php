<?php

    class MissGreekTicket {

        const SCAN_STATUS_UNSCANNED = 0,
              SCAN_STATUS_SCANNED   = 1;

        protected $tableName;

        protected function __construct( array $properties = array() ){
            $this->tableName = __CLASS__;
            $this->setPropertiesFromArray($properties);
        }

        public function getTicketHash(){ return $this->ticketHash; }
        public function getDonationID(){ return $this->donationID; }
        public function getScanStatus(){ return $this->scanStatus; }


        /**
         * Get fully qualified URL to the ticket.
         * @return string
         */
        public function getTicketLinkURL(){
            return BASE_URL . View::url( sprintf('/tix?t=%s', $this->getTicketHash()) );
        }


        /**
         * Check whether the ticket has been scanned or not.
         * @return bool
         */
        public function hasItBeenScanned(){
            return (bool) (self::SCAN_STATUS_SCANNED === (int) $this->scanStatus);
        }


        /**
         * Mark the ticket as having been scanned.
         */
        public function markAsScanned(){
            Loader::db()->Execute("UPDATE {$this->tableName} SET scanStatus = 1 WHERE ticketHash = ?", array(
                $this->ticketHash
            ));
        }


        /**
         * Get a ticket by its hash code.
         * @param $md5
         * @return MissGreekTicket
         */
        public static function getByHash( $md5 ){
            $self = new self();
            $data = Loader::db()->GetRow("SELECT * FROM {$self->tableName} WHERE ticketHash = ?", array($md5));
            $self->setPropertiesFromArray($data);
            return $self;
        }


        /**
         * Get a ticket by the donation id.
         * @param $id
         * @return MissGreekTicket
         */
        public static function getByDonationID( $id ){
            $self = new self();
            $data = Loader::db()->GetRow("SELECT * FROM {$self->tableName} WHERE donationID = ?", array($id));
            $self->setPropertiesFromArray($data);
            return $self;
        }


        /**
         * Create a new ticket with a unique hash code. The hash function uses MySQLs
         * UUID param into an md5 function, so is irrelevant of any specific data being
         * hashed.
         * @param MissGreekDonation $donationObj
         * @return void
         */
        public static function create( MissGreekDonation $donationObj ){
            Loader::db()->Execute("INSERT INTO MissGreekTicket (ticketHash, donationID, scanStatus) VALUES(md5(UUID()),?,?)", array(
                $donationObj->getDonationID(), self::SCAN_STATUS_UNSCANNED
            ));
        }


        /**
         * Set the class properties.
         * @param array $properties
         * @return void
         */
        protected function setPropertiesFromArray( array $properties = array() ) {
            foreach($properties as $key => $prop) {
                $this->{$key} = $prop;
            }
        }

    }