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

class api_info {
    public function get_base_url() {
        try {
            $settings = get_config('local_evokehub');
        } catch (\dml_exception $e) {
            return false;
        }

        return $settings->oauth_baseurl ?: false;
    }

    public function is_connected($userid = null) {
        global $USER;

        if (!$userid) {
            $userid = $USER->id;
        }

        $token = $this->get_user_access_token($userid);

        if (!$token) {
            return false;
        }

        return true;
    }

    public function get_user_access_token($userid = null) {
        global $USER, $DB;

        if (!$userid) {
            $userid = $USER->id;
        }

        return $DB->get_record('local_evokehub_access_token', ['userid' => $userid]);
    }
}
