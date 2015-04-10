<div id="ccm-dashboard-result-message" class="ccm-ui">
    <?php Loader::packageElement('flash_message', 'miss_greek', array('flash' => $flash)); ?>
</div>

<?php /** @var $contestantObj MissGreekContestant */ ?>
<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Contestants'), t('Add Contestant'), 'span12', false, array(Page::getByPath('/dashboard/miss_greek/contestants')), Page::getByPath('/dashboard/miss_greek') ); ?>

    <div id="mg-dashboard">
        <?php Loader::packageElement('dashboard/contestants/form_add_edit', 'miss_greek', array(
            'contestantObj'     => $contestantObj,
            'profileAttrKeys'   => $profileAttrKeys
        )); ?>
    </div>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false); ?>