<?php
$pageTitle = $pageTitle ?? 'AdrenaX';
$bodyClass = trim(($bodyClass ?? 'theme-store') . ' app-root');
$documentTitle = $pageTitle === 'AdrenaX' ? 'AdrenaX' : $pageTitle . ' | AdrenaX';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#0a0c10">
  <title><?php echo htmlspecialchars($documentTitle, ENT_QUOTES, 'UTF-8'); ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="<?php echo htmlspecialchars(route_url('asset', ['path' => 'css/style.css']), ENT_QUOTES, 'UTF-8'); ?>">
  <script src="<?php echo htmlspecialchars(route_url('asset', ['path' => 'js/app.js']), ENT_QUOTES, 'UTF-8'); ?>" defer></script>
</head>
<body class="<?php echo htmlspecialchars($bodyClass, ENT_QUOTES, 'UTF-8'); ?>">
