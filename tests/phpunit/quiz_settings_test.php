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
 * PHPUnit tests for quiz_settings class.
 *
 * @package    quizaccess_seb
 * @author     Andrew Madden <andrewmadden@catalyst-au.net>
 * @copyright  2019 Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use quizaccess_seb\quiz_settings;
use quizaccess_seb\template;
use quizaccess_seb\settings_provider;
use quizaccess_seb\tests\phpunit\quizaccess_seb_testcase;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/base.php');
/**
 * PHPUnit tests for quiz_settings class.
 *
 * @copyright  2020 Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class quizaccess_seb_quiz_settings_testcase extends quizaccess_seb_testcase {

    /**
     * Called before every test.
     */
    public function setUp() {
        parent::setUp();

        $this->resetAfterTest();

        // Setup test data.
        $this->setAdminUser();
        $this->course = $this->getDataGenerator()->create_course();
        $this->quiz = $this->getDataGenerator()->create_module('quiz', [
            'course' => $this->course->id,
            'seb_requiresafeexambrowser' => settings_provider::USE_SEB_CONFIG_MANUALLY,
        ]);
        $this->context = context_module::instance($this->quiz->cmid);
        $this->url = new moodle_url("/mod/quiz/view.php", ['id' => $this->quiz->cmid]);
    }

    /**
     * Test that config is generated immediately prior to saving quiz settings.
     */
    public function test_config_is_created_from_quiz_settings() {
        // Test settings to populate the in the object.
        $settings = $this->get_test_settings();
        $settings->quizid = $this->quiz->id;
        $settings->cmid = $this->quiz->cmid;

        // Obtain the existing record that is created when using a generator.
        $quizsettings = quiz_settings::get_record(['quizid' => $this->quiz->id]);

        // Update the settings with values from the test function.
        $quizsettings->from_record($settings);
        $quizsettings->save();

        $config = $quizsettings->get('config');
        $this->assertEquals(
            "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<!DOCTYPE plist PUBLIC \"-//Apple//DTD PLIST 1.0//EN\" \"http://www.apple.com/DTDs/PropertyList-1.0.dtd\">
<plist version=\"1.0\"><dict><key>showTaskBar</key><true/><key>allowWlan</key><false/><key>showReloadButton</key><true/>"
                . "<key>showTime</key><false/><key>showInputLanguage</key><true/><key>allowQuit</key><true/>"
                . "<key>quitURLConfirm</key><true/><key>audioControlEnabled</key><true/><key>audioMute</key><false/>"
                . "<key>allowSpellCheck</key><false/><key>browserWindowAllowReload</key><true/><key>URLFilterEnable</key><true/>"
                . "<key>URLFilterEnableContentFilter</key><false/><key>hashedQuitPassword</key>"
                . "<string>9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08</string><key>URLFilterRules</key>"
                . "<array><dict><key>action</key><integer>1</integer><key>active</key><true/><key>expression</key>"
                . "<string>test.com</string><key>regex</key><false/></dict></array><key>startURL</key><string>$this->url</string>"
                . "<key>sendBrowserExamKey</key><true/><key>examSessionClearCookiesOnStart</key><false/></dict></plist>\n",
            $config);
    }

    /**
     * Test that config string gets updated from quiz settings.
     */
    public function test_config_is_updated_from_quiz_settings() {
        // Test settings to populate the in the object.
        $settings = $this->get_test_settings();
        $settings->quizid = $this->quiz->id;
        $settings->cmid = $this->quiz->cmid;

        // Obtain the existing record that is created when using a generator.
        $quizsettings = quiz_settings::get_record(['quizid' => $this->quiz->id]);

        // Update the settings with values from the test function.
        $quizsettings->from_record($settings);
        $quizsettings->save();

        $config = $quizsettings->get('config');
        $this->assertEquals("<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<!DOCTYPE plist PUBLIC \"-//Apple//DTD PLIST 1.0//EN\" \"http://www.apple.com/DTDs/PropertyList-1.0.dtd\">
<plist version=\"1.0\"><dict><key>showTaskBar</key><true/><key>allowWlan</key><false/><key>showReloadButton</key><true/>"
            . "<key>showTime</key><false/><key>showInputLanguage</key><true/><key>allowQuit</key><true/>"
            . "<key>quitURLConfirm</key><true/><key>audioControlEnabled</key><true/><key>audioMute</key><false/>"
            . "<key>allowSpellCheck</key><false/><key>browserWindowAllowReload</key><true/><key>URLFilterEnable</key><true/>"
            . "<key>URLFilterEnableContentFilter</key><false/><key>hashedQuitPassword</key>"
            . "<string>9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08</string><key>URLFilterRules</key>"
            . "<array><dict><key>action</key><integer>1</integer><key>active</key><true/><key>expression</key>"
            . "<string>test.com</string><key>regex</key><false/></dict></array><key>startURL</key><string>$this->url</string>"
            . "<key>sendBrowserExamKey</key><true/><key>examSessionClearCookiesOnStart</key><false/></dict></plist>\n", $config);

        $quizsettings->set('filterembeddedcontent', 1); // Alter the settings.
        $quizsettings->save();
        $config = $quizsettings->get('config');
        $this->assertEquals("<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<!DOCTYPE plist PUBLIC \"-//Apple//DTD PLIST 1.0//EN\" \"http://www.apple.com/DTDs/PropertyList-1.0.dtd\">
<plist version=\"1.0\"><dict><key>showTaskBar</key><true/><key>allowWlan</key><false/><key>showReloadButton</key><true/>"
            . "<key>showTime</key><false/><key>showInputLanguage</key><true/><key>allowQuit</key><true/>"
            . "<key>quitURLConfirm</key><true/><key>audioControlEnabled</key><true/><key>audioMute</key><false/>"
            . "<key>allowSpellCheck</key><false/><key>browserWindowAllowReload</key><true/><key>URLFilterEnable</key><true/>"
            . "<key>URLFilterEnableContentFilter</key><true/><key>hashedQuitPassword</key>"
            . "<string>9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08</string><key>URLFilterRules</key>"
            . "<array><dict><key>action</key><integer>1</integer><key>active</key><true/><key>expression</key>"
            . "<string>test.com</string><key>regex</key><false/></dict></array><key>startURL</key><string>$this->url</string>"
            . "<key>sendBrowserExamKey</key><true/><key>examSessionClearCookiesOnStart</key><false/></dict></plist>\n", $config);
    }

    /**
     * Test that config key is generated immediately prior to saving quiz settings.
     */
    public function test_config_key_is_created_from_quiz_settings() {
        $settings = $this->get_test_settings();

        $quizsettings = new quiz_settings(0, $settings);
        $this->assertEmpty($quizsettings->get('configkey'));
        $quizsettings->create();
        $configkey = $quizsettings->get('configkey');
        $this->assertEquals("89557366c34cb64a33781e1fb767cb15380731efdfb967e037476ef420f1d7b8",
                $configkey);
    }

    /**
     * Test that config key is generated immediately prior to saving quiz settings.
     */
    public function test_config_key_is_updated_from_quiz_settings() {
        $settings = $this->get_test_settings();

        $quizsettings = new quiz_settings(0, $settings);
        $this->assertEmpty($quizsettings->get('configkey'));
        $quizsettings->create();
        $configkey = $quizsettings->get('configkey');
        $this->assertEquals("89557366c34cb64a33781e1fb767cb15380731efdfb967e037476ef420f1d7b8",
                $configkey);
        $quizsettings->set('filterembeddedcontent', 1); // Alter the settings.
        $quizsettings->save();
        $configkey = $quizsettings->get('configkey');
        $this->assertEquals("4476e7fc1e4e769c930685535b0f6377e6f736cd6c24b68db512455a95f028b5",
            $configkey);
    }

    /**
     * Test that different URL filter expressions are turned into config XML.
     *
     * @param stdClass $settings Quiz settings
     * @param string $expectedxml SEB Config XML.
     *
     * @dataProvider filter_rules_provider
     */
    public function test_filter_rules_added_to_config(stdClass $settings, string $expectedxml) {
        $quizsettings = new quiz_settings(0, $settings);
        $this->assertEmpty($quizsettings->get('config'));
        $quizsettings->create();
        $config = $quizsettings->get('config');
        $this->assertEquals($expectedxml, $config);
    }

    /**
     * Test that browser keys are validated and retrieved as an array instead of string.
     */
    public function test_browser_exam_keys_are_retrieved_as_array() {
        $quizsettings = new quiz_settings();
        $quizsettings->set('allowedbrowserexamkeys', "one two,three\nfour");
        $retrievedkeys = $quizsettings->get('allowedbrowserexamkeys');
        $this->assertEquals(['one', 'two', 'three', 'four'], $retrievedkeys);
    }

    /**
     * Test validation of Browser Exam Keys.
     *
     * @param string $bek Browser Exam Key.
     * @param string $expectederrorstring Expected error.
     *
     * @dataProvider bad_browser_exam_key_provider
     */
    public function test_browser_exam_keys_validation_errors($bek, $expectederrorstring) {
        $quizsettings = new quiz_settings();
        $quizsettings->set('allowedbrowserexamkeys', $bek);
        $quizsettings->validate();
        $errors = $quizsettings->get_errors();
        $this->assertContains($expectederrorstring, $errors);
    }

    /**
     * Test that uploaded seb file gets converted to config string.
     */
    public function test_config_file_uploaded_converted_to_config() {
        $url = new moodle_url("/mod/quiz/view.php", ['id' => $this->quiz->cmid]);
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
                . "<!DOCTYPE plist PUBLIC \"-//Apple//DTD PLIST 1.0//EN\" \"http://www.apple.com/DTDs/PropertyList-1.0.dtd\">\n"
                . "<plist version=\"1.0\"><dict><key>hashedQuitPassword</key><string>hashedpassword</string>"
                . "<key>allowWlan</key><false/><key>startURL</key><string>$url</string>"
                . "<key>sendBrowserExamKey</key><true/></dict></plist>\n";
        $itemid = $this->create_module_test_file($xml, $this->quiz->cmid);
        $quizsettings = quiz_settings::get_record(['quizid' => $this->quiz->id]);
        $quizsettings->set('requiresafeexambrowser', settings_provider::USE_SEB_UPLOAD_CONFIG);
        $quizsettings->save();
        $config = $quizsettings->get('config');
        $this->assertEquals($xml, $config);
    }

    /**
     * Test test_no_config_file_uploaded
     */
    public function test_no_config_file_uploaded() {
        $quizsettings = quiz_settings::get_record(['quizid' => $this->quiz->id]);
        $quizsettings->set('requiresafeexambrowser', settings_provider::USE_SEB_UPLOAD_CONFIG);
        $cmid = $quizsettings->get('cmid');
        $this->expectException(moodle_exception::class);
        $this->expectExceptionMessage("No uploaded SEB config file could be found for quiz with cmid: {$cmid}");
        $quizsettings->save();
    }

    /**
     * Test using USE_SEB_TEMPLATE and have it override defaults.
     */
    public function test_using_seb_template_override_settings() {
        $xml = file_get_contents(__DIR__ . '/sample_data/unencrypted.seb');

        $template = new template();
        $template->set('content', $xml);
        $template->set('name', 'test');
        $template->save();
        $this->assertContains("<key>startURL</key><string>https://safeexambrowser.org/start</string>", $template->get('content'));
        $this->assertContains("<key>allowQuit</key><true/>", $template->get('content'));

        $quizsettings = quiz_settings::get_record(['quizid' => $this->quiz->id]);
        $quizsettings->set('requiresafeexambrowser', settings_provider::USE_SEB_TEMPLATE);
        $quizsettings->set('templateid', $template->get('id'));
        $quizsettings->set('allowuserquitseb', 0);
        $quizsettings->set('quitpassword', '123');
        $quizsettings->save();
        $this->assertContains(
            "<key>startURL</key><string>https://www.example.com/moodle/mod/quiz/view.php?id={$this->quiz->cmid}</string>",
            $quizsettings->get('config')
        );
        $this->assertContains("<key>allowQuit</key><true/>", $quizsettings->get('config'));
        $hashedpassword = hash('SHA256', '123');
        $this->assertNotContains("<key>hashedQuitPassword</key><string>123</string>", $quizsettings->get('config'));
        $this->assertContains("<key>hashedQuitPassword</key><string>{$hashedpassword}</string>", $quizsettings->get('config'));
    }

    /**
     * Test using USE_SEB_UPLOAD_CONFIG and overriding the password.
     */
    public function test_using_own_config_and_overriding_password() {
        $url = new moodle_url("/mod/quiz/view.php", ['id' => $this->quiz->cmid]);
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
            . "<!DOCTYPE plist PUBLIC \"-//Apple//DTD PLIST 1.0//EN\" \"http://www.apple.com/DTDs/PropertyList-1.0.dtd\">\n"
            . "<plist version=\"1.0\"><dict><key>hashedQuitPassword</key><string>hashedpassword</string>"
            . "<key>allowWlan</key><false/><key>startURL</key><string>$url</string>"
            . "<key>sendBrowserExamKey</key><true/></dict></plist>\n";
        $itemid = $this->create_module_test_file($xml, $this->quiz->cmid);
        $quizsettings = quiz_settings::get_record(['quizid' => $this->quiz->id]);
        $quizsettings->set('requiresafeexambrowser', settings_provider::USE_SEB_UPLOAD_CONFIG);
        $quizsettings->set('quitpassword', '123');
        $quizsettings->save();
        $config = $quizsettings->get('config');

        $hashedpassword = hash('SHA256', '123');
        $this->assertNotContains("<key>hashedQuitPassword</key><string>hashedpassword</string>", $config);
        $this->assertContains("<key>hashedQuitPassword</key><string>{$hashedpassword}</string>", $config);
    }

    /**
     * Test using USE_SEB_TEMPLATE populates the linkquitseb setting if a quitURL is found.
     */
    public function test_template_has_quit_url_set() {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
            . "<!DOCTYPE plist PUBLIC \"-//Apple//DTD PLIST 1.0//EN\" \"http://www.apple.com/DTDs/PropertyList-1.0.dtd\">\n"
            . "<plist version=\"1.0\"><dict><key>hashedQuitPassword</key><string>hashedpassword</string>"
            . "<key>allowWlan</key><false/><key>quitURL</key><string>http://seb.quit.url</string>"
            . "<key>sendBrowserExamKey</key><true/></dict></plist>\n";

        $template = new template();
        $template->set('content', $xml);
        $template->set('name', 'test');
        $template->save();

        $quizsettings = quiz_settings::get_record(['quizid' => $this->quiz->id]);
        $quizsettings->set('requiresafeexambrowser', settings_provider::USE_SEB_TEMPLATE);
        $quizsettings->set('templateid', $template->get('id'));

        $this->assertEmpty($quizsettings->get('linkquitseb'));
        $quizsettings->save();

        $this->assertNotEmpty($quizsettings->get('linkquitseb'));
        $this->assertEquals('http://seb.quit.url', $quizsettings->get('linkquitseb'));
    }

    /**
     * Test using USE_SEB_UPLOAD_CONFIG populates the linkquitseb setting if a quitURL is found.
     */
    public function test_config_file_uploaded_has_quit_url_set() {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
            . "<!DOCTYPE plist PUBLIC \"-//Apple//DTD PLIST 1.0//EN\" \"http://www.apple.com/DTDs/PropertyList-1.0.dtd\">\n"
            . "<plist version=\"1.0\"><dict><key>hashedQuitPassword</key><string>hashedpassword</string>"
            . "<key>allowWlan</key><false/><key>quitURL</key><string>http://seb.quit.url</string>"
            . "<key>sendBrowserExamKey</key><true/></dict></plist>\n";

        $itemid = $this->create_module_test_file($xml, $this->quiz->cmid);
        $quizsettings = quiz_settings::get_record(['quizid' => $this->quiz->id]);
        $quizsettings->set('requiresafeexambrowser', settings_provider::USE_SEB_UPLOAD_CONFIG);

        $this->assertEmpty($quizsettings->get('linkquitseb'));
        $quizsettings->save();

        $this->assertNotEmpty($quizsettings->get('linkquitseb'));
        $this->assertEquals('http://seb.quit.url', $quizsettings->get('linkquitseb'));
    }

    /**
     * Bad browser exam key data provider.
     *
     * @return array
     */
    public function bad_browser_exam_key_provider() : array {
        return [
            'Short string' => ['fdsf434r',
                    'A key should be a 64-character hex string.'],
            'Non hex string' => ['aadf6799aadf6789aadf6789aadf6789aadf6789aadf6789aadf6789aadf678!',
                    'A key should be a 64-character hex string.'],
            'Non unique' => ["aadf6799aadf6789aadf6789aadf6789aadf6789aadf6789aadf6789aadf6789"
                    . "\naadf6799aadf6789aadf6789aadf6789aadf6789aadf6789aadf6789aadf6789", 'The keys must all be different.'],
        ];
    }

    /**
     * Provide settings for different filter rules.
     *
     * @return array Test data.
     */
    public function filter_rules_provider() : array {
        return [
            'enabled simple expessions' => [
                (object) [
                    'requiresafeexambrowser' => settings_provider::USE_SEB_CONFIG_MANUALLY,
                    'quizid' => 1,
                    'cmid' => 1,
                    'expressionsallowed' => "test.com\r\nsecond.hello",
                    'regexallowed' => '',
                    'expressionsblocked' => '',
                    'regexblocked' => '',
                ],
                "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
                . "<!DOCTYPE plist PUBLIC \"-//Apple//DTD PLIST 1.0//EN\" \"http://www.apple.com/DTDs/PropertyList-1.0.dtd\">\n"
                . "<plist version=\"1.0\"><dict><key>showTaskBar</key><true/>"
                . "<key>allowWlan</key><false/><key>showReloadButton</key>"
                . "<true/><key>showTime</key><true/><key>showInputLanguage</key><true/><key>allowQuit</key><true/>"
                . "<key>quitURLConfirm</key><true/><key>audioControlEnabled</key><false/><key>audioMute</key><false/>"
                . "<key>allowSpellCheck</key><false/><key>browserWindowAllowReload</key><true/><key>URLFilterEnable</key><false/>"
                . "<key>URLFilterEnableContentFilter</key><false/><key>URLFilterRules</key><array>"
                . "<dict><key>action</key><integer>1</integer><key>active</key><true/>"
                . "<key>expression</key><string>test.com</string>"
                . "<key>regex</key><false/></dict><dict><key>action</key><integer>1</integer>"
                . "<key>active</key><true/><key>expression</key>"
                . "<string>second.hello</string><key>regex</key><false/></dict></array>"
                . "<key>startURL</key><string>https://www.example.com/moodle/mod/quiz/view.php?id=1</string>"
                . "<key>sendBrowserExamKey</key><true/><key>examSessionClearCookiesOnStart</key><false/></dict></plist>\n",
            ],
            'blocked simple expessions' => [
                (object) [
                    'requiresafeexambrowser' => settings_provider::USE_SEB_CONFIG_MANUALLY,
                    'quizid' => 1,
                    'cmid' => 1,
                    'expressionsallowed' => '',
                    'regexallowed' => '',
                    'expressionsblocked' => "test.com\r\nsecond.hello",
                    'regexblocked' => '',
                ],
                "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
                . "<!DOCTYPE plist PUBLIC \"-//Apple//DTD PLIST 1.0//EN\" \"http://www.apple.com/DTDs/PropertyList-1.0.dtd\">\n"
                . "<plist version=\"1.0\"><dict><key>showTaskBar</key><true/>"
                . "<key>allowWlan</key><false/><key>showReloadButton</key>"
                . "<true/><key>showTime</key><true/><key>showInputLanguage</key><true/><key>allowQuit</key><true/>"
                . "<key>quitURLConfirm</key><true/><key>audioControlEnabled</key><false/><key>audioMute</key><false/>"
                . "<key>allowSpellCheck</key><false/><key>browserWindowAllowReload</key><true/><key>URLFilterEnable</key><false/>"
                . "<key>URLFilterEnableContentFilter</key><false/><key>URLFilterRules</key><array>"
                . "<dict><key>action</key><integer>0</integer><key>active</key><true/>"
                . "<key>expression</key><string>test.com</string>"
                . "<key>regex</key><false/></dict><dict><key>action</key><integer>0</integer>"
                . "<key>active</key><true/><key>expression</key>"
                . "<string>second.hello</string><key>regex</key><false/></dict></array>"
                . "<key>startURL</key><string>https://www.example.com/moodle/mod/quiz/view.php?id=1</string>"
                . "<key>sendBrowserExamKey</key><true/><key>examSessionClearCookiesOnStart</key><false/></dict></plist>\n",
            ],
            'enabled regex expessions' => [
                (object) [
                    'requiresafeexambrowser' => settings_provider::USE_SEB_CONFIG_MANUALLY,
                    'quizid' => 1,
                    'cmid' => 1,
                    'expressionsallowed' => '',
                    'regexallowed' => "test.com\r\nsecond.hello",
                    'expressionsblocked' => '',
                    'regexblocked' => '',
                ],
                "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
                . "<!DOCTYPE plist PUBLIC \"-//Apple//DTD PLIST 1.0//EN\" \"http://www.apple.com/DTDs/PropertyList-1.0.dtd\">\n"
                . "<plist version=\"1.0\"><dict><key>showTaskBar</key><true/>"
                . "<key>allowWlan</key><false/><key>showReloadButton</key>"
                . "<true/><key>showTime</key><true/><key>showInputLanguage</key><true/><key>allowQuit</key><true/>"
                . "<key>quitURLConfirm</key><true/><key>audioControlEnabled</key><false/><key>audioMute</key><false/>"
                . "<key>allowSpellCheck</key><false/><key>browserWindowAllowReload</key><true/><key>URLFilterEnable</key><false/>"
                . "<key>URLFilterEnableContentFilter</key><false/><key>URLFilterRules</key><array>"
                . "<dict><key>action</key><integer>1</integer><key>active</key><true/>"
                . "<key>expression</key><string>test.com</string>"
                . "<key>regex</key><true/></dict><dict><key>action</key><integer>1</integer>"
                . "<key>active</key><true/><key>expression</key>"
                . "<string>second.hello</string><key>regex</key><true/></dict></array>"
                . "<key>startURL</key><string>https://www.example.com/moodle/mod/quiz/view.php?id=1</string>"
                . "<key>sendBrowserExamKey</key><true/><key>examSessionClearCookiesOnStart</key><false/></dict></plist>\n",
            ],
            'blocked regex expessions' => [
                (object) [
                    'requiresafeexambrowser' => settings_provider::USE_SEB_CONFIG_MANUALLY,
                    'quizid' => 1,
                    'cmid' => 1,
                    'expressionsallowed' => '',
                    'regexallowed' => '',
                    'expressionsblocked' => '',
                    'regexblocked' => "test.com\r\nsecond.hello",
                ],
                "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
                . "<!DOCTYPE plist PUBLIC \"-//Apple//DTD PLIST 1.0//EN\" \"http://www.apple.com/DTDs/PropertyList-1.0.dtd\">\n"
                . "<plist version=\"1.0\"><dict><key>showTaskBar</key><true/>"
                . "<key>allowWlan</key><false/><key>showReloadButton</key>"
                . "<true/><key>showTime</key><true/><key>showInputLanguage</key><true/><key>allowQuit</key><true/>"
                . "<key>quitURLConfirm</key><true/><key>audioControlEnabled</key><false/><key>audioMute</key><false/>"
                . "<key>allowSpellCheck</key><false/><key>browserWindowAllowReload</key><true/><key>URLFilterEnable</key><false/>"
                . "<key>URLFilterEnableContentFilter</key><false/><key>URLFilterRules</key><array>"
                . "<dict><key>action</key><integer>0</integer><key>active</key><true/>"
                . "<key>expression</key><string>test.com</string>"
                . "<key>regex</key><true/></dict><dict><key>action</key><integer>0</integer>"
                . "<key>active</key><true/><key>expression</key>"
                . "<string>second.hello</string><key>regex</key><true/></dict></array>"
                . "<key>startURL</key><string>https://www.example.com/moodle/mod/quiz/view.php?id=1</string>"
                . "<key>sendBrowserExamKey</key><true/><key>examSessionClearCookiesOnStart</key><false/></dict></plist>\n",
            ],
            'multiple simple expessions' => [
                (object) [
                    'requiresafeexambrowser' => settings_provider::USE_SEB_CONFIG_MANUALLY,
                    'quizid' => 1,
                    'cmid' => 1,
                    'expressionsallowed' => "*",
                    'regexallowed' => '',
                    'expressionsblocked' => '',
                    'regexblocked' => "test.com\r\nsecond.hello",
                ],
                "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
                . "<!DOCTYPE plist PUBLIC \"-//Apple//DTD PLIST 1.0//EN\" \"http://www.apple.com/DTDs/PropertyList-1.0.dtd\">\n"
                . "<plist version=\"1.0\"><dict><key>showTaskBar</key><true/>"
                . "<key>allowWlan</key><false/><key>showReloadButton</key>"
                . "<true/><key>showTime</key><true/><key>showInputLanguage</key><true/><key>allowQuit</key><true/>"
                . "<key>quitURLConfirm</key><true/><key>audioControlEnabled</key><false/><key>audioMute</key><false/>"
                . "<key>allowSpellCheck</key><false/><key>browserWindowAllowReload</key><true/><key>URLFilterEnable</key><false/>"
                . "<key>URLFilterEnableContentFilter</key><false/><key>URLFilterRules</key><array><dict><key>action</key>"
                . "<integer>1</integer><key>active</key><true/><key>expression</key><string>*</string>"
                . "<key>regex</key><false/></dict>"
                . "<dict><key>action</key><integer>0</integer><key>active</key><true/>"
                . "<key>expression</key><string>test.com</string>"
                . "<key>regex</key><true/></dict><dict><key>action</key><integer>0</integer>"
                . "<key>active</key><true/><key>expression</key>"
                . "<string>second.hello</string><key>regex</key><true/></dict></array>"
                . "<key>startURL</key><string>https://www.example.com/moodle/mod/quiz/view.php?id=1</string>"
                . "<key>sendBrowserExamKey</key><true/><key>examSessionClearCookiesOnStart</key><false/></dict></plist>\n",
            ],
        ];
    }

    /**
     * Get a test object containing mock test settings.
     *
     * @return stdClass Settings.
     */
    private function get_test_settings() : stdClass {
        return (object) [
            'quizid' => 1,
            'cmid' => 1,
            'requiresafeexambrowser' => '1',
            'sebconfigfile' => '373552893',
            'showsebtaskbar' => '1',
            'showwificontrol' => '0',
            'showreloadbutton' => '1',
            'showtime' => '0',
            'showkeyboardlayout' => '1',
            'allowuserquitseb' => '1',
            'quitpassword' => 'test',
            'linkquitseb' => '',
            'userconfirmquit' => '1',
            'enableaudiocontrol' => '1',
            'muteonstartup' => '0',
            'allowspellchecking' => '0',
            'allowreloadinexam' => '1',
            'activateurlfiltering' => '1',
            'filterembeddedcontent' => '0',
            'expressionsallowed' => 'test.com',
            'regexallowed' => '',
            'expressionsblocked' => '',
            'regexblocked' => '',
            'suppresssebdownloadlink' => '1',
        ];
    }

}
