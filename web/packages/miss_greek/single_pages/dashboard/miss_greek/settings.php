<div id="ccm-dashboard-result-message" class="ccm-ui">
    <?php Loader::packageElement('flash_message', 'miss_greek', array('flash' => $flash)); ?>
</div>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Miss Greek Settings'), t('Miss Greek Settings'), 'span12', false); ?>

    <div id="mg-dashboard">
        <div id="mg-settings" class="ccm-pane-body ccm-pane-body-footer">
            <div class="row-fluid">
                <div class="span6">
                    <form method="POST" action="<?php echo $this->action('save_ticket_price'); ?>">
                        <div class="clearfix page-header">
                            <h3 class="lead pull-left">Ticket Price</h3>
                            <button type="submit" class="btn btn-info pull-right">Save</button>
                        </div>
                        <label>Minimum contribution required to get into the event.</label>
                        <div class="input-prepend input-append">
                            <span class="add-on">$</span><?php echo $formHelper->text('MG_TICKET_PRICE', Config::get('MG_TICKET_PRICE'), array('class' => 'input-small')); ?><span class="add-on">.00</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false); ?>