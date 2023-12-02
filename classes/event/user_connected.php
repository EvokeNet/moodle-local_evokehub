<?php

namespace local_evokehub\event;

/**
 * The user_connected event class.
 *
 * @package     local_evokehub
 * @copyright   2023 World Bank Group <https://worldbank.org>
 * @author      Willian Mano <willianmanoaraujo@gmail.com>
 */
class user_connected extends \core\event\base {
    /**
     * Returns description of what happened.
     *
     * @return string
     */
    public function get_description() {
        return "The user with id {$this->relateduserid} was connected with the marketplace.";
    }

    /**
     * Return localised event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('event_user_connected', 'local_evokehub');
    }

    /**
     * Get URL related to the action
     *
     * @return \moodle_url
     */
    public function get_url() {
        return new \moodle_url('/local/evokegame/profile.php');
    }

    /**
     * Init method.
     */
    protected function init() {
        $this->data['crud'] = 'c';
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->data['objecttable'] = 'local_evokehub_access_token';
    }

    /**
     * Custom validation.
     *
     * @throws \coding_exception
     * @return void
     */
    protected function validate_data() {
        parent::validate_data();

        if (!isset($this->relateduserid)) {
            throw new \coding_exception('The \'relateduserid\' must be set.');
        }
    }

    public static function get_objectid_mapping() {
        return array('db' => 'local_evokehub_access_tokens', 'restore' => 'local_evokehub_access_token');
    }
}
