<?php defined('C5_EXECUTE') or die(_("Access Denied."));

	class MissGreekPackage extends Package {

	    protected $pkgHandle 			= 'miss_greek';
	    protected $appVersionRequired 	= '5.6.1';
	    protected $pkgVersion 			= '0.17';


		/**
		 * @return string
		 */
	    public function getPackageName(){
	        return t('CU Greek Gods');
	    }


		/**
		 * @return string
		 */
	    public function getPackageDescription() {
	        return t('CU Greek Gods site and assets.');
	    }


        /**
         * Run hooks high up in the load chain
         * @return void
         */
        public function on_start(){
            define('MISSGREEK_TOOLS_URL', REL_DIR_FILES_TOOLS_PACKAGES . '/' . $this->pkgHandle . '/');
            define('MISSGREEK_IMAGES_URL', DIR_REL . '/packages/' . $this->pkgHandle . '/images/');
            define('MISSGREEK_PACKAGE_ROOT_URL', DIR_REL . '/packages/' . $this->pkgHandle . '/');
						define('AUTHORIZENET_SDK_PATH',
							realpath(dirname(__FILE__) . '/libraries/authorizenet_sdk2')
						);

            // donation event
            Events::extend('update_donation_cache', 'MissGreekDonationEvents', 'updateDonationCache', "packages/{$this->pkgHandle}/libraries/event_hooks/donations.php");
            Events::extend('donation_completed', 'MissGreekDonationEvents', 'donationCompleted', "packages/{$this->pkgHandle}/libraries/event_hooks/donations.php");

            Loader::registerAutoload(array(
                'MissGreekPageController'  => array('library', 'miss_greek_page_controller', $this->pkgHandle),
                'MissGreekBaseObject' => array('library', 'miss_greek_base_object', $this->pkgHandle),
                'MissGreekContestant' => array('model', 'miss_greek_contestant', $this->pkgHandle),
                'MissGreekContestantAttributeKey,MissGreekContestantAttributeValue' => array('model', 'attribute/categories/miss_greek_contestant', $this->pkgHandle),
                'MissGreekContestantList' => array('model', 'miss_greek_contestant_list', $this->pkgHandle),
                'MissGreekDonation' => array('model', 'miss_greek_donation', $this->pkgHandle),
                'MissGreekDonationList' => array('model', 'miss_greek_donation_list', $this->pkgHandle),
                'MissGreekTicket' => array('model', 'miss_greek_ticket', $this->pkgHandle),

                // Authorize.net; use Concrete5's autoloader instead of the require statements in AuthorizeNet.php fake autoloader
                // 'AuthorizeNetException' 	=> array('library', 'authorize_net_sdk/authorize_net_exception', $this->pkgHandle),
                // 'AuthorizeNetRequest' 		=> array('library', 'authorize_net_sdk/lib/shared/AuthorizeNetRequest', $this->pkgHandle),
                // 'AuthorizeNetTypes'			=> array('library', 'authorize_net_sdk/lib/shared/AuthorizeNetTypes', $this->pkgHandle),
                // 'AuthorizeNetXMLResponse' 	=> array('library', 'authorize_net_sdk/lib/shared/AuthorizeNetXMLResponse', $this->pkgHandle),
                // 'AuthorizeNetResponse' 		=> array('library', 'authorize_net_sdk/lib/shared/AuthorizeNetResponse', $this->pkgHandle),
                // 'AuthorizeNetAIM,AuthorizeNetAIM_Response' => array('library', 'authorize_net_sdk/lib/AuthorizeNetAIM', $this->pkgHandle)
            ));

            // load the SOAP client, if it exists
            // if( class_exists('SoapClient') ){
            //     Loader::registerAutoload(array('AuthorizeNetSOAP', array('library', 'authorize_net_sdk/lib/AuthorizeNetSOAP', $this->pkgHandle)));
            // }

						// $dirPath = dirname(__FILE__);
						// require "{$dirPath}/libraries/authorizenet_sdk2/autoload.php";
        }


		/**
		 * Proxy to the parent uninstall method
		 * @return void
		 */
	    public function uninstall() {
	        parent::uninstall();

            try {
                $db = Loader::db();
                $db->Execute("DROP TABLE MissGreekContestant");
                $db->Execute("DROP TABLE MissGreekContestantAttributeValues");
                $db->Execute("DROP TABLE MissGreekContestantSearchIndexAttributes");
                $db->Execute("DROP TABLE MissGreekDonation");
                $db->Execute("DROP TABLE MissGreekTicket");
            }catch(Exception $e){
                // fail gracefully
            }
	    }


		/**
		 * @return void
		 */
	    public function upgrade(){
	        $this->checkDependencies();
			parent::upgrade();
			$this->installAndUpdate();
	    }


		/**
		 * @return void
		 */
		public function install() {
		    $this->checkDependencies();
	    	$this->_packageObj = parent::install();
			$this->installAndUpdate();
	    }


        /**
         * Run before install or upgrade to ensure dependencies are present
         * @dependency concrete_redis package
         */
        private function checkDependencies(){
            // test for the redis package
            $redisPackage       = Package::getByHandle('concrete_redis');
            $redisPackageAvail  = false;
            if( $redisPackage instanceof Package ){
                if( (bool) $redisPackage->isPackageInstalled() ){
                    $redisPackageAvail = true;
                }
            }

            if( !$redisPackageAvail ){
                throw new Exception('CU Greek Gods requires the ConcreteRedis package.');
            }
        }


		/**
		 * Handle all the updating methods
		 * @return void
		 */
		private function installAndUpdate(){
			$this->registerEntityCategories()
                 ->setupAttributeSets()
                 ->setupTheme()
				 ->setupPageTypes()
                 ->setupSinglePages();
		}


        /**
         * @return MissGreekPackage
         */
        private function registerEntityCategories(){
            if( !($this->attributeKeyCategory('miss_greek_contestant') instanceof AttributeKeyCategory) ){
                $transactionAkc = AttributeKeyCategory::add('miss_greek_contestant', AttributeKeyCategory::ASET_ALLOW_MULTIPLE, $this->packageObject());
                $transactionAkc->associateAttributeKeyType( $this->attributeType('text') );
                $transactionAkc->associateAttributeKeyType( $this->attributeType('boolean') );
                $transactionAkc->associateAttributeKeyType( $this->attributeType('number') );
                $transactionAkc->associateAttributeKeyType( $this->attributeType('textarea') );
                $transactionAkc->associateAttributeKeyType( $this->attributeType('select') );
                $transactionAkc->associateAttributeKeyType( $this->attributeType('image_file') );
            }

            return $this;
        }


        /**
         * @return MissGreekPackage
         */
        private function setupAttributeSets(){
            //$this->getOrCreateAttributeSet('contestant_profile', 'miss_greek_contestant');

            return $this;
        }


		/**
		 * @return MissGreekPackage
		 */
		private function setupTheme(){
            // miss greek theme
            try {
                PageTheme::add('miss_greek', $this->packageObject());
            }catch(Exception $e){ /* fail gracefully */ }

			return $this;
		}


		/**
		 * @return MissGreekPackage
		 */
		private function setupPageTypes(){
            if( !is_object($this->pageType('default')) ){
                CollectionType::add(array('ctHandle' => 'default', 'ctName' => 'Default'), $this->packageObject());
            }

			return $this;
		}


        /**
         * @return MissGreekPackage
         */
        private function setupSinglePages(){
            SinglePage::add('/dashboard/miss_greek', $this->packageObject());

            // manage contestants
            $donations = SinglePage::add('/dashboard/miss_greek/donations', $this->packageObject());
            if( is_object($donations) ){
                $donations->setAttribute('icon_dashboard', 'icon-heart');
                SinglePage::add('/dashboard/miss_greek/donations/details', $this->packageObject());
                SinglePage::add('/dashboard/miss_greek/donations/add', $this->packageObject());
                SinglePage::add('/dashboard/miss_greek/donations/edit', $this->packageObject());
                SinglePage::add('/dashboard/miss_greek/donations/reports', $this->packageObject());
            }

            // manage contestants
            $contestants = SinglePage::add('/dashboard/miss_greek/contestants', $this->packageObject());
            if( is_object($contestants) ){
                $contestants->setAttribute('icon_dashboard', 'icon-user');
            }

            // manage attributes
            $attributes = SinglePage::add('/dashboard/miss_greek/contestant_attributes', $this->packageObject());
            if( is_object($attributes) ){
                $attributes->setAttribute('icon_dashboard', 'icon-tasks');
            }

            // settings
            $settings = SinglePage::add('/dashboard/miss_greek/settings', $this->packageObject());
            if( is_object($settings) ){
                $settings->setAttribute('icon_dashboard', 'icon-cog');
            }

            // add the /checkin page (really the check-in manager)
            SinglePage::add('/checkin', $this->packageObject());

            // single page for tickets (for shorter URLs)
            SinglePage::add('/tix', $this->packageObject());

            return $this;
        }


		/**
		 * Get the package object; if it hasn't been instantiated yet, load it.
		 * @return Package
		 */
		private function packageObject(){
			if( $this->_packageObj === null ){
				$this->_packageObj = Package::getByHandle( $this->pkgHandle );
			}
			return $this->_packageObj;
		}


		/**
		 * @return CollectionType
		 */
		private function pageType( $handle ){
			if( $this->{ "pt_{$handle}" } === null ){
				$this->{ "pt_{$handle}" } = CollectionType::getByHandle( $handle );
			}
			return $this->{ "pt_{$handle}" };
		}


		/**
		 * @return AttributeType
		 */
		private function attributeType( $atHandle ){
			if( $this->{ "at_{$atHandle}" } === null ){
				$this->{ "at_{$atHandle}" } = AttributeType::getByHandle( $atHandle );
			}
			return $this->{ "at_{$atHandle}" };
		}


		/**
		 * Get an attribute key category object (eg: an entity category) by its handle
		 * @return AttributeKeyCategory
		 */
		private function attributeKeyCategory( $handle ){
			if( !($this->{ "akc_{$handle}" } instanceof AttributeKeyCategory) ){
				$this->{ "akc_{$handle}" } = AttributeKeyCategory::getByHandle( $handle );
			}
			return $this->{ "akc_{$handle}" };
		}


        /**
         * Get or create an attribute set, for a certain attribute key category (if passed).
         * Will automatically convert the $attrSetHandle from handle_form_name to Handle Form Name
         * @param string $attrSetHandle
         * @param string $attrKeyCategory
         * @return AttributeSet
         */
        private function getOrCreateAttributeSet( $attrSetHandle, $attrKeyCategory = null ){
            if( $this->{ 'attr_set_' . $attrSetHandle } === null ){
                // try to load an existing Attribute Set
                $attrSetObj = AttributeSet::getByHandle( $attrSetHandle );

                // doesn't exist? create it, if an attributeKeyCategory is passed
                if( !is_object($attrSetObj) && !is_null($attrKeyCategory) ){
                    // ensure the attr key category can allow multiple sets
                    $akc = AttributeKeyCategory::getByHandle( $attrKeyCategory );
                    $akc->setAllowAttributeSets( AttributeKeyCategory::ASET_ALLOW_MULTIPLE );

                    // *now* add the attribute set
                    $attrSetObj = $akc->addSet( $attrSetHandle, t( $this->getHelper('text')->unhandle($attrSetHandle) ), $this->packageObject() );
                }

                // assign the $attrSetObj
                $this->{ 'attr_set_' . $attrSetHandle } = $attrSetObj;
            }

            return $this->{ 'attr_set_' . $attrSetHandle };
        }


		/**
		 * "Memoize" helpers so they're only loaded once.
		 * @param string $handle Handle of the helper to load
		 * @param string $pkg Package to get the helper from
		 * @return ...Helper class of some sort
		 */
		private function getHelper( $handle, $pkg = false ){
			$helper = '_helper_' . preg_replace("/[^a-zA-Z0-9]+/", "", $handle);
			if( $this->{$helper} === null ){
				$this->{$helper} = Loader::helper($handle, $pkg);
			}
			return $this->{$helper};
		}

	}
