<?php

/**
 * @category Application
 * @package Application_Migration
 * @author
 * @license New BSD
 * @version $Id$
 */
class Migration_1366743752 extends Core_Migration_Abstract
{

    /**
     * @author
     * @return bool
     */
    public function up()
    {
        $this->query("
CREATE TABLE `client` (
  `client_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(10) unsigned NOT NULL,	
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `middle_name` varchar(45) NOT NULL,
  `address` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `facility` varchar(45) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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

