<?php defined('C5_EXECUTE') or die(_("Access Denied."));

    class MissGreekContestantAttributeKey extends AttributeKey {

        public function getIndexedSearchTable() {
            return 'MissGreekContestantSearchIndexAttributes';
        }

        protected $searchIndexFieldDefinition = 'contestantID I(11) UNSIGNED NOTNULL DEFAULT 0 PRIMARY';


        public function getAttributes($contestantID, $method = 'getValue') {
            $db = Loader::db();
            $values = $db->GetAll("select avID, akID from MissGreekContestantAttributeValues where contestantID = ?", array($contestantID));
            $avl = new AttributeValueList();
            foreach($values as $val) {
                $ak = self::getByID($val['akID']);
                if (is_object($ak)) {
                    $value = $ak->getAttributeValue($val['avID'], $method);
                    $avl->addAttributeValue($ak, $value);
                }
            }
            return $avl;
        }


        public function getAttributeValue($avID, $method = 'getValue') {
            $av = MissGreekContestantAttributeValue::getByID($avID);
            if (is_object($av)) {
                $av->setAttributeKey($this);
                return $av->{$method}();
            }
        }


        public static function getByID($akID) {
            $ak = new self();
            $ak->load($akID);
            if ($ak->getAttributeKeyID() > 0) {
                return $ak;
            }
        }


        public static function getByHandle($akHandle) {
            $ak = CacheLocal::getEntry('mg_contestant_key_by_handle', $akHandle);
            if( is_object($ak) ){
                return $ak;
            }elseif( $ak == -1 ){
                return false;
            }

            $ak = -1;
            $q = "SELECT ak.akID
					FROM AttributeKeys ak
					INNER JOIN AttributeKeyCategories akc ON ak.akCategoryID = akc.akCategoryID
					WHERE ak.akHandle = ?
					AND akc.akCategoryHandle = 'miss_greek_contestant'";
            $akID = Loader::db()->GetOne($q, array($akHandle));

            if($akID > 0){
                $ak = self::getByID( $akID );
            }

            CacheLocal::set('mg_contestant_key_by_handle', $akHandle, $ak);
            return $ak;
        }


        public static function getColumnHeaderList() {
            return parent::getList('miss_greek_contestant', array('akIsColumnHeader' => 1));
        }


        public static function getList() {
            return parent::getList('miss_greek_contestant');
        }


        public static function getSearchableList() {
            return parent::getList('miss_greek_contestant', array('akIsSearchable' => 1));
        }


        public static function getSearchableIndexedList() {
            return parent::getList('miss_greek_contestant', array('akIsSearchableIndexed' => 1));
        }


        public static function getImporterList() {
            return parent::getList('miss_greek_contestant', array('akIsAutoCreated' => 1));
        }


        public static function getUserAddedList() {
            return parent::getList('miss_greek_contestant', array('akIsAutoCreated' => 0));
        }


        public function get($akID) {
            return self::getByID($akID);
        }


        protected function saveAttribute(MissGreekContestant $contestantObj, $value = false) {
            // We check a cID/cvID/akID combo, and if that particular combination has an attribute value ID that
            // is NOT in use anywhere else on the same cID, cvID, akID combo, we use it (so we reuse IDs)
            // otherwise generate new IDs
            $av = $contestantObj->getAttributeValueObject($this, true);
            parent::saveAttribute($av, $value);
            $db = Loader::db();
            $v = array($contestantObj->getContestantID(), $this->getAttributeKeyID(), $av->getAttributeValueID());
            $db->Replace('MissGreekContestantAttributeValues', array(
                'contestantID' => $contestantObj->getContestantID(),
                'akID' => $this->getAttributeKeyID(),
                'avID' => $av->getAttributeValueID()
            ), array('contestantID', 'akID'));

            $contestantObj->reindex();
            unset($av);
            unset($contestantObj);
        }


        public function add($at, $args, $pkg = false) {
            CacheLocal::delete('mg_contestant_key_by_handle', $args['akHandle']);
            $ak = parent::add('miss_greek_contestant', $at, $args, $pkg);
            return $ak;
        }


        public function delete() {
            parent::delete();
            $db = Loader::db();
            $r = $db->Execute('select avID from MissGreekContestantAttributeValues where akID = ?', array($this->getAttributeKeyID()));
            while ($row = $r->FetchRow()) {
                $db->Execute('delete from AttributeValues where avID = ?', array($row['avID']));
            }
            $db->Execute('delete from MissGreekContestantAttributeValues where akID = ?', array($this->getAttributeKeyID()));
        }

    }


    class MissGreekContestantAttributeValue extends AttributeValue {

        public function setContestant( MissGreekContestant $contestantObj ) {
            $this->contestant = $contestantObj;
        }

        public static function getByID($avID) {
            $lav = new self();
            $lav->load($avID);
            if ($lav->getAttributeValueID() == $avID) {
                return $lav;
            }
        }

        public function delete() {
            $db = Loader::db();
            $db->Execute('delete from MissGreekContestantAttributeValues where contestantID = ? and akID = ? and avID = ?', array(
                $this->contestant->getContestantID(),
                $this->attributeKey->getAttributeKeyID(),
                $this->getAttributeValueID()
            ));
            // Before we run delete() on the parent object, we make sure that attribute value isn't being referenced in the table anywhere else
            $num = $db->GetOne('select count(avID) from MissGreekContestantAttributeValues where avID = ?', array($this->getAttributeValueID()));
            if ($num < 1) {
                parent::delete();
            }
        }

    }