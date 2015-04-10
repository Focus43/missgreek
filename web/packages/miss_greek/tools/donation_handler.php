<?php defined('C5_EXECUTE') or die("Access Denied.");

    // set response headers
    header('Content-Type: text/json');

    // process
    try {
        $ticketPrice = (int) Config::get('MG_TICKET_PRICE');

        // Get form data, unwrapped from form array
        $formData = $_POST['donate'];

        // Validate that shit
        $validatorObj = Loader::helper('validation/form');
        $validatorObj->setData($formData);
        $validatorObj->addRequired('contestantID', 'contestantID');
        $validatorObj->addRequiredEmail('email', 'email');
        $validatorObj->addRequired('firstName', 'firstName');
        $validatorObj->addRequired('lastName', 'lastName');
        $validatorObj->addRequired('address1', 'address1');
        $validatorObj->addRequired('addressCity', 'addressCity');
        $validatorObj->addRequired('addressState', 'addressState');
        $validatorObj->addRequired('addressZip', 'addressZip');
        $validatorObj->addRequired('ccNumber', 'ccNumber');
        $validatorObj->addRequired('ccType', 'ccType');
        $validatorObj->addRequired('ccExpMo', 'ccExpMo');
        $validatorObj->addRequired('ccExpYr', 'ccExpYr');
        $validatorObj->addRequired('amount', 'amount');

        // validation test, before sending to the API or persisting to DB
        if( ! $validatorObj->test() ){
            echo Loader::helper('json')->encode((object) array(
                'code'      => 0,
                'message'   => 'Please make sure all fields highlighted in red are filled in correctly.',
                'invalids'  => $validatorObj->getError()->getList()
            ));
            exit;
        }

        // After validation, address ticketing stuff immediately. Defaults to NO ticket.
        $issueTicketStatus = MissGreekDonation::ISSUE_TICKET_NO;
        // If issue ticket is set to yes...
        if( (int)$formData['issueTicket'] === MissGreekDonation::ISSUE_TICKET_YES ){
            if( (int)$formData['amount'] >= $ticketPrice ){
                $issueTicketStatus = MissGreekDonation::ISSUE_TICKET_YES;
            }
        }


        // if we get here, set the transaction data then...
        $transactionHelper = Loader::helper('transaction_handler', 'miss_greek')->setData(array(
            'amount'        => (int) $formData['amount'],
            'card_num'      => $formData['ccNumber'],
            'exp_date'      => "{$formData['ccExpMo']}/{$formData['ccExpYr']}",
            'first_name'    => $formData['firstName'],
            'last_name'     => $formData['lastName'],
            'address'       => $formData['address1'],
            'city'          => $formData['addressCity'],
            'state'         => $formData['addressState'],
            'zip'           => $formData['addressZip'],
            'phone'         => '',
            'email'         => $formData['email']
        ));

        // is ticket included? (for the description in authorize.net)
        $transactionHelper->setIsTicketIncluded((bool)$issueTicketStatus);

        // send to Authorize.net API
        $apiResponse = $transactionHelper->run();

        // if api responded with failure...
        if( ! $apiResponse->approved ){
            echo Loader::helper('json')->encode((object) array(
                'code'      => 0,
                'message'   => $apiResponse->response_reason_text
            ));
            exit;
        }

        // if we get here, transaction was successful, prepare to log the transaction
        $donationObj = new MissGreekDonation(array(
            'contestantID'          => (int) $formData['contestantID'],
            'typeHandle'            => MissGreekDonation::TYPE_HANDLE_CREDIT_CARD,
            'firstName'             => $formData['firstName'],
            'lastName'              => $formData['lastName'],
            'email'                 => $formData['email'],
            'address1'              => $formData['address1'],
            'address2'              => $formData['address2'],
            'city'                  => $formData['addressCity'],
            'state'                 => $formData['addressState'],
            'zip'                   => $formData['addressZip'],
            'nameDisplayMethod'     => (int) $formData['nameDisplayMethod'],
            'customName'            => $formData['customName'],
            'amount'                => (int) $formData['amount'],
            'issueTicket'           => $issueTicketStatus,
            'authNetTransactionID'  => $apiResponse->transaction_id
        ));

        // Save the donation record. Doing so triggers the event system which
        // will automatically generate a ticket if issueTicket is true.
        $donationObj->save();

        // base response data
        $responseData = (object) array(
            'code'      => 1,
            'firstName' => $formData['firstName'],
            'lastName'  => $formData['lastName'],
            'message'   => Loader::helper('receipt_language', 'miss_greek')->setDonationObject($donationObj)->thankYouText()
        );

        // if ticket was issued, add URL in the response object
        if( $donationObj->getIssueTicketStatus() === MissGreekDonation::ISSUE_TICKET_YES ){
            $responseData->ticketURL = $donationObj->getTicketObj()->getTicketLinkURL();
        }

        // Issue email receipt!
        $mailerObj = Loader::helper('mail');
        $mailerObj->to($formData['email']);
        $mailerObj->from(OUTGOING_MAIL_ISSUER_ADDRESS);
        $mailerObj->addParameter('donationObj', $donationObj);
        $mailerObj->load('donation_receipt', 'miss_greek');
        $mailerObj->setSubject('CU Greek Gods Donation Receipt');
        $mailerObj->sendMail();

        echo Loader::helper('json')->encode($responseData);

    // If failure occurs...
    }catch(Exception $e){
        echo Loader::helper('json')->encode((object) array(
            'code'      => 0,
            'message'   => 'An error occurred (not security related!) - please call Jon Hartman @ (443) 956-5918.'
        ));
    }
