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

namespace report_lp\output;

defined('MOODLE_INTERNAL') || die();

use moodle_url;
use pix_icon;
use renderable;
use renderer_base;
use report_lp\local\grouping;
use report_lp\local\item;
use report_lp\local\measure;
use report_lp\local\item_tree;
use report_lp\local\factories\url;
use report_lp\local\summary;
use coding_exception;
use report_lp\local\course_group;
use stdClass;
use templatable;
use user_picture;

/**
 *
 * @package     report_lp
 * @copyright   2019 Troy Williams <troy.williams@learningworks.co.nz>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class summary_report implements renderable, templatable {

    private $rendererbase;

    protected $summary;

    protected $learnerextrafields = ['idnumber', 'coursegroups'];

    public function __construct(summary $summary) {
        $this->summary = $summary;
    }

    public function export_for_template(renderer_base $output) {
        $data = new stdClass();

        $learnerlist = $this->summary->get_learner_list();
        if (empty($learnerlist->get_filtered_course_groups())) {
            $nofilteredcoursegroups = new stdClass();
            $nofilteredcoursegroups->rowsimageurl = $output->image_url('rows', 'report_lp')->out();
            $data->nofilteredcoursegroups = $nofilteredcoursegroups;
            return $data;
        }

        $itemtree = $this->summary->get_item_tree();
        $items = $itemtree->get_flattened_tree();
        $primaryheader = new stdClass();
        $primaryheader->columns = $this->primary_header_columns($output, $items);
        $data->primaryheader = $primaryheader;

        $secondaryheader = new stdClass();
        $secondaryheader->columns = $this->secondary_header_columns($output, $items);
        $data->secondaryheader = $secondaryheader;
        $learnerlist->fetch_all();

        $measures = $itemtree->get_measures();
        foreach ($learnerlist as $learner) {
            $row = new stdClass();
            $row->learner = $this->data_row_user($output, $learner);
            $row->measures = $this->measure_data_for_user($learner, $measures);
            $data->rows[] = $row;
        }
        return $data;
    }

    public function measure_data_for_user($learner, $measures) {
        $row = [];
        foreach ($measures as $measure) {
            /** @var measure $measure */
           $learnerdata = $measure->get_data_for_user($learner->id);
           $data = new stdClass();
           $data->value = $measure->format_user_measure_data($learnerdata, FORMAT_HTML);
           $row[] = $data;
        }
        return $row;
    }

    public function data_row_user(renderer_base $output, $learner) {
        global $PAGE;
        $learner->fullname = fullname($learner);
        $learner->enrolmentstatus = $learner->status;
        $course = $this->summary->get_course();
        $learnerscoursegroups = course_group::get_groups_for_user($course->id, $learner->id);
        $groups = [];
        foreach ($learnerscoursegroups as $learnerscoursegroup) {
            $groups[] = $learnerscoursegroup->name;
        }
        $learner->coursegroups = implode(', ', $groups);
        $profileimage = new user_picture($learner);
        $profileimageeurl = $profileimage->get_url($PAGE, $output);
        $learner->imageurl = $profileimageeurl->out();
        if (empty($learner->imagealt)) {
            $learner->imagealt = get_string('pictureof', '', $learner->fullname);
        }
        return $learner;
    }

    public function primary_header_columns(renderer_base $output, array $items) {
        $columns = [];
        $th = new stdClass();
        $th->label = get_string('learner', 'report_lp');
        $th->colspan = 2 + count($this->learnerextrafields);
        $columns[] = $th;
        foreach ($items as $item) {
            $configuration = $item->get_configuration();
            if ($configuration->get('depth') != 2) {
                continue;
            }
            $th = new stdClass();
            $th->label = '';
            $th->colspan = 1;
            if ($item instanceof grouping) {
                $th->label = $item->get_label();
                $th->colspan = $item->count();
            }
            $columns[] = $th;
        }
        return $columns;
    }

    public function secondary_header_columns(renderer_base $output, array $items) {
        $columns = [];
        $th = new stdClass();
        $th->colspan = 2;
        $columns[] = $th;
        foreach ($this->learnerextrafields as $learnerextrafield) {
            $th = new stdClass();
            $th->label = get_string($learnerextrafield, 'report_lp');
            $columns[] = $th;
        }
        foreach ($items as $item) {
            /** @var item $item */
            $configuration = $item->get_configuration();
            if ($configuration->get('depth') != 2) {
                continue;
            }
            if ($item instanceof grouping) {
               $children = $item->get_children();
               foreach ($children as $child) {
                   $th = new stdClass();
                   $th->label = $child->get_label();
                   if ($child->has_icon()) {
                       $icon = $child->get_icon();
                       $th->icon = $icon->export_for_template($output);
                   }
                   $columns[] = $th;
               }
            } else {
                $th = new stdClass();
                $th->label = $item->get_label();
                if ($item->has_icon()) {
                    $icon = $item->get_icon();
                    $th->icon = $icon->export_for_template($output);
                }
                $columns[] = $th;
            }
        }
        return $columns;
    }
}