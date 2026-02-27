<?php
defined('BASEPATH') or exit('No direct script access allowed');

// -----------------------------------------------------------------------------
// Perfex Install File for Debt Collection
// Author: DevOracle
// Auto-generated on Tue Jun 24 08:59:12 EAT 2025
// -----------------------------------------------------------------------------

add_option('debt_collection_default_status', 'pending');

$CI = &get_instance();


if (!$CI->db->table_exists(db_prefix() . 'follow_up_')) {
    $sql = "CREATE TABLE IF NOT EXISTS `" . db_prefix() . "follow_up_" . "` (
        `id` INT(11) AUTO_INCREMENT ,
        `branch_name` VARCHAR(100) ,
        `admission_no` VARCHAR(50) ,
        `student_name` VARCHAR(100) ,
        `registration_date` DATE ,
        `fee` DECIMAL(10,2) ,
        `fee_paid` DECIMAL(10,2) ,
        `fee_balance` DECIMAL(10,2) ,
        `id_no` VARCHAR(50) ,
        `phone_no` VARCHAR(20) ,
        `course` VARCHAR(100) ,
        `status` VARCHAR(50) ,
        `feedback` TEXT ,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $CI->db->query($sql);
}

if (!$CI->db->table_exists(db_prefix() . 'follow_up_note_')) {
    $sql = "CREATE TABLE IF NOT EXISTS `" . db_prefix() . "follow_up_note_" . "` (
        `id` INT(11) AUTO_INCREMENT ,
        `follow_up_id` INT(11) ,
        `note` TEXT ,
        `created_by` INT(11) ,
        `created_at` DATETIME ,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $CI->db->query($sql);
}
