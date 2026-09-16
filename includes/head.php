<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo htmlspecialchars($page_title); ?></title>
<meta name="description" lang="en-us" content="<?php echo htmlspecialchars($page_desc); ?>" />
<meta name="viewport" content="height=device-height, width=device-width, initial-scale=1, minimum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="format-detection" content="telephone=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="icon" type="image/png" href="<?php echo $BASE; ?>/images/sites/ncc/NWCC-favicon.png">
<script src="<?php echo $BASE; ?>/javascripts/jquery-1.11.0.min.js" type="text/javascript"></script>
<script src="<?php echo $BASE; ?>/javascripts/jquery.simplemodal.1.4.4.min.js" type="text/javascript"></script>
<script src="<?php echo $BASE; ?>/javascripts/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
<script src="<?php echo $BASE; ?>/javascripts/lazyload+io-polyfill.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="https://use.typekit.net/hej7fji.css">
<link href="<?php echo $BASE; ?>/stylesheets/blocks/defaults.css" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo $BASE; ?>/stylesheets/ncc/defaults-foundation.css" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo $BASE; ?>/stylesheets/ncc/icons.css" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo $BASE; ?>/stylesheets/ncc/mobile-menu.css" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo $BASE; ?>/stylesheets/ncc/helpers.css" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo $BASE; ?>/stylesheets/ncc/site.css?v=header2" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo $BASE; ?>/stylesheets/ncc/inner.css?v=header2" media="screen" rel="stylesheet" type="text/css" />
</head>
<body id="<?php echo htmlspecialchars($body_id); ?>" class="<?php echo htmlspecialchars($body_class); ?>">
