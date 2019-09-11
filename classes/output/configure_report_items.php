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

use renderable;
use renderer_base;
use report_lp\local\builders\item_tree;
use report_lp\local\grouping;
use report_lp\local\item_type_list;
use report_lp\local\persistents\item_configuration;
use report_lp\local\visitors\pre_order_visitor;
use stdClass;
use templatable;

/**
 * Output class for generating data for configure items template.
 *
 * @package     report_lp
 * @copyright   2019 Troy Williams <troy.williams@learningworks.co.nz>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class configure_report_items implements renderable, templatable {

    /** @var stdClass $course A course object. */
    private $course;

    /** @var item_type_list $itemtypelist Registered list of items. */
    private $itemtypelist;

    public function __construct(stdClass $course) {
        $this->course = $course;
        $this->itemtypelist = new item_type_list();
    }

    public function export_for_template(renderer_base $output) {
        $data = new stdClass();
        $data->courseid = $this->course->id;
        $data->coursestartdate = $this->course->startdate;
        $data->root = null;
        $data->children = [];
        $tree = new item_tree($this->course, $this->itemtypelist);
        $itemconfigurations = item_configuration::get_records(['courseid' => $this->course->id]);
        $root = $tree->build_from_item_configurations($itemconfigurations);
        if ($root === null) {
            $data->initialisebutton = button::create_initialise_button($this->course->id);
        } else {
            $rootlineitem               = new stdClass();
            $rootlineitem->id           = $root->get_id();
            $rootlineitem->label        = $root->get_label();
            $rootlineitem->shortname    = $root::get_short_name();
            $data->root                 = $rootlineitem;
            $data->initialised          = true;
            if ($root->has_children()) {
                foreach ($root->get_children() as $child) {
                    $childlineitem        = new stdClass();
                    $childlineitem->id    = $child->get_id();
                    $childlineitem->label = $child->get_label();
                    if ($child instanceof grouping) {
                        $childlineitem->grandchildren = [];
                        if ($child->has_children()) {
                            foreach ($child->get_children() as $grandchild) {
                                $grandchildlineitem        = new stdClass();
                                $grandchildlineitem->id    = $grandchild->get_id();
                                $grandchildlineitem->label = $grandchild->get_label();
                                $childlineitem->grandchildren[] = $grandchildlineitem;
                            }
                        }
                    }
                    $data->children[] = $childlineitem;
                }
            }
            /*$visitor = new pre_order_visitor();
            $items = $root->accept($visitor);
            foreach ($items as $item) {
                if ($item->is_root()) {
                    $lineitem               = new stdClass();
                    $lineitem->id           = $item->get_id();
                    $lineitem->label        = $item->get_label();
                    $lineitem->shortname    = $item::get_short_name();
                    $data->rootitem         = $lineitem;
                    $data->initialised      = true;
                } else {
                    $lineitem               = new stdClass();
                    $lineitem->id           = $item->get_id();
                    $lineitem->parentid     = $item->get_parent()->get_id();
                    $lineitem->label        = $item->get_label();
                    $lineitem->shortname    = $item::get_short_name();
                    if ($item instanceof grouping) {
                        $lineitem->grouping = true;
                    }
                    $data->lineitems[]      = $lineitem;
                }
            }*/
        }
        return $data;
    }

}
