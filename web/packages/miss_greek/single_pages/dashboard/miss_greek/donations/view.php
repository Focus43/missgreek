<div id="ccm-dashboard-result-message" class="ccm-ui">
    <?php Loader::packageElement('flash_message', 'miss_greek', array('flash' => $flash)); ?>
</div>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Miss Greek Donations'), t('Donation List.'), false, false ); ?>

    <div id="mg-dashboard">
        <div class="ccm-pane-options">
            <form id="ccm-<?php echo $searchInstance; ?>-advanced-search" method="get" action="<?php echo MISSGREEK_TOOLS_URL; ?>dashboard/donations/search_results">
                <div class="ccm-pane-options-permanent-search">
                    <div class="pull-left">
                        <div class="span2">
                            <?php echo $formHelper->text('keywords', $_REQUEST['keywords'], array('class' => 'input-block-level helpTooltip', 'placeholder' => t('Keyword Search'), 'title' => 'First or last name')); ?>
                        </div>
                        <div class="span2">
                            <?php echo $formHelper->select('typeHandle', array('' => 'Method', 'credit_card' => 'Credit Card', 'cash_check' => 'Cash/Check'), $_REQUEST['typeHandle'], array('class' => 'input-block-level helpTooltip')); ?>
                        </div>
                        <div class="span2">
                            <?php echo $formHelper->select('numResults', array('25' => 'Show 25', '50' => 'Show 50', '100' => 'Show 100', '250' => 'Show 250'), $_REQUEST['numResults'], array('class' => 'input-block-level helpTooltip', 'title' => '# of results to display')); ?>
                        </div>
                        <div class="span1">
                            <button type="submit" class="btn info pull-right">Search</button>
                            <img src="<?php echo ASSETS_URL_IMAGES?>/loader_intelligent_search.gif" width="43" height="11" class="ccm-search-loading" />
                        </div>
                    </div>
                    <div class="pull-right">
                        <a class="btn success" href="<?php echo $this->action('add'); ?>">Add Cash/Check</a>
                    </div>
                </div>
            </form>
        </div>

        <?php Loader::packageElement('dashboard/donations/search_results', 'miss_greek', array(
            'searchInstance'	=> $searchInstance,
            'listObject'		=> $listObject,
            'listResults'		=> $listResults,
            'pagination'		=> $pagination
        )); ?>
    </div>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false); ?>