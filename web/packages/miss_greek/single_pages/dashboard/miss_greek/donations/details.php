<div id="ccm-dashboard-result-message" class="ccm-ui">
    <?php Loader::packageElement('flash_message', 'miss_greek', array('flash' => $flash)); ?>
</div>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Miss Greek Donations'), t('Donation List.'), false, false ); ?>
    <div id="mg-dashboard">
        <?php /** @var $donationObj MissGreekDonation */ ?>
        <div class="ccm-pane-body ccm-pane-body-footer">
            <div class="row-fluid">
                <div class="span12">
                    <h3 class="lead pull-left">Donation Amount: <strong>$<?php echo number_format($donationObj->getAmount(), 2); ?></strong></h3>
                    <div class="pull-right">
                        Transaction ID (AuthNet): <span class="badge badge-info"><?php if( $donationObj->getTypeHandle() === MissGreekDonation::TYPE_HANDLE_CASH_CHECK ){ echo 'None: Cash Transaction'; }else{ echo $donationObj->getAuthNetTransactionID(); } ?></span>
                        <p>Date: <?php $date = new DateTime($donationObj->getDateCreated(), new DateTimeZone('UTC')); echo $date->setTimezone(new DateTimeZone('America/Denver'))->format('M d, Y (g:i a)'); ?></p>
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <h4>Cardholder Information</h4>
                    <table class="table table-bordered table-condensed">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Billing Address</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Zip</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><?php echo t("%s %s", $donationObj->getFirstName(), $donationObj->getLastName()); ?></td>
                            <td><?php echo $donationObj->getEmail(); ?></td>
                            <td><?php echo $donationObj->getAddress1() . ' ' . $donationObj->getAddress2(); ?></td>
                            <td><?php echo $donationObj->getCity(); ?></td>
                            <td><?php echo $donationObj->getState(); ?></td>
                            <td><?php echo $donationObj->getZip(); ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <h4>Was the donation made on someone else's behalf? <small>ie. "Custom Name"</small></h4>
                    <?php if( $donationObj->getNameDisplayMethod() === MissGreekDonation::NAME_DISPLAY_METHOD_CARDHOLDER ): ?>
                        <p>No: uses cardholder's name</p>
                    <?php else: ?>
                        <p>Yes: donation made on behalf of <?php echo $donationObj->getCustomName(); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <h4>Event Ticket</h4>
                    <p>Ticket Code: <a style="font-weight:bold;" href="<?php echo $donationObj->getTicketObj()->getTicketLinkURL(); ?>" target="_blank"><?php echo $donationObj->getTicketObj()->getTicketHash(); ?></a></p>
                    <p>Scanned Status:
                        <?php if( (int)$donationObj->getTicketObj()->getScanStatus() === MissGreekTicket::SCAN_STATUS_UNSCANNED ): ?>
                            <span class="badge badge-success">Has Not Been Scanned</span>
                        <?php else: ?>
                            <span class="badge badge-warning">Has Been Scanned</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false); ?>