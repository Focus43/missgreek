<?php /** @var MissGreekContestant $contestantObj */
    Loader::element('editor_config');
    $formHelper = Loader::helper('form');
    $assetLibrary = Loader::helper('concrete/asset_library');
?>

<div class="ccm-pane-body">
    <form method="post" action="<?php echo $this->action('save_contestant', $contestantObj->getContestantID()); ?>">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#paneProperties" data-toggle="tab">Basic Info.</a></li>
            <li><a href="#paneSurvey" data-toggle="tab">Profile Questions</a></li>
            <li class="pull-right">
                <button type="submit" class="btn success">Save</button>
            </li>
        </ul>

        <div class="tab-content">
            <div id="paneProperties" class="tab-pane active">
                <div class="row-fluid">
                    <div class="span4">
                        <label>First Name</label>
                        <?php echo $formHelper->text('contestant[firstName]', $contestantObj->getFirstName(), array('class' => 'input-block-level')); ?>
                    </div>
                    <div class="span4">
                        <label>Last Name</label>
                        <?php echo $formHelper->text('contestant[lastName]', $contestantObj->getLastName(), array('class' => 'input-block-level')); ?>
                    </div>
                    <div class="span4">
                        <label>House Name (Sorority)</label>
                        <?php echo $formHelper->text('contestant[houseName]', $contestantObj->getHouseName(), array('class' => 'input-block-level')); ?>
                    </div>
                </div>

                <div class="row-fluid">
                    <div class="span12">
                        <label>Featured Photo</label>
                        <?php echo $assetLibrary->image('featuredPic', 'contestant[featuredPhotoID]', 'Featured Photo', File::getByID($contestantObj->getFeaturedPhotoID())); ?>
                    </div>
                </div>

                <div class="row-fluid">
                    <div class="span12">
                        <label>Description</label>
                        <?php Loader::element('editor_controls'); ?>
                        <?php echo $formHelper->textarea('contestant[description]', $contestantObj->getDescription(), array('class' => 'ccm-advanced-editor')); ?>
                    </div>
                </div>
            </div>

            <div id="paneSurvey" class="tab-pane">
                <div class="row-fluid">
                    <div class="span12">
                        <p>To manage the profile questions, visit the <a href="<?php echo $this->url('dashboard/miss_greek/contestant_attributes'); ?>">Contestant Attributes</a> page.</p>
                    </div>
                </div>
                <?php $chunkd = array_chunk($profileAttrKeys, 3); foreach($chunkd AS $group): ?>
                    <div class="row-fluid">
                        <?php foreach($group AS $akObj){ /** @var AttributeKey $akObj */ ?>
                            <div class="span4">
                                <label><?php echo $akObj->getAttributeKeyName(); ?></label>
                                <?php echo $akObj->render('form', $contestantObj->getAttributeValueObject($akObj), true); ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </form>
</div>
<div class="ccm-pane-footer">
    <button id="btnDeleteContestant" type="button" class="btn danger pull-right" data-src="<?php echo View::url('/dashboard/miss_greek/delete_contestant', $contestantObj->getContestantID()); ?>">Delete Contestant</button>
</div>