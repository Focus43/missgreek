<div id="cL1">
    <?php if( $showCodeScanResults ): ?>
        <div id="pageQRCheck" class="page">
            <div class="tbl">
                <div class="tbl-cell">
                    <div class="inner">
                        <div class="row">
                            <div class="col-sm-12 <?php echo $colorClass; ?>">
                                <?php if( $ticketNotFound ){ ?>
                                    <i class="fa fa-exclamation-triangle"></i>
                                    <p>Unable To Locate Ticket</p>
                                <?php }else{ ?>
                                    <?php if( ! $checkinOK ): ?>
                                        <i class="fa fa-exclamation-triangle"></i>
                                        <p>Ticket Already Scanned!</p>
                                    <?php else: ?>
                                        <i class="fa fa-check-circle"></i>
                                        <p>OK!</p>
                                    <?php endif; ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php if( ! $permissionVerified ): ?>
            <div id="pageAccess" class="page">
                <div class="tbl">
                    <div class="tbl-cell">
                        <div class="inner">
                            <div class="row">
                                <div class="col-sm-12">
                                    <form method="post" action="<?php echo $this->action('login_webpage'); ?>">
                                        <input type="text" name="passcode" class="form-control input-lg" placeholder="Passcode" />
                                        <button type="submit" class="btn btn-block btn-success">Go</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <?php if( $this->controller->getTask() === 'login_qr_scanner' ): ?>
                <div id="pageAccess" class="page">
                    <div class="tbl">
                        <div class="tbl-cell">
                            <div class="inner">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <p>You can scan tickets now.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div id="pageDevices" class="page">
                    <div class="tbl">
                        <div class="tbl-cell">
                            <div class="inner">
                                <p>Install QR reader for your device (free).</p>
                                <div class="qr-readerz">
                                    <a class="btn btn-darkness btn-lg" href="http://itunes.apple.com/app/id368494609">iPhone</a>
                                    <a class="btn btn-darkness btn-lg" href="http://play.google.com/store/apps/details?id=me.scan.android.client">Android</a>
                                </div>
                                <p>Then open this page on another device, scan this code, and you can start checking other's tickets.</p>
                                <div style="padding:20px;background:#fff;text-align:center;">
                                    <div id="loginQrScanner" data-target="<?php echo BASE_URL . $this->action('login_qr_scanner', $qr_scanner_login); ?>"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
</div>