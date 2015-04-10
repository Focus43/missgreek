<?php $columns = MissGreekDonationColumnSet::getCurrent();
$imageHelper   = Loader::helper('image');
$permissions   = new Permissions( Page::getByPath('/dashboard/miss_greek/donations/edit') );
$canEdit       = $permissions->canViewPage();
?>

<div id="ccm-<?php echo $searchInstance; ?>-search-results">
    <div class="ccm-pane-body">
        <table id="donationsSearchTable" border="0" cellspacing="0" cellpadding="0" class="group-left ccm-results-list">
            <thead>
                <tr>
                    <?php foreach($columns->getColumns() as $col) { ?>
                        <?php if ($col->isColumnSortable()) { ?>
                            <th class="<?php echo $listObject->getSearchResultsClass($col->getColumnKey())?>"><a href="<?php echo $listObject->getSortByURL($col->getColumnKey(), $col->getColumnDefaultSortDirection(), (MISSGREEK_TOOLS_URL . 'dashboard/donations/search_results'), array())?>"><?php echo $col->getColumnName()?></a></th>
                        <?php } else { ?>
                            <th><?php echo $col->getColumnName()?></th>
                        <?php } ?>
                    <?php } ?>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($listResults AS $donationObj): /** @var MissGreekDonation $donationObj */ ?>
                <tr>
                    <?php foreach($columns->getColumns() AS $colObj){ ?>
                        <td class="<?php echo strtolower($colObj->getColumnName()); ?>"><?php echo $colObj->getColumnValue($donationObj); ?></td>
                    <?php } ?>
                    <td>
                        <a class="btn btn-mini" href="<?php echo View::url('dashboard/miss_greek/donations/details', $donationObj->getDonationID()); ?>">Details</a>
                        <?php if( $canEdit ): ?>
                        <a class="btn btn-mini inverse" href="<?php echo View::url('dashboard/miss_greek/donations/edit', $donationObj->getDonationID()); ?>">Edit</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <!-- # of results -->
        <?php $listObject->displaySummary(); ?>
    </div>

    <!-- paging stuff -->
    <div class="ccm-pane-footer">
        <div class="pull-left">
            <?php $listObject->displayPagingV2((MISSGREEK_TOOLS_URL . 'dashboard/donations/search_results'), array()) ?>
        </div>
        <div class="pull-right">
            <a class="btn success" href="<?php echo View::url('/dashboard/miss_greek/donations/reports'); ?>">Reports</a>
        </div>
    </div>
</div>
