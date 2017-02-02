<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Utils.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/admin/classes/Pages.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/admin/classes/News.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/certificates/classes/Certificate.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Completion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Dashboard.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Courses.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Profile.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Groups.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Users.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Reports.php';

/**
 * Description of Navigation
 *
 * @author moyo
 */
class Navigation extends Utils {

    public $classes_path;

    function __construct() {
        parent::__construct();
        $this->classes_path = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom';
    }

    function get_user_dashboard() {
        $userid = $this->user->id;
        if ($userid == 2) {
            $ds = $this->get_admin_dashboard();
        } // end if $userid == 2
        else {

            $roleid = $this->get_user_role($userid);
            switch ($roleid) {
                case 5:
                    $ds = $this->get_student_dashboard();
                    break;
                case 9:
                    $ds = $this->get_ccg_dashboard();
                    break;
                case 10:
                    $ds = $this->get_gp_dashboard();
                    break;
            }
        } // end else
        return $ds;
    }

    function get_admin_dashboard() {
        $list = "";
        $sesskey = $this->user->sesskey;

        $p = new Pages();
        $pages = $p->get_user_site_pages();

        $ds = new Dashboard();
        $status = $ds->get_user_dashboard($this->user->id);

        $pr = new Profile();
        $profile = $pr->get_user_profile($this->user->id);

        $c = new Courses();
        $courses = $c->get_courses_page($this->user->id);
        $ext = $c->get_personal_external_training_courses($this->user->id);
        $repeat = $c->get_repeat_training_page($this->user->id);
        $policy = $c->get_policy_page($this->user->id);

        $g = new Groups();
        $groups = $g->get_groups_page($this->user->id);

        $u = new Users();
        $users = $u->get_users_page($this->user->id);

        $r = new Reports();
        $report = $r->get_reports_page($this->user->id);

        $list.="<ul class = 'nav nav-tabs' >
          <li class = 'active'><a data-toggle = 'tab' href = '#dash'><i style='padding-left:18px;cursor:pointer;' class='fa fa-tachometer fa-2x' aria-hidden='true'></i><br>Dashboard</li></a>
          <li><a data-toggle = 'tab'  href = '#profile'><i style='padding-left:13px;cursor:pointer;' class='fa fa-id-card-o fa-2x'aria-hidden='true' ></i><br>My profile</span></a></li>
          <li><a data-toggle = 'tab'  href = '#courses'><i style='padding-left:10px;cursor:pointer;' class='fa fa-desktop fa-2x' aria-hidden='true'></i><br>Courses</a></li>
          <li><a data-toggle = 'tab'  href = '#external'><i style='padding-left:32px;cursor:pointer;' class='fa fa-comments fa-2x' aria-hidden='true'></i><br>External Training</a></li>
          <li><a data-toggle = 'tab'  href = '#repeat'><i style='padding-left:32px;cursor:pointer;' class='fa fa-history fa-2x' aria-hidden='true'></i><br>Repeat Training</a></li>
          <li><a data-toggle = 'tab'  href = '#policy'><i style='padding-left:10px;cursor:pointer;' class='fa fa-file-powerpoint-o fa-2x' aria-hidden='true'></i><br>Policies</a></li>
          <li><a data-toggle = 'tab'  href = '#users'><i style='padding-left:10px;cursor:pointer;' class='fa fa-user-circle-o fa-2x' aria-hidden='true'></i><br><span style='padding-left:7px;'>Users</span></a></li>
          <li><a data-toggle = 'tab'  href = '#groups'><i title='Groups' style='padding-left:13px;cursor:pointer;' class='fa fa-users fa-2x' aria-hidden='true'></i><br><span style='padding-left:7px;'>Groups</span></a></li>
          <li><a data-toggle = 'tab'  href = '#reports'><i title='Reports' style='padding-left:15px;cursor:pointer;' class='fa fa-bar-chart fa-2x' aria-hidden='true'></i><br><span style='padding-left:7px;'>Reports</span></a></li>
          <li><a data-toggle = 'tab'  href = '#pages'><i style='padding-left:7px;' style='padding-left:0px;cursor:pointer;' class='fa fa-pencil-square-o fa-2x' aria-hidden='true'></i><br>Pages</a></li>
          <li><a href = 'http://" . $_SERVER['SERVER_NAME'] . "/lms/login/logout.php?seskey=$sesskey'><i title='Logout' style='padding-left:8px;cursor:pointer;' class='fa fa-location-arrow fa-2x' aria-hidden='true'></i><br><span style='padding-left:1px;'>Logout</span></a></li>
        </ul>";

        $list.="<div class = 'tab-content' style='padding-left:10px;padding-right:10px;padding-top:0px;'>
          <div id = 'dash' class = 'tab-pane fade in active'>
            $status
          </div>
          <div id = 'profile' class = 'tab-pane fade'>
            $profile
          </div>
          <div id = 'courses' class = 'tab-pane fade'>
            $courses
          </div>
          <div id = 'external' class = 'tab-pane fade'>
            $ext
          </div>
          <div id = 'repeat' class = 'tab-pane fade'>
            $repeat
          </div>
          <div id = 'policy' class = 'tab-pane fade'>
            $policy
          </div>
          <div id = 'users' class = 'tab-pane fade'>
            $users
          </div>
          <div id = 'groups' class = 'tab-pane fade'>
            $groups
          </div> 
          <div id = 'reports' class = 'tab-pane fade'>
            $report
          </div>
          <div id = 'pages' class = 'tab-pane fade'>
            $pages
          </div>
          <div id = 'help' class = 'tab-pane fade'>
            <h3>Help</h3>
            <p>Some content.</p>
          </div>  
        
        </div>";

        return $list;
    }

    function get_ccg_dashboard() {
        $list = "";
        $sesskey = $this->user->sesskey;

        $ds = new Dashboard();
        $status = $ds->get_user_dashboard($this->user->id);

        $pr = new Profile();
        $profile = $pr->get_user_profile($this->user->id);

        $c = new Courses();
        $courses = $c->get_courses_page($this->user->id);
        $ext = $c->get_personal_external_training_courses($this->user->id);

        $list.="<ul class = 'nav nav-tabs' >
          <li class = 'active'><a data-toggle = 'tab' href = '#dash'><i style='padding-left:13px;cursor:pointer;' class='fa fa-tachometer fa-2x' aria-hidden='true'></i><br>Dashboard</a></li>
          <li><a data-toggle = 'tab' href = '#profile'><i style='padding-left:9px;cursor:pointer;' class='fa fa-id-card-o fa-2x' aria-hidden='true'></i><br>My profile</a></li>
          <li><a data-toggle = 'tab' href = '#courses'><i style='padding-left:6px;cursor:pointer;' class='fa fa-desktop fa-2x' aria-hidden='true'></i><br>Courses</a></li>
          <li><a data-toggle = 'tab' href = '#external'><i style='padding-left:32px;cursor:pointer;' class='fa fa-comments fa-2x' aria-hidden='true'></i><br>External Training</a></li>
          <li><a data-toggle = 'tab' href = '#repeat'><i style='padding-left:32px;cursor:pointer;' class='fa fa-history fa-2x' aria-hidden='true'></i><br>Repeat Training</a></li>
          <li><a data-toggle = 'tab' href = '#policy'><i style='padding-left:5px;cursor:pointer;' class='fa fa-file-powerpoint-o fa-2x' aria-hidden='true'></i><br>Policies</a></li>
          <li><a data-toggle = 'tab' href = '#users'><i style='padding-left:5px;cursor:pointer;' class='fa fa-user-circle-o fa-2x' aria-hidden='true'></i><br><span style='padding-left:7px;'>Users</span></a></li>
          <li><a data-toggle = 'tab' href = '#reports'><i title='Reports' style='padding-left:7px;cursor:pointer;' class='fa fa-bar-chart fa-2x' aria-hidden='true'></i><br><span style='padding-left:7px;'>Reports</span></a></li>
          <li><a data-toggle = 'tab' href = '#help'><i title='Help' style='padding-left:5px;cursor:pointer;' class='fa fa-question fa-2x' aria-hidden='true'></i><br><span style='padding-left:4px;'>Help</span></a></li>
          <li><a href = 'http://" . $_SERVER['SERVER_NAME'] . "/lms/login/logout.php?seskey=$sesskey'><i title='Logout' style='padding-left:8px;cursor:pointer;' class='fa fa-location-arrow fa-2x' aria-hidden='true'></i><br><span style='padding-left:4px;'>Logout</span></a></li>
        </ul>";

        $list.="<div class = 'tab-content'>
          <div id = 'dash' class = 'tab-pane fade in active'>
            <p>$status</p>
          </div>
          <div id = 'profile' class = 'tab-pane fade'>
            <p>$profile</p>
          </div>
          <div id = 'courses' class = 'tab-pane fade'>
            <p>$courses</p>
          </div>
          <div id = 'external' class = 'tab-pane fade'>
            <p>$ext</p>
          </div>
          <div id = 'repeat' class = 'tab-pane fade'>
            <h3>Repeat Training</h3>
            <p>Some content.</p>
          </div>
          <div id = 'policy' class = 'tab-pane fade'>
            <h3>Policies</h3>
            <p>Some content.</p>
          </div>
          <div id = 'users' class = 'tab-pane fade'>
            <h3>Users</h3>
            <p>Some content.</p>
          </div>
          <div id = 'groups' class = 'tab-pane fade'>
            <h3>Groups</h3>
            <p>Some content.</p>
          </div> 
          <div id = 'reports' class = 'tab-pane fade'>
            <h3>Reports</h3>
            <p>Some content.</p>
          </div>
          <div id = 'help' class = 'tab-pane fade'>
            <h3>Help</h3>
            <p>Some content.</p>
          </div>  
        
        </div>";

        return $list;
    }

    function get_gp_dashboard() {
        $list = "";
        $sesskey = $this->user->sesskey;

        $ds = new Dashboard();
        $status = $ds->get_user_dashboard($this->user->id);

        $pr = new Profile();
        $profile = $pr->get_user_profile($this->user->id);

        $c = new Courses();
        $courses = $c->get_courses_page($this->user->id);
        $ext = $c->get_personal_external_training_courses($this->user->id);
        $repeat = $c->get_repeat_training_page($this->user->id);
        $policy = $c->get_policy_page($this->user->id);

        $u = new Users();
        $users = $u->get_users_page($this->user->id);

        $r = new Reports();
        $report = $r->get_reports_page($this->user->id);

        $list.="<ul class = 'nav nav-tabs' >
         <li class = 'active'><a data-toggle = 'tab' href = '#dash'><i style='padding-left:18px;cursor:pointer;' class='fa fa-tachometer fa-2x' aria-hidden='true'></i><br>Dashboard</a></li>
          <li><a data-toggle = 'tab' href = '#profile'><i style='padding-left:12px;cursor:pointer;' class='fa fa-id-card-o fa-2x' aria-hidden='true'></i><br>My profile</a></li>
          <li><a data-toggle = 'tab' href = '#courses'><i style='padding-left:8px;cursor:pointer;' class='fa fa-desktop fa-2x' aria-hidden='true'></i><br>Courses</a></li>
          <li><a data-toggle = 'tab' href = '#external'><i style='padding-left:32px;cursor:pointer;' class='fa fa-comments fa-2x' aria-hidden='true'></i><br>External Training</a></li>
          <li><a data-toggle = 'tab' href = '#repeat'><i style='padding-left:32px;cursor:pointer;' class='fa fa-history fa-2x' aria-hidden='true'></i><br>Repeat Training</a></li>
          <li><a data-toggle = 'tab' href = '#policy'><i style='padding-left:10px;cursor:pointer;' class='fa fa-file-powerpoint-o fa-2x' aria-hidden='true'></i><br>Policies</a></li>
          <li><a data-toggle = 'tab' href = '#users'><i style='padding-left:10px;cursor:pointer;' class='fa fa-user-circle-o fa-2x' aria-hidden='true'></i><br><span style='padding-left:7px;'>Users</span></a></li>
          <li><a data-toggle = 'tab' href = '#reports'><i title='Reports' style='padding-left:15px;cursor:pointer;' class='fa fa-bar-chart fa-2x' aria-hidden='true'></i><br><span style='padding-left:7px;'>Reports</span></a></li>
          <li><a data-toggle = 'tab' href = '#help'><i title='Help' style='padding-left:5px;cursor:pointer;' class='fa fa-question fa-2x' aria-hidden='true'></i><br><span style='padding-left:4px;'>Help</span></a></li>
          <li><a href = 'http://" . $_SERVER['SERVER_NAME'] . "/lms/login/logout.php?seskey=$sesskey'><i title='Logout' style='padding-left:8px;cursor:pointer;' class='fa fa-location-arrow fa-2x' aria-hidden='true'></i><br><span style='padding-left:4px;'>Logout</span></a></li>
        </ul>";

        $list.="<div class ='tab-content'>
          <div id = 'dash' class = 'tab-pane fade in active'>
            $status
          </div>
          <div id = 'profile' class = 'tab-pane fade'>
            $profile
          </div>
          <div id = 'courses' class = 'tab-pane fade'>
            $courses
          </div>
          <div id = 'external' class = 'tab-pane fade'>
            $ext
          </div>
          <div id = 'repeat' class = 'tab-pane fade'>
            $repeat
          </div>
          <div id = 'policy' class = 'tab-pane fade'>
            $policy
          </div>
          <div id = 'users' class = 'tab-pane fade'>
            $users
          </div>
          
          <div id = 'reports' class = 'tab-pane fade'>
            $report
          </div>
          <div id = 'help' class = 'tab-pane fade'>
            <h3>Help</h3>
            <p>Some content.</p>
          </div>  
        
          </div>";

        return $list;
    }

    function get_student_dashboard() {
        $list = "";
        $sesskey = $this->user->sesskey;

        $ds = new Dashboard();
        $status = $ds->get_user_dashboard($this->user->id);

        $pr = new Profile();
        $profile = $pr->get_user_profile($this->user->id);

        $c = new Courses();
        $courses = $c->get_courses_page($this->user->id);
        $ext = $c->get_personal_external_training_courses($this->user->id);
        $repeat = $c->get_repeat_training_page($this->user->id);


        $list.="<ul class = 'nav nav-tabs' >
          <li class = 'active'><a data-toggle = 'tab' href = '#dash'><i style='padding-left:20px;cursor:pointer;' class='fa fa-tachometer fa-2x' aria-hidden='true'></i><br>Dashboard</a></li>
          <li><a data-toggle = 'tab' href = '#profile'><i style='padding-left:14px;cursor:pointer;' class='fa fa-id-card-o fa-2x' aria-hidden='true'></i><br>My profile</a></li>
          <li><a data-toggle = 'tab' href = '#courses'><i style='padding-left:10px;cursor:pointer;' class='fa fa-desktop fa-2x' aria-hidden='true'></i><br>Courses</a></li>
          <li><a data-toggle = 'tab' href = '#external'><i style='padding-left:32px;cursor:pointer;' class='fa fa-comments fa-2x' aria-hidden='true'></i><br>External Training</a></li>
          <li><a data-toggle = 'tab' href = '#repeat'><i style='padding-left:32px;cursor:pointer;' class='fa fa-history fa-2x' aria-hidden='true'></i><br>Repeat Training</a></li>
          <li><a data-toggle = 'tab' href = '#help'><i title='Help' style='padding-left:10px;cursor:pointer;' class='fa fa-question fa-2x' aria-hidden='true'></i><br><span style='padding-left:4px;'>Help</span></a></li>
          <li><a href = 'http://" . $_SERVER['SERVER_NAME'] . "/lms/login/logout.php?seskey=$sesskey'><i title='Logout' style='padding-left:12px;cursor:pointer;' class='fa fa-location-arrow fa-2x' aria-hidden='true'></i><br><span style='padding-left:4px;'>Logout</span></a></li>
        </ul>";

        $list.="<div class = 'tab-content'>
          <div id = 'dash' class = 'tab-pane fade in active'>
            <p>$status</p>
          </div>
          <div id = 'profile' class = 'tab-pane fade'>
            <p>$profile</p>
          </div>
          <div id = 'courses' class = 'tab-pane fade'>
            <p>$courses</p>
          </div>
          <div id = 'external' class = 'tab-pane fade'>
            <p>$ext</p>
          </div>
          <div id = 'repeat' class = 'tab-pane fade'>
            $repeat
          </div>
          <div id = 'help' class = 'tab-pane fade'>
            <h3>Help</h3>
            <p>Some content.</p>
          </div>  
        
        </div>";

        return $list;
    }

}
?>

