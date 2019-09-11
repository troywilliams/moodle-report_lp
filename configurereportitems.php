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

require_once(__DIR__ . '/../../config.php');

$courseid   = required_param('courseid', PARAM_INT);

$course = get_course($courseid);
$coursecontext = context_course::instance($courseid);

require_login($course);
require_capability('report/lp:configure', $coursecontext);

$url = new moodle_url(
    '/report/lp/configure.php',
    ['courseid' => $course->id]
);
$PAGE->set_url($url);

$itemtypelist = new report_lp\local\item_type_list();
$configureportitems = new report_lp\output\configure_report_items($course);
$renderer = $PAGE->get_renderer('report_lp');

$css = new moodle_url('/report/lp/scss/styles.css');
$PAGE->requires->css($css);
$renderer = $PAGE->get_renderer('report_lp');
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('configurereportfor', 'report_lp', $course->fullname));
echo $renderer->render($configureportitems);
$data = $configureportitems->export_for_template($renderer);print_object($data);//die;
echo $OUTPUT->footer();
