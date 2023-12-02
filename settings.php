<?php

/**
 * Global settings
 *
 * @package     local_evokehub
 * @copyright   2023 World Bank Group <https://worldbank.org>
 * @author      Willian Mano <willianmanoaraujo@gmail.com>
 */

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) { // Needs this condition or there is error on login page.
    $settings = new admin_settingpage('local_evokehub_settings',  get_string('settings', 'local_evokehub'));

    $name = 'local_evokehub/oauth_baseurl';
    $title = get_string('oauth_baseurl', 'local_evokehub');
    $default = 0;
    $setting = new admin_setting_configtext($name, $title, '', '', PARAM_RAW_TRIMMED);
    $settings->add($setting);

    $name = 'local_evokehub/oauth_clientid';
    $title = get_string('oauth_clientid', 'local_evokehub');
    $default = 0;
    $setting = new admin_setting_configtext($name, $title, '', '', PARAM_RAW_TRIMMED);
    $settings->add($setting);

    $name = 'local_evokehub/oauth_clientsecret';
    $title = get_string('oauth_clientsecret', 'local_evokehub');
    $default = 0;
    $setting = new admin_setting_configtext($name, $title, '', '', PARAM_RAW_TRIMMED);
    $settings->add($setting);

    $ADMIN->add('localplugins', $settings);
}
