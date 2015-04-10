<!DOCTYPE HTML>
<html lang="<?php echo LANGUAGE; ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=EDGE" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php Loader::element('header_required'); // REQUIRED BY C5 // ?>
</head>

<body>
    <?php print $innerContent; ?>
<?php Loader::element('footer_required'); // REQUIRED BY C5 // ?>
</body>
</html>