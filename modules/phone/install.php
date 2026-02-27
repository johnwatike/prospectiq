<?php

defined('BASEPATH') or exit('No direct script access allowed');

add_option('survey_send_emails_per_cron_run', 100);
add_option('last_survey_send_cron', '');

// Phone module settings
add_option('phone_api_key', 'f7871b1bfb087eecb264b9ecf1ec7e6a95eff105642442b7e353999f18dc9c1d');
add_option('phone_username', 'ptnscrm');
add_option('phone_number', '+254711082705');

if (!$CI->db->table_exists(db_prefix() . 'phone_surveyresultsets')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'phone_surveyresultsets` (
  `resultsetid` int(11) NOT NULL,
  `surveyid` int(11) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `useragent` varchar(150) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'phone_surveyresultsets`
  ADD PRIMARY KEY (`resultsetid`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'phone_surveyresultsets`
  MODIFY `resultsetid` int(11) NOT NULL AUTO_INCREMENT;');
}

if (!$CI->db->table_exists(db_prefix() . 'phone_surveysemailsendcron')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'phone_surveysemailsendcron` (
  `id` int(11) NOT NULL,
  `surveyid` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `emailid` int(11) DEFAULT NULL,
  `listid` varchar(11) DEFAULT NULL,
  `log_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'phone_surveysemailsendcron`
  ADD PRIMARY KEY (`id`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'phone_surveysemailsendcron`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;');
}

if (!$CI->db->table_exists(db_prefix() . 'surveys')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "surveys` (
  `surveyid` int(11) NOT NULL,
  `subject` mediumtext NOT NULL,
  `slug` mediumtext NOT NULL,
  `description` text NOT NULL,
  `viewdescription` text,
  `datecreated` datetime NOT NULL,
  `redirect_url` varchar(100) DEFAULT NULL,
  `send` tinyint(1) NOT NULL DEFAULT '0',
  `onlyforloggedin` int(11) DEFAULT '0',
  `fromname` varchar(100) DEFAULT NULL,
  `iprestrict` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `hash` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'phone_surveys`
  ADD PRIMARY KEY (`surveyid`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'phone_surveys`
  MODIFY `surveyid` int(11) NOT NULL AUTO_INCREMENT;');
}

if (!$CI->db->table_exists(db_prefix() . 'phone_surveysendlog')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "phone_surveysendlog` (
  `id` int(11) NOT NULL,
  `surveyid` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `iscronfinished` int(11) NOT NULL DEFAULT '0',
  `send_to_mail_lists` text
) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'phone_surveysendlog`
  ADD PRIMARY KEY (`id`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'phone_surveysendlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;');
}

if (!$CI->db->table_exists(db_prefix() . 'phone_maillistscustomfields')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'phone_maillistscustomfields` (
  `customfieldid` int(11) NOT NULL,
  `listid` int(11) NOT NULL,
  `fieldname` varchar(150) NOT NULL,
  `fieldslug` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'phone_maillistscustomfields`
  ADD PRIMARY KEY (`customfieldid`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'phone_maillistscustomfields`
  MODIFY `customfieldid` int(11) NOT NULL AUTO_INCREMENT;');
}

if (!$CI->db->table_exists(db_prefix() . 'phone_maillistscustomfieldvalues')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'phone_maillistscustomfieldvalues` (
  `customfieldvalueid` int(11) NOT NULL,
  `listid` int(11) NOT NULL,
  `customfieldid` int(11) NOT NULL,
  `emailid` int(11) NOT NULL,
  `value` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'phone_maillistscustomfieldvalues`
  ADD PRIMARY KEY (`customfieldvalueid`),
  ADD KEY `listid` (`listid`),
  ADD KEY `customfieldid` (`customfieldid`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'phone_maillistscustomfieldvalues`
  MODIFY `customfieldvalueid` int(11) NOT NULL AUTO_INCREMENT;');
}

if (!$CI->db->table_exists(db_prefix() . 'phone_listemails')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'phone_listemails` (
  `emailid` int(11) NOT NULL,
  `listid` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `dateadded` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'phone_listemails`
  ADD PRIMARY KEY (`emailid`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'phone_listemails`
  MODIFY `emailid` int(11) NOT NULL AUTO_INCREMENT;');
}

if (!$CI->db->table_exists(db_prefix() . 'emaillists')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'phone_emaillists` (
  `listid` int(11) NOT NULL,
  `name` mediumtext NOT NULL,
  `creator` varchar(100) NOT NULL,
  `datecreated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'phone_emaillists`
  ADD PRIMARY KEY (`listid`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'phone_emaillists`
  MODIFY `listid` int(11) NOT NULL AUTO_INCREMENT;');
}
