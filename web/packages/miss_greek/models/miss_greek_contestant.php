<?php

    class MissGreekContestant extends MissGreekBaseObject {

        protected $attrCategoryHandle = 'miss_greek_contestant';


        public function __construct( array $properties = array() ){
            $this->setPropertiesFromArray($properties);
            $this->tableName = __CLASS__;
        }


        public function __toString(){
            return ucwords("{$this->firstName} {$this->lastName}");
        }

        /** @return int Get contestantID */
        public function getContestantID(){ return $this->id; }

        /** @return string Get person's first name */
        public function getFirstName(){ return $this->firstName; }

        /** @return string Get person's last name */
        public function getLastName(){ return $this->lastName; }

        /** @return string Get house name member belongs to */
        public function getHouseName(){ return $this->houseName; }

        /** @return string Get contestant description */
        public function getDescription(){ return $this->description; }

        /** @return string Get ID of the featured photo file Obj */
        public function getFeaturedPhotoID(){ return $this->featuredPhotoID; }


        /**
         * Get the featured photo as a file object.
         * @return File
         */
        public function getFeaturedPhotoObj(){
            if( $this->_featuredPhotoObj === null ){
                $this->_featuredPhotoObj = File::getByID( $this->featuredPhotoID );
            }
            return $this->_featuredPhotoObj;
        }


        /**
         * White-listed database fields that can be saved.
         * @return array
         */
        protected function persistable(){
            return array('firstName', 'lastName', 'houseName', 'description', 'featuredPhotoID');
        }


        public function save(){
            $db = Loader::db();

            // record already exists, do an update
            if( $this->id >= 1 ){
                $fields		= $this->persistable();
                $updateStr  = 'modifiedUTC = UTC_TIMESTAMP()';
                $values		= array();
                foreach($fields AS $property){
                    $updateStr .= ", {$property} = ?";
                    $values[] = $this->{$property};
                }
                $values[] = $this->id;
                $db->Execute("UPDATE {$this->tableName} SET {$updateStr} WHERE id = ?", $values);
            // record hasn't been persisted yet, create it
            }else{
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

                Events::fire('update_donation_cache');
            }

            // save attributes
            $attrKeys = MissGreekContestantAttributeKey::getList();
            foreach($attrKeys AS $akObj){
                $akObj->saveAttributeForm( $this );
            }

            return self::getByID( $this->id );
        }


        /**
         * Get a MissGreekContestant instance by ID.
         * @param int $id
         * @return MissGreekContestant
         */
        public static function getByID( $id ){
            $self = new self();
            $row = Loader::db()->GetRow("SELECT * FROM {$self->tableName} WHERE id = ?", array( (int)$id ));
            $self->setPropertiesFromArray($row);
            return $self;
        }


        /**
         * Delete the record, and any attribute values associated w/ it
         * @return void
         */
        public function delete(){
            $db = Loader::db();
            $db->Execute("DELETE FROM MissGreekContestantAttributeValues WHERE contestantID = ?", array($this->id));
            $db->Execute("DELETE FROM MissGreekContestantSearchIndexAttributes WHERE contestantID = ?", array($this->id));
            $db->Execute("DELETE FROM {$this->tableName} WHERE id = ?", array($this->id));
        }


        /* Attribute association stuff
        ----------------------------------------------------------------------*/
        public function clearAttribute($ak){
            parent::clearAttribute($ak);
        }


        public function setAttribute($ak, $value) {
            parent::setAttribute($ak, $value);
        }


        public function getAttribute($ak, $displayMode = false) {
            return parent::getAttribute( $ak, $displayMode );
        }


        public function getAttributeField($ak){
            parent::getAttributeField( $ak );
        }


        public function getAttributeValueObject($ak, $createIfNotFound = false) {
            return parent::getAttributeValueObjectGeneric( $ak, $createIfNotFound, array(
                'table'			=> 'MissGreekContestantAttributeValues',
                'idColumn'		=> 'contestantID',
                'attrValClass'	=> 'MissGreekContestantAttributeValue',
                'setObjMethod'	=> 'setContestant'
            ));
        }


        public function reindex() {
            parent::reindexGeneric(array(
                'table'		=> 'MissGreekContestantSearchIndexAttributes',
                'idColumn'	=> 'contestantID'
            ));
        }

    }