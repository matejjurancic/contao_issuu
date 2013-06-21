-- ********************************************************
-- *                                                      *
-- * IMPORTANT NOTE                                       *
-- *                                                      *
-- * Do not import this file manually but use the Contao  *
-- * install tool to create and maintain database tables! *
-- *                                                      *
-- ********************************************************

-- 
-- Table `tl_issuu_category`
-- 

CREATE TABLE `tl_issuu_category` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `headline` varchar(255) NOT NULL default '',
  `alias` varbinary(128) NOT NULL default '',
  `description` text NULL
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Table `tl_issuu`
-- 

CREATE TABLE `tl_issuu` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `headline` varchar(255) NOT NULL default '',
  `alias` varbinary(128) NOT NULL default '',
  `description` text NULL,
  `keywords` varchar(255) NOT NULL default '',
  `category` char(6) NOT NULL default '000000',
  `doctype` char(6) NOT NULL default '000000',
  `language` char(2) NOT NULL default '',
  `file` varchar(255) NOT NULL default '',
  `documentId` varchar(128) NOT NULL default '',
  `access` varchar(8) NOT NULL default '',
  `sorting` int(10) unsigned NOT NULL default '0'
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_module`
-- 

CREATE TABLE `tl_module` (
  `issuu_categories` blob NULL,
  `issuu_numberOfItems` tinyint(3) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_user`
-- 

CREATE TABLE `tl_user` (
  `issuu` blob NULL,
  `issup` blob NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_user_group`
-- 

CREATE TABLE `tl_user_group` (
  `issuu` blob NULL,
  `issup` blob NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
