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
 * block_online_users data generator
 *
 * @package    block_online_users
 * @category   test
 * @copyright  2012 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


/**
 * Online users block data generator class
 *
 * @package    block_online_users
 * @category   test
 * @copyright  2012 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_online_users_generator extends testing_block_generator {

    /**
     * Create new block instance
     * @param array|stdClass $record
     * @param array $options
     * @return stdClass activity record with extra cmid field
     */
    public function create_instance($record = null, array $options = null) {
        global $DB, $CFG;
        require_once("$CFG->dirroot/mod/page/locallib.php");

        $this->instancecount++;

        $record = (object)(array)$record;
        $options = (array)$options;

        $record = $this->prepare_record($record);

        $id = $DB->insert_record('block_instances', $record);
        context_block::instance($id);

        $instance = $DB->get_record('block_instances', array('id'=>$id), '*', MUST_EXIST);

        return $instance;
    }

    /**
     * Create logged in users and add some of them to groups in a course
     */
    public function create_logged_in_users() {

        $generator = advanced_testcase::getDataGenerator();
        $course1 = $generator->create_course();
        // Create some logged in users who are logged into $course1.
        $user1 = $generator->create_logged_in_user(null, $course1->id);
        $user2 = $generator->create_logged_in_user(null, $course1->id);
        $user3 = $generator->create_logged_in_user(null, $course1->id);
        $user4 = $generator->create_logged_in_user(null, $course1->id);
        $user5 = $generator->create_logged_in_user(null, $course1->id);
        $user6 = $generator->create_logged_in_user(null, $course1->id);
        $user7 = $generator->create_logged_in_user(null, $course1->id);
        $user8 = $generator->create_logged_in_user(null, $course1->id);
        $user9 = $generator->create_logged_in_user(null, $course1->id);
        // Create some logged in users who are not logged into $course1.
        $user10 = $generator->create_logged_in_user();
        $user11 = $generator->create_logged_in_user();
        $user12 = $generator->create_logged_in_user();
        // Create group 1 in course 1.
        $group1 = $generator->create_group(array('courseid' => $course1->id));
        // Create group 2 in course 1.
        $group2 = $generator->create_group(array('courseid' => $course1->id));
        // Add some users to course group 1.
        $generator->create_group_member(array('groupid' => $group1->id, 'userid' => $user1->id));
        $generator->create_group_member(array('groupid' => $group1->id, 'userid' => $user2->id));
        $generator->create_group_member(array('groupid' => $group1->id, 'userid' => $user3->id));
        // Add some users to course group 2.
        $generator->create_group_member(array('groupid' => $group2->id, 'userid' => $user4->id));
        $generator->create_group_member(array('groupid' => $group2->id, 'userid' => $user5->id));
        $generator->create_group_member(array('groupid' => $group2->id, 'userid' => $user6->id));
        $data = array();
        $data['user1'] = $user1;
        $data['user2'] = $user2;
        $data['user3'] = $user3;
        $data['user4'] = $user4;
        $data['user5'] = $user5;
        $data['user6'] = $user6;
        $data['user7'] = $user7;
        $data['user8'] = $user8;
        $data['user9'] = $user9;
        $data['user10'] = $user10;
        $data['user11'] = $user11;
        $data['user12'] = $user12;
        $data['group1'] = $group1;
        $data['group2'] = $group2;
        $data['course1'] = $course1;

        return $data; // Return the user, course and group objects.
    }
}
