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

namespace report_lp\local;

defined('MOODLE_INTERNAL') || die();

use coding_exception;
use core_plugin_manager;
use moodle_url;
use pix_icon;
use plugin_renderer_base;
use report_lp\local\persistents\item_configuration;
use ReflectionClass;
use stdClass;
use report_lp\local\contracts\item_visitor;
use report_lp\output\cell;

/**
 * The base class that classes must extend.
 *
 * @package     report_lp
 * @copyright   2019 Troy Williams <troy.williams@learningworks.co.nz>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class item {

    /** @var null SHORT_NAME Can be used to override unique short name. */
    public const SHORT_NAME = null;

    /** @var string COMPONENT_TYPE Used to identify core or plugin type. */
    public const COMPONENT_TYPE = null;

    /** @var string COMPONENT_NAME Used to for name of core subsystem or plugin. */
    public const COMPONENT_NAME = null;

    /** @var stdClass $course The associated course object. */
    private $course;

    /** @var item|null The parent item of this item. If no item will return null. */
    public $parent;

    /** @var item_configuration $configuration The associated persistent class. */
    private $configuration;

    /** @var plugin_renderer_base $renderer Cache renderer class. */
    private $renderer;

    /** @var int $id */
    protected $id;

    /** @var int $courseid */
    protected $courseid;

    /** @var int $usecustomlabel */
    protected $usecustomlabel;

    /** @var string $customlabel */
    protected $customlabel;

    /** @var int $parentitemid */
    protected $parentitemid;

    /** @var int $depth */
    protected $depth;

    /** @var string $path Forward slashed list is hierarchy of record id's. */
    protected $path;

    /** @var int $sortorder */
    protected $sortorder;

    /** @var int $islocked */
    protected $islocked;

    /** @var int $visibletosummary */
    protected $visibletosummary;

    /** @var int $visibletoinstance */
    protected $visibletoinstance;

    /** @var int $visibletolearner */
    protected $visibletolearner;

    /** @var string $extraconfigurationdata JSON structure defined by each child class. */
    protected $extraconfigurationdata;

    /**
     * item constructor.
     *
     * @param stdClass|null $course
     */
    public function __construct(stdClass $course = null) {
        $this->course = $course;
    }

    /**
     * Allow visitor to process this and node and any child nodes.
     *
     * @param item_visitor $visitor
     * @return mixed
     */
    public function accept(item_visitor $visitor) {
        return $visitor->visit($this);
    }

    final public function get_id() {
        return $this->id;
    }

    final public function get_courseid() {
        return $this->courseid;
    }

    final public function get_usecustomlabel() {
        return $this->usecustomlabel;
    }

    final public function get_customlabel() {
        return $this->customlabel;
    }

    final public function get_parentitemid() {
        return $this->parentitemid;
    }

    final public function get_depth() {
        return $this->depth;
    }

    final public function get_path() {
        return $this->path;

    }

    final public function get_sortorder() {
        return $this->sortorder;
    }

    final public function get_extraconfigurationdata() {
        return $this->extraconfigurationdata;
    }

    /**
     * Human readable name for item. For example, Grouping, Last course access.
     *
     * @return string
     */
    abstract public function get_name() : string;

    /**
     * Use reflection class to get extending classes shortname.
     *
     * @return string
     * @throws \ReflectionException
     * @throws coding_exception
     */
    public static function get_short_name() : string {
        $item = new static();
        if (is_null(static::SHORT_NAME)) {
            $reflection = new ReflectionClass($item);
             return $reflection->getShortName();
        }
        return static::SHORT_NAME;
    }

    /**
     * The default text for heading or column heading for this item.
     *
     * @return string
     */
    abstract public function get_default_label() : string;

    /**
     * Get human friendly description of what this item does.
     *
     * @return string
     */
    abstract public function get_description() : string;

    /**
     * Child class to overide if supports feature. Only call if has_icon().
     *
     * @return pix_icon|null
     */
    public function get_icon() : ? pix_icon {
        return null;
    }

    /**
     * Primary method for building a cell object for
     *
     * @param int|null $depth
     * @return mixed
     */
    public function build_header_cell(int $depth = null) {
        $cell = new cell();
        $renderer = $this->get_renderer();
        $text = $this->get_label();
        $cell->header = true;
        $cell->text = $text;
        $contents = new stdClass();
        $contents->text = $text;
        $contents->title = $text;
        if ($this->has_url()) {
            $link = new stdClass();
            $link->text = $text;
            $link->alt = $text;
            $link->src = $this->get_url()->out();
            $contents->link = $link;
        }
        if ($this->has_icon()) {
            $contents->icon =  $this->get_icon()->export_for_template($renderer);
        }
        $cell->content = $renderer->render_from_template(
            'report_lp/cell_contents', $contents);
        return $cell;
    }

    /**
     * Return renderer class, cache if required.
     *
     * @return \renderer_base
     */
    protected function get_renderer() {
        global $PAGE;
        if (is_null($this->renderer)) {
            $this->renderer = $PAGE->get_renderer('report_lp');
        }
        return $this->renderer;
    }

    /**
     * Gets custom label if set else fall back to default label. To be called
     * by reports not report configuration.
     *
     * @return string
     * @throws coding_exception
     */
    final public function get_label() : string {
         if (is_null($this->id)) {
            throw new coding_exception("Configuration not loaded");
         }
         if ($this->usecustomlabel) {
             return format_text($this->customlabel, FORMAT_PLAIN);
         }
         return $this->get_default_label();
     }

    /**
     * Child class to overide if supports feature. Only call if has_url().
     *
     * @return moodle_url|null
     */
    public function get_url() : ? moodle_url {
        return null;
    }

    /**
     * Return the associated configuration persistent for item.
     *
     * @return item_configuration
     */
    final public function get_configuration() : item_configuration {
        return $this->configuration;
    }

    /**
     * Get parent item.
     *
     * @return item|null
     */
    final public function get_parent() : ? item {
        return $this->parent;
    }

    /**
     * Set parent item, must match if id has been set.
     *
     * @param item|null $parent
     * @throws coding_exception
     */
    final protected function set_parent(item $parent = null) {
        if ($this->get_id()) {
            if ($parent->get_id() != $this->get_parentitemid()) {
                throw new coding_exception("Failed adoption");
            }
        }
        $this->parent = $parent;
    }

    /**
     * Get associated course object.
     *
     * @return stdClass
     * @throws \dml_exception
     */
    final public function get_course() : stdClass {
        if (!isset($this->course)) {
            if ($this->courseid) {
                $this->course = get_course($this->courseid);
            }
        }
        return $this->course;
    }

    /**
     * Relative class name.
     *
     * @return string
     */
    final public static function get_class_name() : string {
        return get_class(new static());
    }

    /**
     * Child class to overide if supports feature.
     *
     * @return bool
     */
    public function has_icon() : bool {
        return false;
    }

    /**
     * Child class to overide if supports feature.
     *
     * @return bool
     */
    public function has_url() : bool {
        return false;
    }

    /**
     * Is a child.
     *
     * @return bool
     */
    public function is_child() {
        return ($this->get_parentitemid() != 0);
    }

    /**
     * If item using component check it is available and enabled. If not enable
     * by default.
     *
     * Override this method to allow another level of control possibly disabling via a $CFG
     * variable.
     *
     * @return bool
     */
    public static function is_enabled() {
        $type = static::COMPONENT_TYPE;
        $name = static::COMPONENT_NAME;
        // Does not make use of a component so enable by default.
        if (is_null($type) || is_null($name)) {
            return true;
        }
        $pluginmanager = core_plugin_manager::instance();
        $present = $pluginmanager->get_present_plugins($type);
        if (!is_array($present)) {
            return false;
        }
        if (!isset($present[$name])) {
            return false;
        }
        $enabled = $pluginmanager->get_enabled_plugins($type);
        if (!is_array($enabled)) {
            return false;
        }
        return isset($enabled[$name]);
    }

    /**
     * @return bool
     */
    public function is_locked() {
        return ($this->islocked) ? true : false;
    }

    /**
     * Is the root of the tree. Top dog.
     *
     * @return bool
     */
    public function is_root() {
        return ($this->get_parentitemid() == 0);
    }

    public function is_visible_in_summary() {
        return ($this->visibletosummary) ? true : false;
    }

    public function is_visible_in_instance() {
        return ($this->visibletoinstance) ? true : false;
    }

    public function is_visible_to_learner() {
        return ($this->visibletolearner) ? true : false;
    }

    /**
     * Load properties from configuration persistent.
     *
     * @param item_configuration $configuration
     * @throws coding_exception
     */
    public function load_configuration(item_configuration $configuration) {
        unset($this->course);
        $properties = $configuration::properties_definition();
        foreach ($properties as $property => $propertyinformation) {
            $this->{$property} = $configuration->get($property);
        }
        $this->configuration = $configuration;
    }

}
