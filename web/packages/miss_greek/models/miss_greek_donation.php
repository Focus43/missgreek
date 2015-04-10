<?php defined('C5_EXECUTE') or die("Access Denied.");

    /**
     * To enable administrators to manually add donation records that didn't funnel through
     * the Donate page, we have a $typeHandle property (see constants below). For example, if
     * someone writes a check that should go to the contestant totals, but it wasn't run as a
     * credit card transaction.
     *
     * Class MissGreekDonation
     */
    class MissGreekDonation extends MissGreekBaseObject {

        const TYPE_HANDLE_CREDIT_CARD        = 'credit_card',
              TYPE_HANDLE_CASH_CHECK         = 'cash_check',
              NAME_DISPLAY_METHOD_CARDHOLDER = 1,
              NAME_DISPLAY_METHOD_CUSTOM     = 2,
              ISSUE_TICKET_NO                = 0,
              ISSUE_TICKET_YES               = 1;


        public function __construct( array $properties = array() ){
            parent::__construct($properties);
            $this->tableName = __CLASS__;
        }

        /** @return string Get donation record ID */
        public function getDonationID(){ return $this->id; }

        /** @return string Get ID of the contestant donation is on behalf of */
        public function getContestantID(){ return $this->contestantID; }

        /** @return string Get donation type handle */
        public function getTypeHandle(){ return $this->typeHandle; }

        /** @return string Get person's first name */
        public function getFirstName(){ return $this->firstName; }

        /** @return string Get person's last name */
        public function getLastName(){ return $this->lastName; }

        /** @return string Get person's email */
        public function getEmail(){ return $this->email; }

        /** @return string Get address line 1 */
        public function getAddress1(){ return $this->address1; }

        /** @return string Get address line 2 */
        public function getAddress2(){ return $this->address2; }

        /** @return string Get address city */
        public function getCity(){ return $this->city; }

        /** @return string Get address state */
        public function getState(){ return $this->state; }

        /** @return int Get address zip */
        public function getZip(){ return $this->zip; }

        /** @return float Get transaction amount */
        public function getAmount(){ return $this->amount; }

        /** @return int Custom name? */
        public function getNameDisplayMethod(){ return $this->nameDisplayMethod; }

        /** @return string If nameDisplayMethod is custom, this is the custom value */
        public function getCustomName(){ return $this->customName; }

        /** @return string Get authorize.net transaction ID */
        public function getAuthNetTransactionID(){ return $this->authNetTransactionID; }

        /** @return string Get the concat'd full address */
        public function getAddressString(){
            return t("%s %s %s, %s %s", $this->address1, $this->address2, $this->city, $this->state, $this->zip);
        }

        /** @return int Was a ticket issued? */
        public function getIssueTicketStatus(){ return $this->issueTicket; }


        /**
         * Get the ticket object by its ID.
         * @return MissGreekTicket
         */
        public function getTicketObj(){
            if( $this->_ticketObj === null ){
                $this->_ticketObj = MissGreekTicket::getByDonationID( $this->id );
            }
            return $this->_ticketObj;
        }


        /**
         * White-list database fields that can be saved.
         * @return array
         */
        protected function persistable(){
            return array('contestantID', 'typeHandle', 'firstName', 'lastName', 'email', 'address1', 'address2', 'city',
            'state', 'zip', 'nameDisplayMethod', 'customName', 'amount', 'issueTicket', 'authNetTransactionID');
        }


        /**
         * Persist the object to the database; only *create*, not update, since transactions should never
         * be modified.
         * @return MissGreekDonation
         */
        public function save(){
            // record already exists, do an update
            if( $this->id >= 1 ){
                // *** TRANSACTIONS ARE WRITE-ONCE ONLY *** //
            }else{
                $db 		= Loader::db();
                $fields		= $this->persistable();
                $fieldNames = "createdUTC, modifiedUTC, " . implode(',', $fields);
                $fieldCount	= implode(',', array_fill(0, count($fields), '?'));
                $values		= array();
                foreach($fields AS $property){
                    $values[] = $this->{$property};
                }
                $db->Execute("INSERT INTO {$this->tableName} ($fieldNames) VALUES (UTC_TIMESTAMP(), UTC_TIMESTAMP(), $fieldCount)", $values);
                $this->isNew = true;
                $this->id	 = $db->Insert_ID();
            }

            $self = self::getByID($this->id);
            Events::fire('update_donation_cache');
            Events::fire('donation_completed', $self);
            return $self;
        }


        /**
         * Get an instance of a donation record.
         * @param int $id
         * @return MissGreekDonation
         */
        public static function getByID( $id ){
            $self = new self();
            $row = Loader::db()->GetRow("SELECT * FROM {$self->tableName} WHERE id = ?", array( (int)$id ));
            $self->setPropertiesFromArray($row);
            return $self;
        }


        /**
         * Delete a donation record.
         * @return void
         */
        public function delete(){
            Loader::db()->Execute("DELETE FROM {$this->tableName} WHERE id = ?", array($this->id));
        }


        /* ATTRIBUTES AREN'T SUPPORTED; these are just here to honor the class Interface requirements.
        ----------------------------------------------------------------------*/
        public function clearAttribute($ak){}
        public function setAttribute($ak, $value){}
        public function getAttribute($ak, $displayMode = false){}
        public function getAttributeField($ak){}
        public function getAttributeValueObject($ak, $createIfNotFound = false){}
        public function reindex(){}

    }