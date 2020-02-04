<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Global configuration settings for the quizaccess_seb plugin.
 *
 * @package    quizaccess_seb
 * @author     Andrew Madden <andrewmadden@catalyst-au.net>
 * @copyright  2019 Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

global $ADMIN;
if ($ADMIN->fulltree) {

    $links = [
        'seb' => get_string('setting:showseblink', 'quizaccess_seb'),
        'http' => get_string('setting:showhttplink', 'quizaccess_seb')
    ];
    $settings->add(new admin_setting_configmulticheckbox('quizaccess_seb/showseblinks',
            get_string('setting:showseblinks', 'quizaccess_seb'),
            get_string('setting:showseblinks_desc', 'quizaccess_seb'),
            $links, $links));

    $settings->add(new admin_setting_configtext('quizaccess_seb/downloadlink',
            get_string('setting:downloadlink', 'quizaccess_seb'),
            get_string('setting:downloadlink_desc', 'quizaccess_seb'),
            'https://safeexambrowser.org/download_en.html',
            PARAM_URL));

    $settings->add(new admin_setting_configcheckbox('quizaccess_seb/quizpasswordrequired',
            get_string('setting:quizpasswordrequired', 'quizaccess_seb'),
            get_string('setting:quizpasswordrequired_desc', 'quizaccess_seb'),
            '0'));
}
