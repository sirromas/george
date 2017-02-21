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
 * Parent theme: Bootstrapbase by Bas Brands
 * Built on: Essential by Julian Ridden
 *
 * @package   theme_lambda
 * @copyright 2016 redPIthemes
 *
 */
$footerl = 'footer-left';
$footerm = 'footer-middle';
$footerr = 'footer-right';

$hasfootnote = (empty($PAGE->theme->settings->footnote)) ? false : $PAGE->theme->settings->footnote;
$hasfooterleft = (empty($PAGE->layout_options['noblocks']) && $PAGE->blocks->region_has_content('footer-left', $OUTPUT));
$hasfootermiddle = (empty($PAGE->layout_options['noblocks']) && $PAGE->blocks->region_has_content('footer-middle', $OUTPUT));
$hasfooterright = (empty($PAGE->layout_options['noblocks']) && $PAGE->blocks->region_has_content('footer-right', $OUTPUT));
?>
<div class="row-fluid">
    <!--
    <span class="span6" style="text-align: right;"><a style="color:#bdc3c7;font-weight: bold;cursor: pointer;" href="http://practiceindex.co.uk/gp/terms-and-conditions/">Terms & Conditions</a></span>
    <span class="span6" style="text-align: left;"><a style="color:#bdc3c7;font-weight: bold;cursor: pointer;" href="http://practiceindex.co.uk/gp/privacy-policy/">Privacy Policy</a></span>
    -->

    <p style="text-align:center;color: #bdc3c7;">
        <a style="color:#bdc3c7;font-weight: bold;cursor: pointer;" href="http://practiceindex.co.uk/gp/terms-and-conditions/">Terms & Conditions</a>
        &nbsp;&nbsp;<a style="color:#bdc3c7;font-weight: bold;cursor: pointer;" href="http://practiceindex.co.uk/gp/privacy-policy/">Privacy Policy</a>
    </p>

</div>

<div class="footerlinks">
    <div class="row-fluid">
        <p style="text-align:center;color: #bdc3c7;">&copy; Copyright 2017 - Practice Index Ltd. All rights reserved.</p>
    </div>

    <?php if ($PAGE->theme->settings->socials_position == 0) { ?>
        <?php require_once(dirname(__FILE__) . '/socials.php'); ?>
    <?php }
    ?>

</div>