<?php
function get_user_id_for_user_token($user_token){
    $CI = & get_instance();
  	$sql = "SELECT user_id FROM tbl_users WHERE user_token = '".$user_token."'";
  	$result = $CI->db->query($sql);
	$row = $result->row_array();
        if(isset($row["user_id"]) && !empty($row["user_id"])) {
              return $row["user_id"];
        }
	return 0;
}
function getUserData($userid) {
    $CI = & get_instance();
  	$sql = "SELECT * FROM tbl_users WHERE user_id = '".$userid."'";
  	$result = $CI->db->query($sql);
	$row = $result->row();
        if(isset($row->user_id) && !empty($row->user_id)){
              return $row;
        }
	return 0;
}
function getUserAccountData($userid){
    $CI = & get_instance();
  	$sql = "SELECT * FROM tbl_account_details WHERE user_id = '".$userid."'";
  	$result = $CI->db->query($sql);
	$row = $result->row();
    if(isset($row->user_id) && !empty($row->user_id)){
	  return $row;
    }
	return 0;
}
function get_user_id_for_email($email) {
	$CI = & get_instance();
  	$sql = "SELECT user_id FROM tbl_users WHERE email = '".$email."'";
  	$result = $CI->db->query($sql);
	$row = $result->row_array();
    if(isset($row["user_id"]) && !empty($row["user_id"])){
      return $row["user_id"];
    }
	return 0;
}

function update_user($usrData, $user_id) {
    $CI = & get_instance();
    $count = 0;
    $fields = '';

    foreach($usrData as $col => $val) {
    if ($count++ != 0) $fields .= ', ';
        $fields .= "$col = '$val'";
    }
    $sql = "UPDATE tbl_users SET ".$fields." WHERE user_id = ".$user_id;
    $CI->db->query($sql);
}

function add_user($usrData) {
	$CI = & get_instance();
   	$count = 0;
   	$fields = '';

   	foreach($usrData as $col => $val) {
      	if ($count++ != 0) $fields .= ', ';
      	$fields .= "`$col` = '$val'";
   	}

   	$sql = "INSERT INTO `tbl_users` SET $fields";
   	if ($CI->db->query($sql) === TRUE) {
	  	echo "New record created successfully";
	}
	return $CI->db->insert_id();
}

function create_sso_session($identity_token){
	//Your Site Settings
    $site_subdomain = OPENALL_SUBDOMAIN;
    $site_public_key = OPENALL_PUBKEY;
    $site_private_key = OPENALL_PRIVKEY;

    //API Access domain
    $site_domain = $site_subdomain . '.api.oneall.com';
	$sso_uri = 'https://' . $site_domain . '/sso/sessions/identities/'.$identity_token.'.json';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $sso_uri);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_USERPWD, $site_public_key . ":" . $site_private_key);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_POSTFIELDS,null);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response  = curl_exec($ch);

    $response = json_decode($response,true);
    $data = $response['response']['result']['data']['sso_session'];
    curl_close($ch);

    return $data['sso_session_token'];
}

function read_sso_session($identity_token){
    //Your Site Settings
    $site_subdomain = OPENALL_SUBDOMAIN;
    $site_public_key = OPENALL_PUBKEY;
    $site_private_key = OPENALL_PRIVKEY;

    //API Access domain
    $site_domain = $site_subdomain . '.api.oneall.com';
    $sso_uri = 'https://' . $site_domain . '/sso/sessions/identities/'.$identity_token.'.json';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $sso_uri);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_USERPWD, $site_public_key . ":" . $site_private_key);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response  = curl_exec($ch);

    $response = json_decode($response,true);
    $data = $response['response']['result']['data']['sso_session'];
    curl_close($ch);

    return $data['sso_session_token'];
}

function delete_identity_sso_session($identity_token){
	//Your Site Settings
    $site_subdomain = OPENALL_SUBDOMAIN;
    $site_public_key = OPENALL_PUBKEY;
    $site_private_key = OPENALL_PRIVKEY;

    //API Access domain
    $site_domain = $site_subdomain . '.api.oneall.com';
	$url = 'https://' . $site_domain . '/sso/sessions/identites/'.$identity_token.'.json?confirm_deletion=true';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERPWD, $site_public_key . ":" . $site_private_key);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $result;
}

function delete_sso_session($sso_token){
	//Your Site Settings
    $site_subdomain = OPENALL_SUBDOMAIN;
    $site_public_key = OPENALL_PUBKEY;
    $site_private_key = OPENALL_PRIVKEY;

    //API Access domain
    $site_domain = $site_subdomain . '.api.oneall.com';
	$url = 'https://' . $site_domain . '/sso/sessions/'.$sso_token.'.json?confirm_deletion=true';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERPWD, $site_public_key . ":" . $site_private_key);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
}

function synchronize_user($data_json){
	//Your Site Settings
    $site_subdomain = OPENALL_SUBDOMAIN;
    $site_public_key = OPENALL_PUBKEY;
    $site_private_key = OPENALL_PRIVKEY;

    //API Access domain
    $site_domain = $site_subdomain . '.api.oneall.com';
	$url = 'https://' . $site_domain . '/storage/users/user/synchronize.json';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_json)));
    curl_setopt($ch, CURLOPT_USERPWD, $site_public_key . ":" . $site_private_key);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    $response = json_decode($result,true);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
}

function check_user($data_json, $user_id){
    //Your Site Settings
    $site_subdomain = OPENALL_SUBDOMAIN;
    $site_public_key = OPENALL_PUBKEY;
    $site_private_key = OPENALL_PRIVKEY;

    //API Access domain
    $site_domain = $site_subdomain . '.api.oneall.com';
    $url = 'https://' . $site_domain . '/storage/users/user/lookup.json';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_json)));
    curl_setopt($ch, CURLOPT_USERPWD, $site_public_key . ":" . $site_private_key);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    $response = json_decode($result,true);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if (isset($response['response']['result'])) {
        $update = array(
            'user_token' => $response['response']['result']['data']['user']['user_token'],
            'identity_token' => $response['response']['result']['data']['user']['identity']['identity_token']
        );
        update_user($update, $user_id);
        return $update;
    } else {
        return false;
    }
    
}

function create_user($data_json, $user_id){
    //Your Site Settings
    $site_subdomain = OPENALL_SUBDOMAIN;
    $site_public_key = OPENALL_PUBKEY;
    $site_private_key = OPENALL_PRIVKEY;

    //API Access domain
    $site_domain = $site_subdomain . '.api.oneall.com';
    $url = 'https://' . $site_domain . '/storage/users.json';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_json)));
    curl_setopt($ch, CURLOPT_USERPWD, $site_public_key . ":" . $site_private_key);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    $response = json_decode($result,true);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $update = array(
        'user_token' => $response['response']['result']['data']['user']['user_token'],
        'identity_token' => $response['response']['result']['data']['user']['identity']['identity_token']
    );
    update_user($update, $user_id);
    return $update;
}

function update_oneall_user($data_json, $user_token){
    //Your Site Settings
    $site_subdomain = OPENALL_SUBDOMAIN;
    $site_public_key = OPENALL_PUBKEY;
    $site_private_key = OPENALL_PRIVKEY;

    //API Access domain
    $site_domain = $site_subdomain . '.api.oneall.com';
    $url = 'https://' . $site_domain . '/storage/users/'.$user_token.'.json';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_json)));
    curl_setopt($ch, CURLOPT_USERPWD, $site_public_key . ":" . $site_private_key);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    $response = json_decode($result,true);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    // $update = array(
    //     'user_token' => $response['response']['result']['data']['user']['user_token'],
    //     'identity_token' => $response['response']['result']['data']['user']['identity']['identity_token']
    // );
    // update_user($update, $user_id);
    // return $update;
}
?>