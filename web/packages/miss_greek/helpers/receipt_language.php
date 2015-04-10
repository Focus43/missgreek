<?php

    class ReceiptLanguageHelper {

        /** @var $_donationObj MissGreekDonation */
        protected $_donationObj;

        public function setDonationObject( MissGreekDonation $donationObj ){
            $this->_donationObj = $donationObj;
            return $this;
        }

        public function thankYouText(){
            $donorName      = sprintf('%s %s', $this->_donationObj->getFirstName(), $this->_donationObj->getLastName());
            $donationAmount = (int)$this->_donationObj->getAmount();

            // If *includes* a ticket
            if( $this->_donationObj->getIssueTicketStatus() === MissGreekDonation::ISSUE_TICKET_YES ){
                $ticketPrice       = $this->getTicketPrice();
                $eligibleDeduction = $donationAmount - $this->getTicketPrice();
return <<< heredoc
<p>{$donorName},</p>
<p>Thank you for supporting the 2014 CU Greek Gods event and Clinica Family
Health Services. You have made a donation in the amount of <strong>&#36;{$donationAmount}</strong>,
including 1 ticket to the event for <strong>&#36;{$ticketPrice}</strong>. Because Clinica is a 501(c)(3) tax-exempt organization,
your donation is eligible for tax-deduction (Federal Tax ID: 84-0743432). Ticket purchases, however, are
not tax-deductible. Therefore, your tax deduction has been calculated at <strong>&#36;{$eligibleDeduction}.</strong><p>
<p>Your donation and ticket purchase(s) will show up on your credit card statement as a charge from Clinica Campesina.</p>
<p>Again, thank you for supporting the CU Greek community and Clinica Family Health Services.</p>
<p>Please print or save this page for your records.</p>
heredoc;
            }

            // Doesn't include a ticket
return <<< heredoc
<p>{$donorName},</p>
<p>Thank you for supporting the 2014 CU Greek Gods event and Clinica Family
Health Services. You have made a donation in the amount of <strong>&#36;{$donationAmount}</strong>, excluding any tickets to
the event. Because Clinica is a 501(c)(3) tax-exempt organization, your donation of <strong>&#36;{$donationAmount}</strong>
is eligible for tax deduction in the full amount (Federal Tax ID: 84-0743432).</p>
<p>Your donation and ticket purchase(s) will show up on your credit card statement as a charge from Clinica Campesina.</p>
<p>Again, thank you for supporting the CU Greek community and Clinica Family Health Services.</p>
<p>Please print or save this page for your records.</p>
heredoc;
        }


        /**
         * Get the ticket price.
         * @return int
         */
        protected function getTicketPrice(){
            return (int) Config::get('MG_TICKET_PRICE');
        }

    }