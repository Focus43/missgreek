<?php

abstract class MissGreekPageController extends Controller {

    const PACKAGE_HANDLE	= 'miss_greek',
        FLASH_TYPE_OK		= 'success',
        FLASH_TYPE_ERROR	= 'error';

    protected $requireHttps = false;


    /**
     * Ruby on Rails "flash" functionality ripoff.
     * @param string $msg Optional, set the flash message
     * @param string $type Optional, set the class for the alert
     * @return void
     */
    public function flash( $msg = 'Success', $type = self::FLASH_TYPE_OK ){
        $_SESSION['flash_msg'] = array(
            'msg'  => $msg,
            'type' => $type
        );
        return $this;
    }


    /**
     * Before a page gets rendered, always make sure that the $_POST card_number
     * field is empty. For any page. No matter what.
     */
    public function on_before_render(){
        // never send back the credit card
        $_POST['donate']['ccNumber'] = false;
    }


    /**
     * Add js/css + tools URL meta tag; clear the flash.
     * @return void
     */
    public function on_start(){
        // force https (if $requireHTTPS == true)
        if( $this->requireHttps == true && !( isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on') ) ){
            header("Location: " . str_replace('http', 'https', BASE_URL . Page::getCurrentPage()->getCollectionPath()));
        }

        // message flash
        if( isset($_SESSION['flash_msg']) ){
            $this->set('flash', $_SESSION['flash_msg']);
            unset($_SESSION['flash_msg']);
        }

        // include live reload for for grunt watch!
        if(isset($_SERVER['VAGRANT_VM']) && ((bool) $_SERVER['VAGRANT_VM'] === true)){
            //$this->addFooterItem('<script src="http://lo.cal:9090/livereload.js"></script>');
        }
    }


    /**
     * Same as $view->action(), but forces to return a fully qualified URL prepended
     * with https://
     * @param string $action
     * @param string $task(s)
     * @return string
     */
    public function secureAction($action, $task = null){
        $args = func_get_args();
        array_unshift($args, Page::getCurrentPage()->getCollectionPath());
        $path = call_user_func_array(array('View', 'url'), $args);
        return 'https://' . $_SERVER['HTTP_HOST'] . $path;
    }


    /**
     * Send back an ajax response if request headers accept json, or handle
     * redirect if just doing regular http
     * @param bool $okOrFail
     * @param mixed String || Array $message
     * @return void
     */
    protected function formResponder( $okOrFail, $message, $extraData = array() ){
        $accept = explode( ',', $_SERVER['HTTP_ACCEPT'] );
        $accept = array_map('trim', $accept);


        // send back a JSON response
        if( in_array($accept[0], array('application/json', 'text/javascript')) || $_SERVER['X_REQUESTED_WITH'] == 'XMLHttpRequest'){
            header('Content-Type: application/json');
            echo json_encode( (object) (array(
                    'code'		=> (int) $okOrFail,
                    'messages'	=> is_array($message) ? $message : array($message)
                ) + $extraData));
            exit;
        }

        // somehow a plain old html browser request got through, redirect it
        $this->flash( $message, ((bool)$okOrFail === true ? self::FLASH_TYPE_OK : self::FLASH_TYPE_ERROR) );
        $this->redirect( Page::getCurrentPage()->getCollectionPath() );
    }


    /**
     * "Memoize" helpers so they're only loaded once.
     * @param string $handle Handle of the helper to load
     * @param string $pkg Package to get the helper from
     * @return ...Helper class of some sort
     */
    public function getHelper( $handle, $pkg = false ){
        $helper = '_helper_' . preg_replace("/[^a-zA-Z0-9]+/", "", $handle);
        if( $this->{$helper} === null ){
            $this->{$helper} = Loader::helper($handle, $pkg);
        }
        return $this->{$helper};
    }

}
