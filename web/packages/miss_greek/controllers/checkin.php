<?php

    class CheckinController extends MissGreekPageController {

        const ACCESS_CODE       = 'gg2014er',
              SESSION_KEY       = 'can_perform_checkins',
              QR_SCANNER_LOGIN  = 'qr$sc@nnerLogin';

        protected $supportsPageCache = false;

        public function on_start(){
            parent::on_start();
            $this->addHeaderItem($this->getHelper('html')->css('checkin.css', self::PACKAGE_HANDLE));
            $this->addHeaderItem($this->getHelper('html')->javascript('qrcode.js', self::PACKAGE_HANDLE));
            $this->addHeaderItem('<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">');
            $this->addFooterItem($this->getHelper('html')->javascript('single-page-checkin.js', self::PACKAGE_HANDLE));
        }


        /**
         * If user has permission, set $permissionVerified and show proper front-end.
         * @return void
         */
        public function view(){
            if( $this->checkPermission() ){
                $this->set('permissionVerified', true);
                $this->set('qr_scanner_login', md5(self::QR_SCANNER_LOGIN));
            }
        }


        /**
         * Form handler for user to "login" (doesn't actually use C5 login system, just
         * sets validated session variable).
         * @return void
         */
        public function login_webpage(){
            if( $_POST['passcode'] === self::ACCESS_CODE ){
                $_SESSION[self::SESSION_KEY] = true;
                $this->redirect( Page::getCurrentPage()->getCollectionPath() );
            }
        }


        /**
         * Using a QR code scanner to login...
         * @param string|null $with
         */
        public function login_qr_scanner( $with = null ){
            if( $with === md5(self::QR_SCANNER_LOGIN) ){
                $_SESSION[self::SESSION_KEY] = true;
                $this->view();
            }
        }


        /**
         * When using a QR code reader, this is where the magic actually happens.
         * @param mixed $code
         */
        public function scan_code( $hash = null ){
            if( ! $this->checkPermission() ){
                $this->view();
                return;
            }

            // Denote to the view we're showing scan results, no matter what the result yet
            $this->set('showCodeScanResults', true);

            // Always returns an object; test hash values match up
            $ticketObj = MissGreekTicket::getByHash($hash);

            // If hash codes don't match (eg. record returned empty); show ticket not found
            if( $ticketObj->getTicketHash() !== $hash ){
                $this->set('ticketNotFound', true);
                $this->set('colorClass', 'text-warning');
                return;
            }

            // Has it already been scanned?
            if( $ticketObj->hasItBeenScanned() ){
                $this->set('checkinOK', false);
                $this->set('colorClass', 'text-danger');
                return;
            }

            // If we get here, all good! Show success, and immediately mark record as scanned.
            $ticketObj->markAsScanned();
            $this->set('checkinOK', true);
            $this->set('colorClass', 'text-success');
        }


        /**
         * Once the user hit the verify() method, this checks the session for future
         * calls.
         * @return bool
         */
        protected function checkPermission(){
            if( (bool) $_SESSION[self::SESSION_KEY] ){
                return true;
            }
            return false;
        }

    }