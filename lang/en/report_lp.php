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
 * Definition of language strings.
 *
 * @package   {{PLUGIN_NAME}} {@link https://docs.moodle.org/dev/Frankenstyle}
 * @copyright 2015 LearningWorks Ltd {@link http://www.learningworks.co.nz}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Learner progress report';
$string['report/learnerprogress:view'] = 'Can view report';
$string['selectgroup'] = 'Select group...';
$string['selectcategory'] = 'Select category...';
$string['coursegroup'] = 'Course group';
$string['coursecategory'] = 'Course category';
$string['configureprogresstracking'] = 'Configure learner progress tracking';
$string['configurelearnerprogressreport'] = 'Configure learner progress report';
$string['viewlearnerprogresssummary'] = 'View learner progress summary';

$string['trackassignment'] = 'Track assignment';
$string['notenrolled'] = 'Not enrolled';
$string['exportfilename'] = 'learnerprogress';
$string['configure'] = 'Configure';
$string['datalastfetched'] = 'Data last fetched at: {$a}';
$string['fetchprogessdata'] = 'Fetch progress data';
$string['lp:configure'] = 'Build report add/remove groupings and measures';
$string['lp:viewsummary'] = 'View the summary Learner progress report';
$string['lp:viewindividual'] = 'View individual Learner progress report';
$string['lp:exportsummary'] = 'Export summary to file';

$string['assignmentresubmitcount:measure:name'] = 'Assignment resubmits';
$string['assignmentresubmitcount:measure:description'] = 'The number of resubmitted assignment attempts.';

$string['assignmentstatus:measure:name'] = 'Assignment status';
$string['assignmentstatus:measure:description'] = 'Uses grade display type for submitted assignments for example "Achieved" or "Not achieved". Indicates not submitted type states such as "Draft", "No submission", and "Reopened".';

$string['attendancename'] = 'Attendance name';
$string['attendancesessionssummary:measure:name'] = 'Attendance';
$string['attendancesessionssummary:measure:description'] = 'Show how many sessions a learner has attended out of total number of sessions. For example 3/4 sessions attended.';
$string['coursegrade:measure:label'] = 'Course grade (or final grade)';
$string['coursegrade:measure:name'] = 'Course grade';
$string['coursegrade:measure:description'] = 'The course grade calculated or based on aggregated grade items from the gradebook.';


$string['checklistcompletion:measure:name'] = 'Checklist';
$string['checklistcompletion:measure:description'] = 'Indicate if checklist is incompleted or completed.';

$string['gradecategoryactivitycompletion:measure:name'] = 'Grade category activity completion';
$string['gradecategoryactivitycompletion:measure:description'] = 'The percentage of activities completed by a learner that reside in a grade book category.';

$string['lastcourseaccess:measure:label'] = 'Last course access';
$string['lastcourseaccess:measure:name'] = 'Last course access';
$string['lastcourseaccess:measure:description'] = 'The date and time the learner last accessed this course.';

$string['grouping:measure:name'] = 'Grouping';
$string['grouping:measure:description'] = 'Grouping allows a set of measures to be grouped together for display purposes.';

$string['grouping'] = 'Grouping';
$string['measures'] = 'Measures';
$string['configurereportfor'] = 'Configure learner progress report for {$a}';
$string['summaryreportfor'] = 'Learner progress summary report for {$a}';

$string['usecustomlabel'] = 'Use custom label';
$string['customlabel'] = 'Custom label';
$string['defaultlabel'] = 'Default label';
$string['visibletosummary'] = 'Display in the summary report';
$string['visibletoinstance'] = 'Display in the learner instance report';
$string['visibletolearner'] = 'Display to learner';
$string['configure'] = 'Configure {$a}';

$string['defaultlabelgrouping'] = 'Grouping {$a}';
$string['defaultlabelassignmentstatus'] = 'Assignment name';
$string['defaultlabelassignmentstatusconfigured'] = 'Status: {$a}';
$string['defaultlabelassignmentresubmitcount'] = 'Assignment name';
$string['defaultlabelassignmentresubmitcountconfigured'] = 'Resubmit total: {$a}';
$string['defaultlabelattendancesessionssummary'] = 'Sessions attended summary';
$string['checklistname'] = 'Checklist name';
$string['defaultlabelgradecategoryconfigured'] = 'Grade category completion: {$a}';

$string['parentgrouping'] = 'Parent grouping';
$string['generalsettings'] = 'General settings';
$string['specificsettings'] = 'Specific settings';

$string['pleasechoose'] = 'Please choose an item from the list';

$string['dotn'] = '...n';
$string['noavailablemodules'] = 'No available {$a} modules. Either none in course or all are in use be this measure.';
$string['nogradecategories'] = 'No available grade categories. Either none in course or all are in use be this measure.';
$string['noitemsconfigured'] = 'No items configured';
$string['noitemsconfigured:description'] = 'Use the grouping and measures controls to start configuring the learner progress report';

$string['moveup'] = 'Move item up';
$string['movedown'] = 'Move item down';
$string['deleteitem'] = 'Delete item';
$string['configureitem'] = 'Configure item';
$string['deletegroupingitem'] = '
<h5>Delete grouping {$a}</h5>
<p>Deleting a grouping will also delete any measures associated to the grouping</p>';
$string['deletemeasureitem'] = '
<h5>Delete measure {$a}</h5>';
$string['noreportconfiguration'] = 'Report configuration does not exist';
$string['instantiatenewreportquestion'] = 'Would you like to instantiate a new report configuration for {$a}?';
$string['instantiate'] = 'Instantiate...';

$string['addgrouping'] = 'Add grouping item to the report';
$string['addmeasure'] ='Add measure of item type {$a} to the report';

$string['none'] = 'None';
$string['filtergroups'] = 'Filter groups';
$string['aria:filterdropdown'] = 'Filtering dropdown';
$string['aria:none'] = 'aria:none';
$string['aria:open'] = 'aria:open';
$string['aria:closed'] = 'aria:closed';
$string['aria:controls'] = 'aria:controls';

$string['learner'] = 'Learner';
$string['idnumber'] = 'ID';
$string['coursegroups'] = 'Groups';

$string['noselectedcoursegroups'] = 'No course groups selected';
$string['noselectedcoursegroups:description'] = 'Please use the course group filter dropdown to select groups of learners to filter date on';

$string['cachedef_summarydata'] = 'The summary measure data for learners in a course';

