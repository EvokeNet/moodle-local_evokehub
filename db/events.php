<?php

/**
 * Game changer events definition
 *
 * @package     local_evokehub
 * @copyright   2023 World Bank Group <https://worldbank.org>
 * @author      Willian Mano <willianmanoaraujo@gmail.com>
 */

defined('MOODLE_INTERNAL') || die();

$observers = [
    [
        'eventname' => '\local_evokehub\event\user_connected',
        'callback' => '\local_evokehub\observers\api::clientsetup',
        'internal' => false
    ],
    [
        'eventname' => '\local_evokegame\event\evocoins_added',
        'callback' => '\local_evokehub\observers\api::coinsadded',
        'internal' => false
    ],
];
