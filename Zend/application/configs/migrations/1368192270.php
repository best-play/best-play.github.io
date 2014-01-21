<?php

/**
 * @category Application
 * @package Application_Migration
 * @author
 * @license New BSD
 * @version $Id$
 */
class Migration_1368192270 extends Core_Migration_Abstract
{

    /**
     * @author
     * @return bool
     */
    public function up()
    {
        $this->query("

CREATE TABLE `meet` (
  `meet_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(10) unsigned NOT NULL,	
  `date` datetime NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`meet_id`)
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

