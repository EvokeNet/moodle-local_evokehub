<?php

/**
 * Connect to Evoke Hub outh
 *
 * @package     local_evokehub
 * @copyright   2023 World Bank Group <https://worldbank.org>
 * @author      Willian Mano <willianmanoaraujo@gmail.com>
 */

require(__DIR__.'/../../config.php');

global $CFG, $SESSION;

$SESSION->oauthstate = $state = base64_encode(openssl_random_pseudo_bytes(40));

$settings = get_config('local_evokehub');

if (!$settings->oauth_baseurl || !$settings->oauth_clientid || !$settings->oauth_clientsecret) {
    redirect(new moodle_url('/'), 'Serviço não configurado.', null, \core\output\notification::NOTIFY_ERROR);
}

$query = http_build_query([
    'client_id' => $settings->oauth_clientid,
    'redirect_uri' => $CFG->wwwroot . '/local/evokehub/oauthcallback.php',
    'response_type' => 'code',
    'scope' => '',
    'state' => $state,
    'prompt' => 'consent', // "login", "consent", or "login"
    'back_uri' => $CFG->wwwroot
]);

redirect("{$settings->oauth_baseurl}/oauth/authorize?{$query}");