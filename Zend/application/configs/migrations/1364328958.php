<?php

/**
 * @category Application
 * @package Application_Migration
 * @author
 * @license New BSD
 * @version $Id$
 */
class Migration_1364328958 extends Core_Migration_Abstract
{

    /**
     * @author
     * @return bool
     */
    public function up()
    {
        $this->query("
CREATE TABLE `user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(85) NOT NULL,
  `password` char(40) NOT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `gender` enum('MALE','FEMALE') NOT NULL,
  `status` enum('ENABLED','DISABLED') NOT NULL DEFAULT 'ENABLED',
  `created` datetime NOT NULL,
  `role` enum('STAFF','ADMIN') NOT NULL DEFAULT 'STAFF',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `invite` (
  `invite_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `from` int(11) NOT NULL,
  `to` varchar(85) NOT NULL,
  `code` varchar(12) NOT NULL,
  `role` enum('STAFF','ADMIN') NOT NULL,
  `created` datetime NOT NULL,
  `status` enum('NEW','USED') NOT NULL DEFAULT 'NEW',
  PRIMARY KEY (`invite_id`),
  UNIQUE KEY `code_UNIQUE` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `restore_password` (
  `restore_password_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `whom` varchar(85) NOT NULL,
  `code` varchar(12) NOT NULL,
  `created` datetime NOT NULL,
  `status` enum('NEW','USED') NOT NULL DEFAULT 'NEW',
  PRIMARY KEY (`restore_password_id`),
  UNIQUE KEY `code_UNIQUE` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `user` (`user_id`, `email`, `password`, `phone`, `first_name`, `last_name`, `gender`, `status`, `created`, `role`) VALUES
(NULL, 'staff@example.com', '05fe7461c607c33229772d402505601016a7d0ea', NULL, 'Тестовый', 'Менеджер', 'MALE', 'ENABLED', '2012-07-13 00:00:00', 'STAFF'),
(NULL, 'admin@example.com', '05fe7461c607c33229772d402505601016a7d0ea', NULL, 'Тестовый', 'Администратор', 'MALE', 'ENABLED', '2012-07-13 00:00:00', 'ADMIN');
	");
    }

    /**
     * @author
     * @return bool
     */
    public function down()
    {
        $this->query("");
    }


}

