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
 * Define capabilities for plugin.
 *
 * @package    quizaccess_seb
 * @author     Andrew Madden <andrewmadden@catalyst-au.net>
 * @copyright  2019 Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$capabilities = array(
    'quizaccess/seb:bypassseb' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
            'editingteacher' => CAP_ALLOW
        )
    ),
    'quizaccess/seb:managetemplates' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
    ),
);

// Individual setting capabilities.
$settings = \quizaccess_seb\settings_provider::get_quiz_elements();
foreach ($settings as $name => $type) {
    // Don't add a capability for the header element.
    if ($type !== 'header') {
        $capabilities["quizaccess/seb:manage_$name"] = array(
            'captype' => 'write',
            'contextlevel' => CONTEXT_MODULE,
            'archetypes' => array(
                'editingteacher' => CAP_ALLOW
            )
        );
    }
}
