##
#
##

CREATE TABLE `api_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `controller` varchar(64) NOT NULL,
  `action` varchar(128) DEFAULT NULL,
  `data` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;