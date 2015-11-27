<?php
// This file is NOT a part of Moodle - http://moodle.org/
//
// This client for Moodle 2 is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//

/**
 * REST client for Moodle 2
 * Return JSON or XML format
 *
 * @authorr Jerome Mouneyrac
 */

/// SETUP - NEED TO BE CHANGED
$token = 'acabec9d20933913f14301785324f579';
$domainname = 'http://www.yourmoodle.com';
$functionname = 'core_user_update_users';

// REST RETURNED VALUES FORMAT
$restformat = 'json'; //Also possible in Moodle 2.2 and later: 'json'
                     //Setting it to 'json' will fail all calls on earlier Moodle version

//////// moodle_user_create_users ////////

/// PARAMETERS - NEED TO BE CHANGED IF YOU CALL A DIFFERENT FUNCTION
$user1 = new stdClass();
$user1->id = 2;

// Upload a user picture example.
/// UPLOAD PARAMETERS
//Note: check "Maximum uploaded file size" in your Moodle "Site Policies".
$imagepath = '../../sample-ws-clients/PHP-HTTP-filehandling/image_to_upload.jpg'; //CHANGE THIS !
$filepath = '/'; //put the file to the root of your private file area. //OPTIONAL

/// UPLOAD IMAGE - Moodle 2.1 and later
$params = array('file_box' => "@".$imagepath,'filepath' => $filepath, 'token' => $token, 'filearea' => 'draft');
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
curl_setopt($ch, CURLOPT_URL, $domainname . '/webservice/upload.php');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
$response = curl_exec($ch);
print_r($response);
$data = json_decode($response);

$user1->userpicture = $data[0]->itemid;
$users = array($user1);
$params = array('users' => $users);

/// REST CALL
header('Content-Type: text/plain');
$serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;
require_once('./curl.php');
$curl = new curl;
//if rest format == 'xml', then we do not add the param for backward compatibility with Moodle < 2.2
$restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';
$resp = $curl->post($serverurl . $restformat, $params);
print_r($resp);
