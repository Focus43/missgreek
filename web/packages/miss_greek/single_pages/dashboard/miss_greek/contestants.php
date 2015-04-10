<div id="ccm-dashboard-result-message" class="ccm-ui">
    <?php Loader::packageElement('flash_message', 'miss_greek', array('flash' => $flash)); ?>
</div>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Miss Greek Contestants'), t('Add / Update / Delete contestants.'), false, false ); ?>

<div id="mg-dashboard">
    <div class="ccm-pane-options">
        <form id="ccm-<?php echo $searchInstance; ?>-advanced-search" method="get" action="<?php echo MISSGREEK_TOOLS_URL; ?>dashboard/contestants/search_results">
            <div class="ccm-pane-options-permanent-search">
                <div class="pull-left">
                    <div class="span2">
                        <?php echo $formHelper->text('keywords', $_REQUEST['keywords'], array('class' => 'input-block-level helpTooltip', 'placeholder' => t('Keyword Search'), 'title' => 'First or last name')); ?>
                    </div>
                    <div class="span2">
                        <?php echo $formHelper->select('numResults', array('10' => 'Show 10 (Default)', '25' => 'Show 25', '50' => 'Show 50', '100' => 'Show 100', '500' => 'Show 500'), $_REQUEST['numResults'], array('class' => 'input-block-level helpTooltip', 'title' => '# of results to display')); ?>
                    </div>
                    <div class="span1">
                        <button type="submit" class="btn info pull-right">Search</button>
                        <img src="<?php echo ASSETS_URL_IMAGES?>/loader_intelligent_search.gif" width="43" height="11" class="ccm-search-loading" />
                    </div>
                </div>
                <div class="pull-right">
                    <a class="btn success" href="<?php echo View::url('dashboard/miss_greek/add_contestant') ?>">Add Contestant</a>
                </div>
            </div>
        </form>
    </div>

    <?php Loader::packageElement('dashboard/contestants/search_results', 'miss_greek', array(
        'searchInstance'	=> $searchInstance,
        'listObject'		=> $listObject,
        'listResults'		=> $listResults,
        'pagination'		=> $pagination
    )); ?>
</div>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false); ?>