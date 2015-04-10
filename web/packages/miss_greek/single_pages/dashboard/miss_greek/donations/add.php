<div id="ccm-dashboard-result-message" class="ccm-ui">
    <?php Loader::packageElement('flash_message', 'miss_greek', array('flash' => $flash)); ?>
</div>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Miss Greek Donations'), t('Donation List.'), false, false ); ?>

    <div id="mg-dashboard">
        <div class="ccm-pane-body ccm-pane-body-footer">
            <form id="frmAddDonation" method="POST" action="<?php echo $this->action('save'); ?>">
                <div class="row-fluid">
                    <div class="span12">
                        <div class="pull-left">
                            <h3 class="lead" style="margin-bottom:0;">Add Donation Manually</h3>
                            <p>This is primarily for accounting purposes, in order to add cash/checks to the donation totals.</p>
                        </div>
                        <button type="submit" class="btn success pull-right">Add Donation</button>
                    </div>
                </div>
                <div class="well">
                    <div class="row-fluid">
                        <div class="span12">
                            <label>Donate On Behalf Of</label>
                            <?php echo $formHelper->select('donate[contestantID]', $contestantList, '', array('class' => 'input-block-level')); ?>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span4">
                            <label>First Name</label>
                            <?php echo $formHelper->text('donate[firstName]', '', array('class' => 'input-block-level', 'placeholder' => 'First Name')); ?>
                        </div>
                        <div class="span4">
                            <label>Last Name</label>
                            <?php echo $formHelper->text('donate[lastName]', '', array('class' => 'input-block-level', 'placeholder' => 'Last Name')); ?>
                        </div>
                        <div class="span4">
                            <label>Email Address</label>
                            <?php echo $formHelper->text('donate[email]', '', array('class' => 'input-block-level', 'placeholder' => 'Email Address')); ?>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span4">
                            <label>Donation Amount</label>
                            <?php echo $formHelper->text('donate[amount]', '', array('class' => 'input-block-level', 'placeholder' => '$ Amount')); ?>
                            <p class="muted">(ONLY whole dollar amount, no "$" symbol, or decimals)</p>
                        </div>
                        <div id="nameDisplayMethod" class="span4">
                            <label>Name Display Settings</label>
                            <label class="radio">
                                <?php echo $formHelper->radio('donate[nameDisplayMethod]', MissGreekDonation::NAME_DISPLAY_METHOD_CARDHOLDER, 1); ?> Use Name As Shown Above
                            </label>
                            <label class="radio">
                                <?php echo $formHelper->radio('donate[nameDisplayMethod]', MissGreekDonation::NAME_DISPLAY_METHOD_CUSTOM, 1, array('data-custom' => 'true')); ?> Custom Name
                            </label>
                            <?php echo $formHelper->text('donate[customName]', '', array('class' => 'form-control', 'placeholder' => 'ie. Ralphie', 'disabled' => 'disabled')); ?>
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
            });
        </script>
    </div>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false); ?>