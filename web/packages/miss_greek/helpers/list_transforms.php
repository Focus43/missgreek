<?php

    class ListTransformsHelper {

        public function contestantSelectList( array $list = array() ){
            $formatted = array('' => 'Select A Contestant');
            foreach($list AS $contestantObj){ /** @var MissGreekContestant $contestantObj */
                $formatted[ $contestantObj->getContestantID() ] = $contestantObj->__toString();
            }
            return $formatted;
        }

    }