<?php defined('C5_EXECUTE') or die("Access Denied.");
    $imageHelper    = Loader::helper('image');
    $contestantObj  = MissGreekContestant::getByID((int)$_REQUEST['contestantID']);
    $attrKeys       = MissGreekContestantAttributeKey::getList();
    $donorsListData = ConcreteRedis::db()->hget('mg_donors', $contestantObj->getContestantID());
    $donorsList     = (array) Loader::helper('json')->decode($donorsListData);
    $donorsChunked  = array_chunk($donorsList, 3);
?>
<div>
    <h3><?php echo $contestantObj; ?></h3>
    <div class="banner-img">
        <div class="banner-wrap">
            <button class="btn btn-lg btn-block do-donate" data-id="<?php echo $contestantObj->getContestantID(); ?>">Support With A Donation!</button>
            <img src="<?php echo $imageHelper->getThumbnail($contestantObj->getFeaturedPhotoObj(), 1200, 600, true)->src; ?>" />
            <span>Representing <?php echo $contestantObj->getHouseName(); ?></span>
        </div>
    </div>

    <h3>About</h3>
    <?php echo $contestantObj->getDescription(); ?>
    <?php foreach($attrKeys AS $akObj): /** @var AttributeKey $akObj */ ?>
        <p class="title"><?php echo $akObj->getAttributeKeyName(); ?></p>
        <p><?php echo (string) $contestantObj->getAttribute($akObj, 'display'); ?></p>
    <?php endforeach; ?>

    <h3>Supporters</h3>
    <div class="donorList">
        <?php foreach($donorsChunked AS $rowChunk): ?>
            <div class="row">
                <?php foreach($rowChunk AS $donorObj){ ?>
                    <div class="col-sm-4">
                        <p><?php echo $donorObj->name; ?></p>
                    </div>
                <?php } ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>