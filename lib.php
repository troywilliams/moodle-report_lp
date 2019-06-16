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
 * Main Moodle plugin library.
 *
 * @package     report_lp
 * @copyright   2019 Troy Williams <troy.williams@learningworks.co.nz>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * This function extends the navigation with the report items
 *
 * @param $navigation
 * @param $course
 * @param $context
 * @throws coding_exception
 * @throws moodle_exception
 */
function report_lp_extend_navigation_course($navigation, $course, $context) {
    if (has_capability('report/lp:view', $context) and $course->id != SITEID) {
        $url = new moodle_url('/report/lp/configure.php', array('courseid' => $course->id));
        $label = get_string('pluginname', 'report_lp');
        $navigation->add($label, $url, navigation_node::TYPE_SETTING,
            null, null, new pix_icon('icon', '', 'report_lp'));
    }
}

/**
 * The main source of truth for supported measures. This as where new measures must be
 * added if order for them to be used by the plugin.
 *
 * @return array
 */
function report_lp_get_supported_measures() {
    return [
        new report_lp\local\measures\assignment_resubmit_count(),
        new report_lp\local\measures\assignment_status(),
        new report_lp\local\measures\attendance_sessions_summary(),
        new report_lp\local\measures\checklist_complete(),
        new report_lp\local\measures\grade_category_activity_completion(),
        new report_lp\local\measures\last_course_access()
    ];
}
