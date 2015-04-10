<?php

    class DashboardMissGreekSettingsController extends MissGreekPageController {

        public function on_start(){
            parent::on_start();
            $this->addHeaderItem($this->getHelper('html')->css('miss-greek.dashboard.css', 'miss_greek'));
            $this->addFooterItem($this->getHelper('html')->javascript('miss-greek.dashboard.js', 'miss_greek'));
        }


        public function view(){
            $this->set('formHelper', $this->getHelper('form'));
        }


        public function save_ticket_price(){
            Config::save('MG_TICKET_PRICE', (int) $_POST['MG_TICKET_PRICE']);
            // respond
            $this->flash('Ticket price setting saved.')
                 ->redirect('dashboard/miss_greek/settings');
        }

    }