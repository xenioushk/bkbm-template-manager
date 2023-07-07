<?php

$updaterBase = 'https://projects.bluewindlab.net/wpplugin/zipped/plugins/';
$pluginRemoteUpdater = $updaterBase . 'bkbm/notifier_bkbm_tpl.php';
new WpAutoUpdater(BWL_KB_TPL_PLUGIN_VERSION, $pluginRemoteUpdater, BKBTPL_ADDON_UPDATER_SLUG);
