<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$url=base_url();
$pieces = parse_url($url);
$domain = isset($pieces['host']) ? $pieces['host'] : $pieces['path'];
if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
	$host=$regs['domain'];
}else{
	$host=$_SERVER['SERVER_NAME'];
}


$config['debug'] = FALSE;

// Server Information
$config['protocol'] = 'https';
$config['host'] = $host; // email domain (usually same as cPanel domain above)
$config['port'] = config_item('saas_cpanel_port');  // cpanel secure authentication port unsecure port# 2082

// Output type
$config['output'] = 'json';

// Auth type (hash/pass)
$config['auth_type'] = 'hash';

// Username
$config['user'] = config_item('saas_cpanel_username'); // cPanel username

// Password or hash string
$config['auth'] = decrypt(config_item('saas_cpanel_password')); // cPanel password

// curl or fopen
$config['http_client'] = 'curl';

/* End of file */