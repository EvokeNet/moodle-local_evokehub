<?php

/**
 * Event listener for dispatched event
 *
 * @package     local_evokehub
 * @copyright   2023 World Bank Group <https://worldbank.org>
 * @author      Willian Mano <willianmanoaraujo@gmail.com>
 */

namespace local_evokehub\observers;

defined('MOODLE_INTERNAL') || die;

use core\event\base as baseevent;

class api {
    public static function clientsetup(baseevent $event) {
        $apiutil = new \local_evokehub\util\api($event->relateduserid);

        $apiutil->clientsetup();
    }

    public static function coinsadded(baseevent $event) {
        try {
            $apiutil = new \local_evokehub\util\api($event->relateduserid);

            $apiutil->coinsadded($event->other);
        } catch (\Exception $e) {
            // Segue o jogo por enquanto.
        }
    }
}
