<?php

/**
 * Connect to game changer outh
 *
 * @package     local_evokehub
 * @copyright   2023 World Bank Group <https://worldbank.org>
 * @author      Willian Mano <willianmanoaraujo@gmail.com>
 */

require(__DIR__.'/../../config.php');

global $CFG, $USER, $SESSION;

$state = $SESSION->oauthstate;

unset($SESSION->oauthstate);

$stateparam = required_param('state', PARAM_RAW);
$codeparam = optional_param('code', null, PARAM_RAW);

$PAGE->set_url('/local/evokehub/oauthcallback.php');

if ($state !== $stateparam) {
    throw new \Exception('Invalid state code');
}

if (!$codeparam) {
    $hint = required_param('hint', PARAM_RAW);
    $error_description = required_param('error_description', PARAM_RAW);

    redirect(new moodle_url('/'), "<h5 class='alert-heading'>{$hint}</h5><p class='mb-0'>{$error_description}</p>");
}

$client = new \GuzzleHttp\Client();

$settings = get_config('local_evokehub');

if (!$settings->oauth_baseurl || !$settings->oauth_clientid || !$settings->oauth_clientsecret) {
    redirect(new \moodle_url('/'), 'Serviço não configurado.', null, \core\output\notification::NOTIFY_ERROR);
}

$response = $client->request('POST', "{$settings->oauth_baseurl}/oauth/token", [
    'form_params' => [
        'grant_type' => 'authorization_code',
        'client_id' => $settings->oauth_clientid,
        'client_secret' => $settings->oauth_clientsecret,
        'redirect_uri' => $CFG->wwwroot . '/local/evokehub/oauthcallback.php',
        'code' => $codeparam,
    ]
]);

$body = $response->getBody();

$returndata = json_decode($body);

$data = [
    'userid' => $USER->id,
    'token_type' => $returndata->token_type,
    'access_token' => $returndata->access_token,
    'refresh_token' => $returndata->refresh_token,
    'expires_in' => time() + $returndata->expires_in,
    'timecreated' => time(),
    'timemodified' => time(),
];

$DB->insert_record('local_evokehub_access_token', $data);

$event = \local_evokehub\event\user_connected::create([
    'objectid' => $USER->id,
    'context' => \context_system::instance(),
    'relateduserid' => $USER->id
]);
$event->trigger();

redirect(new moodle_url('/'), 'Conexão feita com sucesso.', null, \core\output\notification::NOTIFY_SUCCESS);
