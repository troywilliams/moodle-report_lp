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
    if ($course->id != SITEID) {
        if (has_capability('report/lp:configure', $context)) {
            $url = report_lp\local\factories\url::get_config_url($course);
            $label = get_string('configurelearnerprogressreport', 'report_lp');
            $navigation->add(
                $label,
                $url,
                navigation_node::TYPE_SETTING,
                null,
                null,
                new pix_icon('icon', '', 'report_lp')
            );
        }
        if (has_capability('report/lp:viewsummary', $context)) {
            $url = report_lp\local\factories\url::get_summary_url($course);
            $label = get_string('viewlearnerprogresssummary', 'report_lp');
            $navigation->add(
                $label,
                $url,
                navigation_node::TYPE_SETTING,
                null,
                null,
                new pix_icon('icon', '', 'report_lp')
            );
        }
    }
}
