<?php

    /**
     * This page SHOULD get cached, as the entire display gets generated on the front end.
     *
     * Class TixController
     */
    class TixController extends MissGreekPageController {

        protected $supportsPageCache = true;

        public function on_start(){
            parent::on_start();
            $this->addHeaderItem($this->getHelper('html')->javascript('qrcode.js', self::PACKAGE_HANDLE));
            $this->addFooterItem($this->getHelper('html')->javascript('single-page-tix.js', self::PACKAGE_HANDLE));
        }

    }