<?php /** @var $donationObj MissGreekDonation */ ?>

<div id="ccm-dashboard-result-message" class="ccm-ui">
    <?php Loader::packageElement('flash_message', 'miss_greek', array('flash' => $flash)); ?>
</div>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Miss Greek Donations'), t('Edit'), false, false ); ?>

    <div id="mg-dashboard">
        <div class="ccm-pane-body ccm-pane-body-footer">
            <form id="frmAddDonation" method="POST" action="<?php echo $this->action('save', $donationObj->getDonationID()); ?>">
                <div class="row-fluid">
                    <div class="span12">
                        <div class="clearfix" style="padding:0;">
                            <div class="pull-left">
                                <h3 class="lead" style="margin-bottom:12px;">Update Donation Record</h3>
                            </div>
                            <button type="submit" class="btn success pull-right">Update</button>
                        </div>
                        <p><span class="badge badge-warning">NOTE</span> Updating information here only affects website display. If you increase/decrease the amount, it <strong>WILL NOT</strong> charge the credit card more or less.</p>
                    </div>
                </div>
                <div class="well">
                    <div class="row-fluid">
                        <div class="span12">
                            <label>Donate On Behalf Of</label>
                            <?php echo $formHelper->select('donate[contestantID]', $contestantList, $donationObj->getContestantID(), array('class' => 'input-block-level')); ?>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span4">
                            <label>First Name</label>
                            <?php echo $formHelper->text('donate[firstName]', $donationObj->getFirstName(), array('class' => 'input-block-level', 'placeholder' => 'First Name')); ?>
                        </div>
                        <div class="span4">
                            <label>Last Name</label>
                            <?php echo $formHelper->text('donate[lastName]', $donationObj->getLastName(), array('class' => 'input-block-level', 'placeholder' => 'Last Name')); ?>
                        </div>
                        <div class="span4">
                            <label>Email Address</label>
                            <?php echo $formHelper->text('donate[email]', $donationObj->getEmail(), array('class' => 'input-block-level', 'placeholder' => 'Email Address')); ?>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span4">
                            <label>Donation Amount</label>
                            <?php echo $formHelper->text('donate[amount]', (int)$donationObj->getAmount(), array('class' => 'input-block-level', 'placeholder' => '$ Amount')); ?>
                            <p class="muted">(ONLY whole dollar amount, no "$" symbol, or decimals)</p>
                        </div>
                        <div id="nameDisplayMethod" class="span4">
                            <label>Name Display Settings</label>
                            <label class="radio">
                                <?php echo $formHelper->radio('donate[nameDisplayMethod]', MissGreekDonation::NAME_DISPLAY_METHOD_CARDHOLDER, $donationObj->getNameDisplayMethod()); ?> Use Name As Shown Above
                            </label>
                            <label class="radio">
                                <?php echo $formHelper->radio('donate[nameDisplayMethod]', MissGreekDonation::NAME_DISPLAY_METHOD_CUSTOM, $donationObj->getNameDisplayMethod(), array('data-custom' => 'true')); ?> Custom Name
                            </label>
                            <?php echo $formHelper->text('donate[customName]', $donationObj->getCustomName(), array('class' => 'form-control', 'placeholder' => 'ie. Ralphie', 'disabled' => 'disabled')); ?>
                        </div>
                        <div class="span4">
                            <!-- nothing -->
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <script type="text/javascript">
            $(function(){
                $('#nameDisplayMethod').on('click', 'input[type="radio"]', function(){
                    var $textInput = $('input[type="text"]', '#nameDisplayMethod');
                    $textInput.prop('disabled', !($(this).attr('data-custom') === 'true') );
                });

                $('input[type="radio"]', '#nameDisplayMethod').filter(':checked').trigger('click');
            });
        </script>
    </div>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false); ?>