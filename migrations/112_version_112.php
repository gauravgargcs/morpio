<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_112 extends CI_Migration
{
    function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
       $this->db->query("ALTER TABLE `tbl_request_quote` CHANGE `city` `subject` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;");
        $this->db->query("UPDATE `tbl_config` SET `value` = '1.1.2' WHERE `tbl_config`.`config_key` = 'version';");
    }
}
