<?php

/**
 * Api util class
 *
 * @package     local_evokehub
 * @copyright   2023 World Bank Group <https://worldbank.org>
 * @author      Willian Mano <willianmanoaraujo@gmail.com>
 */

namespace local_evokehub\util;

defined('MOODLE_INTERNAL') || die;

use moodle_url;

class api {
    protected $userid;
    protected $oauth_baseurl;
    protected $oauth_access_token;

    public function __construct($userid) {
        $info = new api_info();
        $accesstoken = $info->get_user_access_token($userid);

        if (!$accesstoken) {
            throw new \Exception('User is not connected to the marketplace.');
        }

        $settings = get_config('local_evokehub');
        if (!$settings->oauth_baseurl || !$settings->oauth_clientid || !$settings->oauth_clientsecret) {
            throw new \Exception('Marketplace is not configured.');
        }

        $this->userid = $userid;
        $this->oauth_baseurl = $settings->oauth_baseurl;
        $this->oauth_access_token = $accesstoken;
    }

    private function refresh_token() {
        $nowplusonehour = time() + 3600;

        // Se o token for expirar na proxima hora, entao renova-o.
        if ($nowplusonehour >= $this->oauth_access_token->expires_in) {
            // TODO: refresh token
        }
    }

    public function clientsetup() {
        global $DB;

        $this->refresh_token();

        $coinsledger = $DB->get_records('evokegame_evcs_transactions', ['userid' => $this->userid]);

        if (!$coinsledger) {
            return true;
        }

        $data = [];
        if ($coinsledger) {
            foreach ($coinsledger as $entry) {
                $data[] = [
                    'id' => $entry->id,
                    'coins' => $entry->coins,
                    'action' => $entry->action
                ];
            }
        }

        $client = new \GuzzleHttp\Client([
            "base_uri" => $this->oauth_baseurl,
            "headers" => [
                "Accept" => "application/json",
                "Content-Type" => "application/x-www-form-urlencoded",
                "Authorization" => "Bearer {$this->oauth_access_token->access_token}"
            ]
        ]);

        return $client->request('POST', '/api/clientsetup', [
            'form_params' => [
                'data' => json_encode($data)
            ]
        ]);
    }

    public function coinsadded($coins) {
        $this->refresh_token();

        $data = ['coins' => $coins];

        $client = new \GuzzleHttp\Client([
            "base_uri" => $this->oauth_baseurl,
            "headers" => [
                "Accept" => "application/json",
                "Content-Type" => "application/x-www-form-urlencoded",
                "Authorization" => "Bearer {$this->oauth_access_token->access_token}"
            ]
        ]);

        return $client->request('POST', '/api/ledgerin', [
            'form_params' => [
                'data' => json_encode($data)
            ]
        ]);
    }
}
