<?php
defined('BASEPATH') or exit('No direct script access allowed');

// -----------------------------------------------------------------------------
// Perfex Install File for Follow Up
// Author: DevOracle
// Auto-generated on Tue Jun 24 11:20:53 EAT 2025
// -----------------------------------------------------------------------------

add_option('follow_up_default_status', 'pending');

$CI = &get_instance();


if (!$CI->db->table_exists(db_prefix() . 'debt_collection')) {
    $sql = "CREATE TABLE IF NOT EXISTS `" . db_prefix() . "debt_collection" . "` (
        `id` INT(11) AUTO_INCREMENT ,
        `branch_name` VARCHAR(100) ,
        `admission_no` VARCHAR(50) ,
        `student_name` VARCHAR(100) ,
        `registration_date` VARCHAR(250) ,
        `fee` DECIMAL(10,2) ,
        `fee_paid` DECIMAL(10,2) ,
        `fee_balance` DECIMAL(10,2) ,
        `id_no` VARCHAR(50) ,
        `phone_no` VARCHAR(20) ,
        `course` VARCHAR(100) ,
        `status` VARCHAR(50) ,
        `feedback` TEXT ,
        `user_id` VARCHAR(250) ,
        `branch_id` VARCHAR(250) ,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $CI->db->query($sql);
}

if (!$CI->db->table_exists(db_prefix() . 'inquiries')) {
    $sql = "CREATE TABLE IF NOT EXISTS `" . db_prefix() . "inquiries" . "` (
        `id` INT(11) AUTO_INCREMENT ,
        `client_name` VARCHAR(250) ,
        `contact` VARCHAR(250) ,
        `course` VARCHAR(250) ,
        `feedback` TEXT ,
        `user_id` VARCHAR(250) ,
        `branch_id` VARCHAR(250) ,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $CI->db->query($sql);
}
