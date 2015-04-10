<?php defined('C5_EXECUTE') or die("Access Denied.");

    class DashboardMissGreekDonationsReportsController extends MissGreekPageController {

        public function on_start(){
            parent::on_start();
            $this->addHeaderItem($this->getHelper('html')->css('miss-greek.dashboard.css', 'miss_greek'));
            $this->addFooterItem($this->getHelper('html')->javascript('miss-greek.dashboard.js', 'miss_greek'));
            $this->addFooterItem($this->getHelper('html')->javascript('libs/highcharts-3.0.9/js/highcharts.js', 'miss_greek'));
        }


        public function view(){

        }

    }