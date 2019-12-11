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
 * Controller for managing quiz settings.
 *
 * @package    quizaccess_seb
 * @author     Andrew Madden <andrewmadden@catalyst-au.net>
 * @copyright  2019 Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace quizaccess_seb;

defined('MOODLE_INTERNAL') || die();

class settings_manager {

    /**
     * Get all form elements that are a 'filepicker' type.
     *
     * @param bool $strippedprefix Defaults to true.
     * @return array
     */
    public function get_file_picker_elements($strippedprefix = true) : array {
        $formelements = $this->get_quiz_element_types();
        $filepickers = [];
        foreach ($formelements as $name => $type) {
            if ($type == 'filepicker') {
                if ($strippedprefix) {
                    // Strip key prefix.
                    $name = preg_replace("/^seb_/", "", $name);
                }
                $filepickers[] = $name;
            }
        }
        return $filepickers;
    }

    /**
     * Get the type of element for each of the form elements in quiz settings.
     *
     * Contains all setting elements. Array key is name of 'form element'/'database column (excluding prefix)'.
     *
     * @return array All quiz form elements to be added and their types.
     */
    public function get_quiz_element_types() : array {
        return [
            'seb' => 'header',
            'seb_requiresafeexambrowser' => 'selectyesno',
            'seb_sebconfigtemplate' => 'select',
            'seb_sebconfigfile' => 'filepicker',
            'seb_showsebtaskbar' => 'selectyesno',
            'seb_showwificontrol' => 'selectyesno',
            'seb_showreloadbutton' => 'selectyesno',
            'seb_showtime' => 'selectyesno',
            'seb_showkeyboardlayout' => 'selectyesno',
            'seb_allowuserquitseb' => 'selectyesno',
            'seb_quitpassword' => 'passwordunmask',
            'seb_linkquitseb' => 'selectyesno',
            'seb_userconfirmquit' => 'selectyesno',
            'seb_enableaudiocontrol' => 'selectyesno',
            'seb_muteonstartup' => 'selectyesno',
            'seb_allowspellchecking' => 'selectyesno',
            'seb_allowreloadinexam' => 'selectyesno',
            'seb_activateurlfiltering' => 'selectyesno',
            'seb_filterembeddedcontent' => 'selectyesno',
            'seb_expressionsallowed' => 'textarea',
            'seb_regexallowed' => 'textarea',
            'seb_expressionsblocked' => 'textarea',
            'seb_regexblocked' => 'textarea',
            'seb_suppresssebdownloadlink' => 'selectyesno',
        ];
    }

    /**
     * Get the default values of the quiz settings.
     *
     * Array key is name of 'form element'/'database column (excluding prefix)'.
     * TODO: Update sebconfigtemplate default after templates are implemented.
     *
     * @return array List of settings and their defaults.
     */
    public function get_quiz_defaults() : array {
        return [
            'seb_requiresafeexambrowser' => 0,
            'seb_sebconfigtemplate' => 0,
            'seb_sebconfigfile' => null,
            'seb_showsebtaskbar' => 1,
            'seb_showwificontrol' => 0,
            'seb_showreloadbutton' => 1,
            'seb_showtime' => 1,
            'seb_showkeyboardlayout' => 1,
            'seb_allowuserquitseb' => 1,
            'seb_quitpassword' => '',
            'seb_linkquitseb' => 0,
            'seb_userconfirmquit' => 1,
            'seb_enableaudiocontrol' => 0,
            'seb_muteonstartup' => 0,
            'seb_allowspellchecking' => 0,
            'seb_allowreloadinexam' => 1,
            'seb_activateurlfiltering' => 0,
            'seb_filterembeddedcontent' => 0,
            'seb_expressionsallowed' => '',
            'seb_regexallowed' => '',
            'seb_expressionsblocked' => '',
            'seb_regexblocked' => '',
            'seb_suppresssebdownloadlink' => 0,
        ];
    }

    /**
     * Get the conditions that an element should be hid in the form. Expects matching using 'eq'.
     *
     * Array key is name of 'form element'/'database column (excluding prefix)'.
     * Values of main array contain array of name => condition pairs. When matching, element should be hidden.
     * E.g. If requiresafeexambrowser is set to false, all other settings are hidden.
     *
     * @return array List of rules per element.
     */
    public function get_quiz_hideifs() : array {
        return [
            'seb_sebconfigtemplate' => [
                'seb_requiresafeexambrowser' => 0,
            ],
            'seb_sebconfigfile' => [
                'seb_requiresafeexambrowser' => 0,
            ],
            'seb_showsebtaskbar' => [
                'seb_requiresafeexambrowser' => 0,
            ],
            'seb_showwificontrol' => [
                'seb_requiresafeexambrowser' => 0,
                'seb_showsebtaskbar' => 0,
            ],
            'seb_showreloadbutton' => [
                'seb_requiresafeexambrowser' => 0,
                'seb_showsebtaskbar' => 0,
            ],
            'seb_showtime' => [
                'seb_requiresafeexambrowser' => 0,
                'seb_showsebtaskbar' => 0,
            ],
            'seb_showkeyboardlayout' => [
                'seb_requiresafeexambrowser' => 0,
                'seb_showsebtaskbar' => 0,
            ],
            'seb_allowuserquitseb' => [
                'seb_requiresafeexambrowser' => 0,
            ],
            'seb_quitpassword' => [
                'seb_requiresafeexambrowser' => 0,
                'seb_allowuserquitseb' => 0,
            ],
            'seb_linkquitseb' => [
                'seb_requiresafeexambrowser' => 0,
            ],
            'seb_userconfirmquit' => [
                'seb_requiresafeexambrowser' => 0,
                'seb_linkquitseb' => 0,
            ],
            'seb_enableaudiocontrol' => [
                'seb_requiresafeexambrowser' => 0,
            ],
            'seb_muteonstartup' => [
                'seb_requiresafeexambrowser' => 0,
                'seb_enableaudiocontrol' => 0,
            ],
            'seb_allowspellchecking' => [
                'seb_requiresafeexambrowser' => 0,
            ],
            'seb_allowreloadinexam' => [
                'seb_requiresafeexambrowser' => 0,
            ],
            'seb_activateurlfiltering' => [
                'seb_requiresafeexambrowser' => 0,
            ],
            'seb_filterembeddedcontent' => [
                'seb_requiresafeexambrowser' => 0,
                'seb_activateurlfiltering' => 0,
            ],
            'seb_expressionsallowed' => [
                'seb_requiresafeexambrowser' => 0,
                'seb_activateurlfiltering' => 0,
            ],
            'seb_regexallowed' => [
                'seb_requiresafeexambrowser' => 0,
                'seb_activateurlfiltering' => 0,
            ],
            'seb_expressionsblocked' => [
                'seb_requiresafeexambrowser' => 0,
                'seb_activateurlfiltering' => 0,
            ],
            'seb_regexblocked' => [
                'seb_requiresafeexambrowser' => 0,
                'seb_activateurlfiltering' => 0,
            ],
            'seb_suppresssebdownloadlink' => [
                'seb_requiresafeexambrowser' => 0,
            ],
        ];
    }
}

