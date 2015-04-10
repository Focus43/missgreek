<?php defined('C5_EXECUTE') or die("Access Denied.");

    class DashboardMissGreekController extends MissGreekPageController {

        public function on_start(){
            $this->addHeaderItem($this->getHelper('html')->css('miss-greek.dashboard.css', 'miss_greek'));
            $this->addFooterItem($this->getHelper('html')->javascript('miss-greek.dashboard.js', 'miss_greek'));
        }


        public function view() {
            $this->redirect('/dashboard/miss_greek/contestants');
        }


        /**
         * Create a new contestant.
         * @return void
         */
        public function add_contestant(){
            $this->set('contestantObj', new MissGreekContestant);
            $this->set('profileAttrKeys', MissGreekContestantAttributeKey::getList());
        }


        /**
         * @param int $id
         * @return void
         */
        public function edit_contestant( $id ){
            $this->set('contestantObj', MissGreekContestant::getByID($id));
            $this->set('profileAttrKeys', MissGreekContestantAttributeKey::getList());
        }


        /**
         * @param int $id
         * @todo: permission check
         * @return void
         */
        public function save_contestant( $id = null ){
            $contestantObj = MissGreekContestant::getByID($id);
            $contestantObj->setPropertiesFromArray($_POST['contestant']);
            $contestantObj->save();
            $this->redirect('/dashboard/miss_greek/contestants');
        }


        /**
         * @param int $id
         * @return void
         */
        public function delete_contestant( $id = null ){
            try {
                // first try deleting individual id
                $contestantObj = MissGreekContestant::getByID($id);
                if( $contestantObj->getContestantID() >= 1 ){
                    $contestantObj->delete();
                    $this->redirect('/dashboard/miss_greek/contestants');
                    return;
                }
                // look for an array of ids (delete multiple)
                if( !empty($_POST['contestantID']) ){
                    foreach($_POST['contestantID'] AS $contestantID){
                        MissGreekContestant::getByID( $contestantID )->delete();
                    }
                }
                $this->formResponder(true, 'Contestants deleted.');
            }catch(Exception $e){

            }
        }

    }