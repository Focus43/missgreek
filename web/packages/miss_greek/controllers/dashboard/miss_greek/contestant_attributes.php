<?php

    class DashboardMissGreekContestantAttributesController extends Controller {

        public $helpers = array('form');

        public function __construct() {
            parent::__construct();
            $otypes = AttributeType::getList('miss_greek_contestant');
            $types = array();
            foreach($otypes as $at) {
                $types[$at->getAttributeTypeID()] = $at->getAttributeTypeName();
            }
            $this->set('types', $types);
        }

        public function delete($akID, $token = null){
            try {
                $ak = MissGreekContestantAttributeKey::getByID($akID);

                if(!($ak instanceof MissGreekContestantAttributeKey)) {
                    throw new Exception(t('Invalid attribute ID.'));
                }

                $valt = Loader::helper('validation/token');
                if (!$valt->validate('delete_attribute', $token)) {
                    throw new Exception($valt->getErrorMessage());
                }

                $ak->delete();

                $this->redirect("/dashboard/miss_greek/contestant_attributes", 'attribute_deleted');
            } catch (Exception $e) {
                $this->set('error', $e);
            }
        }

        public function select_type() {
            $atID = $this->request('atID');
            $at = AttributeType::getByID($atID);
            $this->set('type', $at);
        }

        public function view() {
            $attribs = MissGreekContestantAttributeKey::getList();
            $this->set('attribs', $attribs);
        }

        public function on_start() {
            $this->set('category', AttributeKeyCategory::getByHandle('miss_greek_contestant'));
        }

        public function add() {
            $this->select_type();
            $type = $this->get('type');
            $cnt = $type->getController();
            $e = $cnt->validateKey($this->post());
            if ($e->has()) {
                $this->set('error', $e);
            } else {
                $type = AttributeType::getByID($this->post('atID'));
                $ak = MissGreekContestantAttributeKey::add($type, $this->post());
                $this->redirect('/dashboard/miss_greek/contestant_attributes/', 'attribute_created');
            }
        }

        public function attribute_deleted() {
            $this->set('message', t('Contestant Attribute Deleted.'));
        }

        public function attribute_created() {
            $this->set('message', t('Contestant Attribute Created.'));
        }

        public function attribute_updated() {
            $this->set('message', t('Contestant Attribute Updated.'));
        }

        public function edit($akID = 0) {
            if ($this->post('akID')) {
                $akID = $this->post('akID');
            }
            $key = MissGreekContestantAttributeKey::getByID($akID);
            if (!is_object($key) || $key->isAttributeKeyInternal()) {
                $this->redirect('/dashboard/miss_greek/contestant_attributes');
            }
            $type = $key->getAttributeType();
            $this->set('key', $key);
            $this->set('type', $type);

            if ($this->isPost()) {
                $cnt = $type->getController();
                $cnt->setAttributeKey($key);
                $e = $cnt->validateKey($this->post());
                if ($e->has()) {
                    $this->set('error', $e);
                } else {
                    $type = AttributeType::getByID($this->post('atID'));
                    $key->update($this->post());
                    $this->redirect('/dashboard/miss_greek/contestant_attributes', 'attribute_updated');
                }
            }
        }

    }