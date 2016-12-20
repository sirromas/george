<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/Utils.php';

/**
 * Description of Navigation
 *
 * @author moyo
 */
class Navigation extends Utils {

    function __construct() {
        parent::__construct();
    }

    function get_user_dashboard() {
        $userid = $this->user->id;
        if ($userid == 2) {
            $ds = $this->get_admin_dashboard();
        } // end if $userid == 2
        else {

            $roleid = $this->get_user_role($userid);
            //echo "Role id: ".$roleid."<br>";
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

        $list.="<ul class = 'nav nav-tabs' >
          <li class = 'active'><a data-toggle = 'tab' href = '#dash'><i style='padding-left:7px;cursor:pointer;' class='fa fa-tachometer fa-4x' aria-hidden='true'></i><br>Dashboard</li>
          <li><a data-toggle = 'tab' href = '#profile'><i style='padding-left:2px;cursor:pointer;' class='fa fa-id-card-o fa-4x' aria-hidden='true'></i><br>My profile</a></li>
          <li><a data-toggle = 'tab' href = '#courses'><i style='padding-left:0px;cursor:pointer;' class='fa fa-desktop fa-4x' aria-hidden='true'></i><br>Courses</a></li>
          <li><a data-toggle = 'tab' href = '#external'><i style='padding-left:25px;cursor:pointer;' class='fa fa-comments fa-4x' aria-hidden='true'></i><br>External Training</a></li>
          <li><a data-toggle = 'tab' href = '#repeat'><i style='padding-left:25px;cursor:pointer;' class='fa fa-history fa-4x' aria-hidden='true'></i><br>Repeat Training</a></li>
          <li><a data-toggle = 'tab' href = '#policy'><i style='padding-left:0px;cursor:pointer;' class='fa fa-file-powerpoint-o fa-4x' aria-hidden='true'></i><br>Policies</a></li>
          <li><a data-toggle = 'tab' href = '#users'><i style='padding-left:0px;cursor:pointer;' class='fa fa-user-circle-o fa-4x' aria-hidden='true'></i><br><span style='padding-left:7px;'>Users</span></a></li>
          <li><a data-toggle = 'tab' href = '#groups'><i title='Groups' style='padding-left:0px;cursor:pointer;' class='fa fa-users fa-4x' aria-hidden='true'></i><br><span style='padding-left:7px;'>Groups</span></a></li>
          <li><a data-toggle = 'tab' href = '#reports'><i title='Reports' style='padding-left:0px;cursor:pointer;' class='fa fa-bar-chart fa-4x' aria-hidden='true'></i><br><span style='padding-left:7px;'>Reports</span></a></li>
          <li><a data-toggle = 'tab' href = '#pages'><i style='padding-left:7px;' style='padding-left:0px;cursor:pointer;' class='fa fa-pencil-square-o fa-4x' aria-hidden='true'></i><br>Site pages</a></li>
          <li><a data-toggle = 'tab' href = '#help'><i title='Help' style='padding-left:0px;cursor:pointer;' class='fa fa-question fa-4x' aria-hidden='true'></i><br><span style='padding-left:4px;'>Help</span></a></li>
        </ul>";

        $list.="<div class = 'tab-content'>
          <div id = 'dash' class = 'tab-pane fade in active'>
            <h3>Dashboard</h3>
            <p>Some content.</p>
          </div>
          <div id = 'profile' class = 'tab-pane fade'>
            <h3>My Profile</h3>
            <p>Some content.</p>
          </div>
          <div id = 'courses' class = 'tab-pane fade'>
            <h3>Courses</h3>
            <p>Some content.</p>
          </div>
          <div id = 'external' class = 'tab-pane fade'>
            <h3>External Training</h3>
            <p>Some content.</p>
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
          <div id = 'pages' class = 'tab-pane fade'>
            <h3>Site Pages</h3>
            <p>Some content.</p>
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

        $list.="<ul class = 'nav nav-tabs' >
          <li class = 'active'><a data-toggle = 'tab' href = '#dash'><i style='padding-left:7px;cursor:pointer;' class='fa fa-tachometer fa-4x' aria-hidden='true'></i><br>Dashboard</li>
          <li><a data-toggle = 'tab' href = '#profile'><i style='padding-left:2px;cursor:pointer;' class='fa fa-id-card-o fa-4x' aria-hidden='true'></i><br>My profile</a></li>
          <li><a data-toggle = 'tab' href = '#courses'><i style='padding-left:0px;cursor:pointer;' class='fa fa-desktop fa-4x' aria-hidden='true'></i><br>Courses</a></li>
          <li><a data-toggle = 'tab' href = '#external'><i style='padding-left:25px;cursor:pointer;' class='fa fa-comments fa-4x' aria-hidden='true'></i><br>External Training</a></li>
          <li><a data-toggle = 'tab' href = '#repeat'><i style='padding-left:25px;cursor:pointer;' class='fa fa-history fa-4x' aria-hidden='true'></i><br>Repeat Training</a></li>
          <li><a data-toggle = 'tab' href = '#policy'><i style='padding-left:0px;cursor:pointer;' class='fa fa-file-powerpoint-o fa-4x' aria-hidden='true'></i><br>Policies</a></li>
          <li><a data-toggle = 'tab' href = '#users'><i style='padding-left:0px;cursor:pointer;' class='fa fa-user-circle-o fa-4x' aria-hidden='true'></i><br><span style='padding-left:7px;'>Users</span></a></li>
          <li><a data-toggle = 'tab' href = '#groups'><i title='Groups' style='padding-left:0px;cursor:pointer;' class='fa fa-users fa-4x' aria-hidden='true'></i><br><span style='padding-left:7px;'>Groups</span></a></li>
          <li><a data-toggle = 'tab' href = '#reports'><i title='Reports' style='padding-left:0px;cursor:pointer;' class='fa fa-bar-chart fa-4x' aria-hidden='true'></i><br><span style='padding-left:7px;'>Reports</span></a></li>
          <li><a data-toggle = 'tab' href = '#help'><i title='Help' style='padding-left:0px;cursor:pointer;' class='fa fa-question fa-4x' aria-hidden='true'></i><br><span style='padding-left:4px;'>Help</span></a></li>
        </ul>";

        $list.="<div class = 'tab-content'>
          <div id = 'dash' class = 'tab-pane fade in active'>
            <h3>Dashboard</h3>
            <p>Some content.</p>
          </div>
          <div id = 'profile' class = 'tab-pane fade'>
            <h3>My Profile</h3>
            <p>Some content.</p>
          </div>
          <div id = 'courses' class = 'tab-pane fade'>
            <h3>Courses</h3>
            <p>Some content.</p>
          </div>
          <div id = 'external' class = 'tab-pane fade'>
            <h3>External Training</h3>
            <p>Some content.</p>
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

        $list.="<ul class = 'nav nav-tabs' >
          <li class = 'active'><a data-toggle = 'tab' href = '#dash'><i style='padding-left:7px;cursor:pointer;' class='fa fa-tachometer fa-4x' aria-hidden='true'></i><br>Dashboard</li>
          <li><a data-toggle = 'tab' href = '#profile'><i style='padding-left:2px;cursor:pointer;' class='fa fa-id-card-o fa-4x' aria-hidden='true'></i><br>My profile</a></li>
          <li><a data-toggle = 'tab' href = '#courses'><i style='padding-left:0px;cursor:pointer;' class='fa fa-desktop fa-4x' aria-hidden='true'></i><br>Courses</a></li>
          <li><a data-toggle = 'tab' href = '#external'><i style='padding-left:25px;cursor:pointer;' class='fa fa-comments fa-4x' aria-hidden='true'></i><br>External Training</a></li>
          <li><a data-toggle = 'tab' href = '#repeat'><i style='padding-left:25px;cursor:pointer;' class='fa fa-history fa-4x' aria-hidden='true'></i><br>Repeat Training</a></li>
          <li><a data-toggle = 'tab' href = '#policy'><i style='padding-left:0px;cursor:pointer;' class='fa fa-file-powerpoint-o fa-4x' aria-hidden='true'></i><br>Policies</a></li>
          <li><a data-toggle = 'tab' href = '#users'><i style='padding-left:0px;cursor:pointer;' class='fa fa-user-circle-o fa-4x' aria-hidden='true'></i><br><span style='padding-left:7px;'>Users</span></a></li>
          <li><a data-toggle = 'tab' href = '#groups'><i title='Groups' style='padding-left:0px;cursor:pointer;' class='fa fa-users fa-4x' aria-hidden='true'></i><br><span style='padding-left:7px;'>Groups</span></a></li>
          <li><a data-toggle = 'tab' href = '#reports'><i title='Reports' style='padding-left:0px;cursor:pointer;' class='fa fa-bar-chart fa-4x' aria-hidden='true'></i><br><span style='padding-left:7px;'>Reports</span></a></li>
          <li><a data-toggle = 'tab' href = '#help'><i title='Help' style='padding-left:0px;cursor:pointer;' class='fa fa-question fa-4x' aria-hidden='true'></i><br><span style='padding-left:4px;'>Help</span></a></li>
        </ul>";

        $list.="<div class = 'tab-content'>
          <div id = 'dash' class = 'tab-pane fade in active'>
            <h3>Dashboard</h3>
            <p>Some content.</p>
          </div>
          <div id = 'profile' class = 'tab-pane fade'>
            <h3>My Profile</h3>
            <p>Some content.</p>
          </div>
          <div id = 'courses' class = 'tab-pane fade'>
            <h3>Courses</h3>
            <p>Some content.</p>
          </div>
          <div id = 'external' class = 'tab-pane fade'>
            <h3>External Training</h3>
            <p>Some content.</p>
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

    function get_student_dashboard() {
        $list = "";

        $list.="<ul class = 'nav nav-tabs' >
          <li class = 'active'><a data-toggle = 'tab' href = '#dash'><i style='padding-left:7px;cursor:pointer;' class='fa fa-tachometer fa-4x' aria-hidden='true'></i><br>Dashboard</li>
          <li><a data-toggle = 'tab' href = '#profile'><i style='padding-left:2px;cursor:pointer;' class='fa fa-id-card-o fa-4x' aria-hidden='true'></i><br>My profile</a></li>
          <li><a data-toggle = 'tab' href = '#courses'><i style='padding-left:0px;cursor:pointer;' class='fa fa-desktop fa-4x' aria-hidden='true'></i><br>Courses</a></li>
          <li><a data-toggle = 'tab' href = '#external'><i style='padding-left:25px;cursor:pointer;' class='fa fa-comments fa-4x' aria-hidden='true'></i><br>External Training</a></li>
          <li><a data-toggle = 'tab' href = '#repeat'><i style='padding-left:25px;cursor:pointer;' class='fa fa-history fa-4x' aria-hidden='true'></i><br>Repeat Training</a></li>
          <li><a data-toggle = 'tab' href = '#help'><i title='Help' style='padding-left:0px;cursor:pointer;' class='fa fa-question fa-4x' aria-hidden='true'></i><br><span style='padding-left:4px;'>Help</span></a></li>
        </ul>";

        $list.="<div class = 'tab-content'>
          <div id = 'dash' class = 'tab-pane fade in active'>
            <h3>Dashboard</h3>
            <p>Some content.</p>
          </div>
          <div id = 'profile' class = 'tab-pane fade'>
            <h3>My Profile</h3>
            <p>Some content.</p>
          </div>
          <div id = 'courses' class = 'tab-pane fade'>
            <h3>Courses</h3>
            <p>Some content.</p>
          </div>
          <div id = 'external' class = 'tab-pane fade'>
            <h3>External Training</h3>
            <p>Some content.</p>
          </div>
          <div id = 'repeat' class = 'tab-pane fade'>
            <h3>Repeat Training</h3>
            <p>Some content.</p>
          </div>
          <div id = 'help' class = 'tab-pane fade'>
            <h3>Help</h3>
            <p>Some content.</p>
          </div>  
        
        </div>";

        return $list;
    }

}
