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
 * Online users tests
 *
 * @package    block_online_users
 * @category   test
 * @copyright  2015 University of Nottingham <www.nottingham.ac.uk>
 * @author     Barry Oosthuizen <barry.oosthuizen@nottingham.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use block_online_users\online_users as online_users;

/**
 * Online users testcase
 *
 * @package    block_online_users
 * @category   test
 * @copyright  2015 University of Nottingham <www.nottingham.ac.uk>
 * @author     Barry Oosthuizen <barry.oosthuizen@nottingham.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_online_users_online_users_testcase extends advanced_testcase {

    /**
     * Test \block_online_users\onlineusers works as expected
     */
    public function test_online_users() {
        $this->resetAfterTest(true);

        $timetoshowusers = 300; // Seconds.
        // Generate logged in users.
        $generator = $this->getDataGenerator()->get_plugin_generator('block_online_users');
        $data = $generator->create_logged_in_users();

        $now = time();
        // Check logged in group 1 & members in course 1 (should be 3).
        $this->check_course1_group_members($data, $now, $timetoshowusers);
        // Check logged in users in course 1 (should be 9).
        $this->check_course1($data, $now, $timetoshowusers);
        // Check logged in at the site level (should be 12).
        $this->check_sitelevel($now, $timetoshowusers);
    }

    /**
     * Check logged in group 1 & 2 members in course 1 (should be 3).
     * @param array $data Array of user, course and group objects
     * @param int $now Current Unix timestamp
     * @param int $timetoshowusers The time window (in seconds) to check for the latest logged in users
     */
    private function check_course1_group_members($data, $now, $timetoshowusers) {
        $context = context_course::instance($data['course1']->id);
        $onlineusers = new online_users($data['group1']->id, $now, $timetoshowusers, $context, false, $data['course1']->id);
        $usercount = $onlineusers->count_users();
        $users = $onlineusers->get_users();
        $this->assertEquals($usercount, count($users), 'There was a problem counting the number of online users in group 1');
        $this->assertEquals($usercount, 3, 'There was a problem counting the number of online users in group 1');

        $onlineusers = new online_users($data['group2']->id, $now, $timetoshowusers, $context, false, $data['course1']->id);
        $usercount = $onlineusers->count_users();
        $users = $onlineusers->get_users();
        $this->assertEquals($usercount, count($users), 'There was a problem counting the number of online users in group 2');
        $this->assertEquals($usercount, 3, 'There was a problem counting the number of online users in group 2');
    }

    /**
     * Check logged in users in course 1 (should be 9).
     * @param array $data Array of user, course and group objects
     * @param int $now Current Unix timestamp
     * @param int $timetoshowusers The time window (in seconds) to check for the latest logged in users
     */
    private function check_course1($data, $now, $timetoshowusers) {
        $context = context_course::instance($data['course1']->id);
        $currentgroup = null;
        $onlineusers = new online_users($currentgroup, $now, $timetoshowusers, $context, false, $data['course1']->id);
        $usercount = $onlineusers->count_users();
        $users = $onlineusers->get_users();
        $this->assertEquals($usercount, count($users), 'There was a problem counting the number of online users in course 1');
        $this->assertEquals($usercount, 9, 'There was a problem counting the number of online users in course 1');
    }

    /**
     * Check logged in at the site level (should be 12).
     * @param int $now Current Unix timestamp
     * @param int $timetoshowusers The time window (in seconds) to check for the latest logged in users
     */
    private function check_sitelevel($now, $timetoshowusers) {
        $context = context_system::instance();
        $currentgroup = null;
        $onlineusers = new online_users($currentgroup, $now, $timetoshowusers, $context, true);
        $usercount = $onlineusers->count_users();
        $users = $onlineusers->get_users();
        $this->assertEquals($usercount, count($users), 'There was a problem counting the number of online users at site level');
        $this->assertEquals($usercount, 12, 'There was a problem counting the number of online users at site level');
    }
}
