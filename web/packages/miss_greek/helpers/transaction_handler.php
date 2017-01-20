<?php defined('C5_EXECUTE') or die("Access Denied.");

  class TransactionHandlerHelper {

    private $_data, $_apiResponse, $_authNetObj, $_includesTicket;

    /**
     * Pass in an array of the data to be passed to the authorize.net API. Must
     * be formatted with key => value whereas key = valid api field name and
     * value is assigned accordingly.
     * @param array $data
     * @return TransactionHandlerHelper
     */
    public function setData( array $data ){
        $this->_data = $data;
        return $this;
    }


    public function setIsTicketIncluded( $bool = false ){
        $this->_includesTicket = $bool;
    }


    /**
     * Only after ->setData is set; this will actually commit the transaction to
     * the API, and return the response object. Also, memoize it so that once
     * the $_apiResponse is set, the transaction can't be rerun without creating
     * a new instance.
     * @return mixed
     */
    public function run(){
        if( $this->_apiResponse === null ){
            $this->_apiResponse = $this->authorizeNetObj()->authorizeAndCapture();
        }
        return $this->_apiResponse;
    }


    /**
     * Instantiate the authorize.net API class and pass data accordingly.
     * @return AuthorizeNetAIM
     */
    private function authorizeNetObj(){
        if( $this->_authNetObj === null ){
            // create new Auth AIM instance
            $this->_authNetObj = new AuthorizeNetAIM;

            // iterate through $_data and set key => values as AuthNet object properties
            foreach($this->_data AS $prop => $value){
                $this->_authNetObj->{$prop} = $value;
            }

            // set description as defaulting to NO TICKET
            $this->_authNetObj->description = 'CUGreekGods.com; ticket: NO';
            if( $this->_includesTicket === true ){
                $this->_authNetObj->description = 'CUGreekGods.com; ticket: YES';
            }
        }
        return $this->_authNetObj;
    }

  }
