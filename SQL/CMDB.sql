--  Database = CMDB;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel application
--
CREATE TABLE IF NOT EXISTS application (
  App_ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  Name varchar(255) COLLATE utf8_bin DEFAULT NULL,
  Active INTEGER(1) NOT NULL DEFAULT '1',
  Deactivate_reason varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (App_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
insert into application (App_ID,Name) values
(1,'Active Directory'),
(2,'CMDB');
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel accounttype
--
CREATE TABLE IF NOT EXISTS accounttype (
  Type_ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  Type varchar(255) COLLATE utf8_bin DEFAULT NULL,
  Description varchar(255) COLLATE utf8_bin DEFAULT NULL,
  Active INTEGER(1) NOT NULL DEFAULT '1',
  Deactivate_reason varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (Type_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
-- ---INITIAL DATA
INSERT INTO accounttype (Type_ID,Type, Description) Values
(1,'Normal User','Normal User no aditional wrights'),
(2,'Administrator','Admnistrator Account'),
(3,'Servive User','User used to run services');
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel category
--
CREATE TABLE IF NOT EXISTS category (
  ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  Category varchar(255) COLLATE utf8_bin DEFAULT NULL,
  Prefix varchar(5) COLLATE utf8_bin DEFAULT NULL,
  Active INTEGER(1) NOT NULL DEFAULT '1',
  Deactivate_reason varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/* Initial Data */
INSERT INTO category (ID,Category,prefix) VALUES 
(1,'Kensington',NULL),
(2,'Mobile',NULL),
(3,'Mobile Subscription',NULL),
(4,'Internet Subscription',NULL),
(5,'Laptop','LPT'),
(6,'Desktop','DST'),
(7,'Token',NULL),
(8, 'Monitor','SCR'),
(9, 'Docking station','DOC');
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel identitytype
--
CREATE TABLE IF NOT EXISTS identitytype (
  Type_ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  Type varchar(255) COLLATE utf8_bin DEFAULT NULL,
  Description varchar(255) COLLATE utf8_bin DEFAULT NULL,
  Active INTEGER(1) NOT NULL DEFAULT '1',
  Deactivate_reason varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (Type_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
/* Initial Data */
insert into identitytype (Type_ID,Type,Description) values
(1,'Werknemer','Werknemer'),
(2,'Exeterne','Externe medewerker');
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel roletype
--
CREATE TABLE IF NOT EXISTS roletype (
  Type_ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  Type varchar(255) COLLATE utf8_bin DEFAULT NULL,
  Description varchar(255) COLLATE utf8_bin DEFAULT NULL,
  Active INTEGER(1) NOT NULL DEFAULT '1',
  Deactivate_reason varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (Type_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
/*+ initial Data */ 
insert into roletype (Type_ID,Type,Description) values
(1,'Application','The Application Role'),
(2,'System','The System Role');
-- -------------------------------------------------------------------------------
--
-- Tabelstructuur voor tabel ram
--
CREATE TABLE IF NOT EXISTS ram (
  ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  RAM bigint(20) DEFAULT NULL,
  Text varchar(255) DEFAULT NULL,
  PRIMARY KEY (ID)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/* Initial DATA*/
INSERT INTO ram (ID, RAM, Text) VALUES
(1, 128, '128 Kb'),
(2, 256, '256 Kb'),
(3, 512, '512 Kb'),
(4, 1024, '1 Gb'),
(5, 1536, '1,5 Gb'),
(6, 2048, '2 Gb'),
(7, 2560, '2,5 Gb'),
(8, 3072, '3 Gb'),
(9, 3584, '3,5 Gb'),
(10, 4096, '4 Gb');
-- ---------------------------------------------------------------------------------
--
-- Tabelstructuur voor tabel configuration
--
CREATE TABLE IF NOT EXISTS configuration (
	CODE varchar(12) COLLATE utf8_bin NOT NULL,
	SUB_CODE varchar(255) COLLATE utf8_bin NOT NULL,
	CFN_DATE DATETIME DEFAULT NULL,
	CFN_NUMBER INT(20) DEFAULT NULL,
	TEXT VARCHAR(255) COLLATE utf8_bin DEFAULT NULL,
	Description VARCHAR(255) COLLATE utf8_bin DEFAULT NULL,
	PRIMARY KEY (CODE,SUB_CODE)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/* Initial Data */
INSERT INTO configuration (CODE,SUB_CODE,TEXT,Description) values
('General','DateFormat','d-m-Y','This is the data format used in the application'),
('General','LogDateFormat','d-m-Y h:i:s','This is the data format used in the application');
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel Level
--
CREATE TABLE IF NOT EXISTS Level (
    Level INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	Description varchar(255) COLLATE utf8_bin DEFAULT NULL,
    PRIMARY KEY (Level)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1;
insert into Level (Level) Values
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9);
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel account
--
CREATE TABLE IF NOT EXISTS account (
  Acc_ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  UserID varchar(255) COLLATE utf8_bin DEFAULT NULL,
  Type INTEGER UNSIGNED NOT NULL,
  Application INTEGER UNSIGNED NOT NULL,
  Active INTEGER(1) NOT NULL DEFAULT '1',
  Deactivate_reason varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (Acc_ID),
  FOREIGN KEY(Type) REFERENCES accounttype(Type_ID),
  FOREIGN KEY(Application) REFERENCES Application(App_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
/* Initial Data */
INSERT INTO account (Acc_ID,UserID,Type,Application) VALUE
(1,'Root',2,2);
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel assettype
--
CREATE TABLE IF NOT EXISTS assettype (
  Type_ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  Vendor varchar(255) COLLATE utf8_bin DEFAULT NULL,
  Type varchar(255) COLLATE utf8_bin DEFAULT NULL,
  Category INTEGER UNSIGNED DEFAULT NULL,
  Active INTEGER(1) NOT NULL DEFAULT '1',
  Deactivate_reason varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (Type_ID),
  FOREIGN KEY(Category) REFERENCES category(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel identity
--
CREATE TABLE IF NOT EXISTS identity (
  Iden_ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  Name varchar(255) COLLATE utf8_bin DEFAULT NULL,
  E_Mail varchar(255) COLLATE utf8_bin DEFAULT NULL,
  UserID varchar(20) COLLATE utf8_bin DEFAULT NULL,
  Company varchar(255) COLLATE utf8_bin DEFAULT NULL,
  Language varchar(2) COLLATE utf8_bin DEFAULT NULL,
  Type INTEGER UNSIGNED DEFAULT NULL,
  Active INTEGER(1) NOT NULL DEFAULT '1',
  Deactivate_reason varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (Iden_ID),
  FOREIGN KEY (Type) REFERENCES identitytype(Type_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
ALTER TABLE identity ADD UNIQUE(UserID);
-- Initial Data
insert into identity (Name,Language,Type) Values('Stock','NL',1);
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel asset
--
CREATE TABLE IF NOT EXISTS asset (
  AssetTag varchar(20) COLLATE utf8_bin NOT NULL DEFAULT 'IND',
  SerialNumber varchar(255) COLLATE utf8_bin DEFAULT NULL,
  IP_Adress varchar(255) COLLATE utf8_bin DEFAULT NULL,
  Name varchar(255) COLLATE utf8_bin DEFAULT NULL,
  MAC varchar(255) COLLATE utf8_bin DEFAULT NULL,
  RAM varchar(255) COLLATE utf8_bin DEFAULT NULL,
  Type INTEGER UNSIGNED DEFAULT NULL,
  Category INTEGER UNSIGNED DEFAULT NULL,
  Identity INTEGER UNSIGNED DEFAULT 1,
  Active INTEGER(1) NOT NULL DEFAULT '1',
  Deactivate_reason varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (AssetTag),
  FOREIGN KEY(Type) REFERENCES assettype(Type_ID),
  FOREIGN KEY(Category) REFERENCES category(id),
  FOREIGN KEY(Identity) REFERENCES identity(Iden_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel role
--
CREATE TABLE IF NOT EXISTS role (
  Role_ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  Name varchar(255) COLLATE utf8_bin DEFAULT NULL,
  Description varchar(255) COLLATE utf8_bin DEFAULT NULL,
  Type INTEGER UNSIGNED DEFAULT NULL,
  Active INTEGER(1) NOT NULL DEFAULT '1',
  Deactivate_reason varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (Role_ID),
  FOREIGN KEY(Type) REFERENCES roletype(Type_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
-- Initial Data
insert into role (Role_ID, Name, Description, Type) VALUES
(1,'Administrator','The administrator of the Application',1);
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel System
--
CREATE TABLE IF NOT EXISTS System (
  Sys_ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  Name varchar(255) COLLATE utf8_bin DEFAULT NULL,
  Active INTEGER(1) NOT NULL DEFAULT 1,
  Deactivate_reason varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (Sys_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel IdenAccount
--
CREATE TABLE IF NOT EXISTS IdenAccount (
	Identity INTEGER UNSIGNED NOT NULL,
	Account INTEGER UNSIGNED NOT NULL,
	ValidFrom DATETIME NOT NULL,
	ValidEnd DATETIME DEFAULT NULL,
	PRIMARY KEY (Identity,Account,ValidFrom),
	FOREIGN KEY(Identity) REFERENCES identity(Iden_ID),
	FOREIGN KEY(Account) REFERENCES account(Acc_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
-- Initial data
insert into IdenAccount (Identity,Account,ValidFrom) values
(1,1,'2012-01-01 00:00:00');
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel AccountRole
--
CREATE TABLE IF NOT EXISTS AccountRole (
	Role INTEGER UNSIGNED DEFAULT NULL,
	Account INTEGER UNSIGNED DEFAULT NULL,
	ValidFrom DATETIME DEFAULT NULL,
	ValidEnd DATETIME DEFAULT NULL,
	PRIMARY KEY (Role,Account),
	FOREIGN KEY(Role) REFERENCES role(Role_ID),
	FOREIGN KEY(Account) REFERENCES account(Acc_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
-- Initial data
insert into AccountRole (Role,Account,ValidFrom) values
(1,1,'2012-01-01 00:00:00');
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel AccountAppRole
--
CREATE TABLE IF NOT EXISTS AccountSysRole (
    Role INTEGER UNSIGNED DEFAULT NULL,
    Account INTEGER UNSIGNED DEFAULT NULL,
    System INTEGER UNSIGNED DEFAULT NULL,
    ValidFrom DATETIME DEFAULT NULL,
    ValidEnd DATETIME DEFAULT NULL,
    PRIMARY KEY (Account,Role,System),
	FOREIGN KEY(Role) REFERENCES role(Role_ID),
	FOREIGN KEY(Account) REFERENCES account(Acc_ID),
	FOREIGN KEY(System) REFERENCES system(Sys_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
/*-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel ttRoleRole
--
CREATE TABLE IF NOT EXISTS ttRoleRole (
	id INTEGER(11) not null AUTO_increment,
	Role1 INTEGER(11) DEFAULT NULL,
	Role2 INTEGER(11) DEFAULT NULL,
	ValidFrom DATETIME DEFAULT NULL,
	ValidEnd DATETIME DEFAULT NULL,
	PRIMARY KEY (ID),
	KEY Role1(Role1),
	KEY Role2 (Role2)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 */;
--
-- Tabelstructuur voor tabel Kensington
--
CREATE TABLE IF NOT EXISTS Kensington (
	Key_ID INTEGER UNSIGNED not null AUTO_increment,
	Type INTEGER UNSIGNED DEFAULT NULL,
	Serial Varchar(8) COLLATE utf8_bin DEFAULT NULL,
	AssetTag varchar(20) COLLATE utf8_bin DEFAULT NULL,
	AmountKeys INTEGER(11) NOT NULL DEFAULT 1,
	hasLock INTEGER(1) NOT NULL DEFAULT 0,
	Active INTEGER(1) NOT NULL DEFAULT 1,
	Deactivate_reason varchar(255) COLLATE utf8_bin DEFAULT NULL,
	PRIMARY KEY (Key_ID),
	FOREIGN KEY(Type) REFERENCES assettype(Type_ID),
	FOREIGN KEY(AssetTag) REFERENCES asset(AssetTag)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel Admin
--
CREATE TABLE IF NOT EXISTS Admin (
	Admin_id INTEGER UNSIGNED not null AUTO_increment,
	Account INTEGER UNSIGNED DEFAULT NULL,
	Level INTEGER UNSIGNED NOT NULL default 1,
	PassWord varchar(255) COLLATE utf8_bin NOT NULL,
	DateSet DATETIME NOT NULL,
	Active INTEGER(1) NOT NULL DEFAULT 1,
	Deactivate_reason varchar(255) COLLATE utf8_bin DEFAULT NULL,
	PRIMARY KEY (Admin_id),
	FOREIGN KEY(Account) REFERENCES account(Acc_ID),
	FOREIGN KEY(Level) REFERENCES level(level)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
INSERT INTO Admin (Admin_id,Account,Level,PassWord,DateSet) VALUE
(1,1,9,'61a99380acad7d202889ecfa941a38e6','2012-01-01 00:00:00');
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel OldPass
--
CREATE TABLE IF NOT EXISTS OldPAss (
	Pass_id INTEGER UNSIGNED not null AUTO_increment,
	Account INTEGER UNSIGNED DEFAULT NULL,
	DateSet DATETIME NOT NULL,
	PRIMary KEY (Pass_id),
	FOREIGN KEY(Account) REFERENCES account(Acc_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel Mobile
--
CREATE TABLE IF NOT EXISTS Mobile (
	IMEI INTEGER(20) not null,
	MobileType INTEGER UNSIGNED DEFAULT NULL,
	Identity INTEGER UNSIGNED DEFAULT 1,
	Active INTEGER(1) NOT NULL DEFAULT 1,
	Deactivate_reason varchar(255) COLLATE utf8_bin DEFAULT NULL,
	PRIMARY KEY (IMEI),
	FOREIGN KEY(MobileType) REFERENCES AssetType(Type_ID),
	FOREIGN KEY(Identity) REFERENCES identity(Iden_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel SubscriptionType
--
CREATE TABLE IF NOT EXISTS SubscriptionType (
  Type_ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  Type varchar(255) COLLATE utf8_bin DEFAULT NULL,
  Description varchar(255) COLLATE utf8_bin DEFAULT NULL,
  Provider varchar(255) COLLATE utf8_bin DEFAULT NULL,
  Active INTEGER(1) NOT NULL DEFAULT '1',
  Deactivate_reason varchar(255) COLLATE utf8_bin DEFAULT NULL,
  Category INTEGER UNSIGNED DEFAULT NULL,
  PRIMARY KEY (Type_ID),
  FOREIGN KEY(Category) REFERENCES category(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
/* INITIAL DATA*/
insert into SubscriptionType (Type_ID,Type,Description, Provider, Category) values
(1,'Expresnet','Telenet Expresnet','Telenet',4),
(2,'Fibernet','Telenet Fibernet','Telenet',4),
(3,'ADSL','ADSL','Belgacom',4),
(4,'VDSL','VDSL','Belgacom',4),
(5,'Split','Company pays back wath the user indicates','Proximus',3),
(6,'EUR 25','Company pays EUR 25 back per month','Proximus',3),
(7,'FULL','Company pays everyting back','Proximus',3);
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel Subscription
--
CREATE TABLE IF NOT EXISTS Subscription (
	Sub_ID INTEGER UNSIGNED not null AUTO_INCREMENT,
	PhoneNumber VARCHAR(255) COLLATE utf8_bin not null,
	SubscriptionType INTEGER UNSIGNED DEFAULT NULL,
	Identity INTEGER UNSIGNED DEFAULT NULL,
	IMEI INTEGER(20) DEFAULT NULL,
	Active INTEGER(1) NOT NULL DEFAULT '1',
  	Deactivate_reason varchar(255) COLLATE utf8_bin DEFAULT NULL,
	Category INTEGER UNSIGNED DEFAULT NULL,
	PRIMARY KEY (Sub_ID),
	FOREIGN KEY(Identity) REFERENCES identity(Iden_ID),
	FOREIGN KEY(IMEI) REFERENCES Mobile(IMEI),
	FOREIGN KEY(SubscriptionType) REFERENCES SubscriptionType(Type_ID),
	FOREIGN KEY(Category) REFERENCES category(id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel Menu
--
CREATE TABLE IF NOT EXISTS menu ( 
  Menu_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  label varchar(50) NOT NULL default '',
  link_url varchar(100) NOT NULL default '#',
  parent_id INTEGER UNSIGNED default NULL,
  PRIMARY KEY (Menu_id),
  FOREIGN KEY (parent_id) REFERENCES menu(Menu_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
-- Initial Data
insert into menu (Menu_id, label) VALUES (1,'Identity');
insert into menu (Menu_id, label,parent_id) VALUES (2,'Identity',1);
insert into menu (Menu_id, label,link_url,parent_id) VALUES (3,'Overview','Identity.php',2);
insert into menu (Menu_id, label) VALUES (4,'Account');
insert into menu (Menu_id, label,parent_id) VALUES (5,'Account',4);
insert into menu (Menu_id, label,link_url,parent_id) VALUES (6,'Overview','Account.php',5);
insert into menu (Menu_id, label) VALUES (7,'Role');
insert into menu (Menu_id, label,parent_id) VALUES (8,'Role',7);
insert into menu (Menu_id, label,link_url,parent_id) VALUES (9,'Overview','Role.php',8);
-- Devices
INSERT INTO menu (Menu_id, label, link_url, parent_id) VALUES
(10, 'Devices', '#', null),
(11, 'Laptop', '#', 10),
(12, 'Overview', 'Devices.php?Category=Laptop', 11),
(13, 'Desktop', '#', 10),
(14, 'Overview', 'Devices.php?Category=Desktop', 13),
(15, 'Monitor', '#', 10),
(16, 'Overview', 'Devices.php?Category=Monitor', 15),
(17,'Docking station','#',10),
(18,'Overview','Devices.php?Category=Docking%20station',17),
(19, 'Token', '#', 10),
(20, 'Overview', 'Token.php', 19),
(21, 'Kensington', '#', 10),
(22, 'Overview', 'Kensington.php', 21),
(23, 'Mobile', '#', 10),
(24, 'Overview', 'Mobile.php', 23),
(25, 'Subscription', '#', 10),
(26, 'Overview', 'Subscription.php', 25);
-- Types
INSERT INTO menu (Menu_id, label, link_url, parent_id) VALUES
(27, 'Types', '#', null),
(28, 'Asset Type', '#',27),
(29, 'Overview', 'AssetType.php',28),
(30, 'Asset Category', '#',29),
(31, 'Overview', 'Category.php',30),
(32, 'Identity Type', '#',27),
(33, 'Overview', 'IdentityType.php',32),
(34, 'Account Type', '#',27),
(35, 'Overview', 'AccountType.php',34),
(36, 'Role Type', '#',27),
(37, 'Overview', 'RoleType.php',36),
(38, 'Subscription Type', '#', 27),
(39, 'Overview', 'SubscriptionType.php', 38);
INSERT INTO menu (Menu_id, label, link_url, parent_id) VALUES
(40, 'System','#',null),
(41, 'System','#',40),
(42, 'Overview','Sytem.php',41),
(43, 'Application','#',NULL),
(44, 'Application','#',43),
(45, 'Overview','Application.php',44),
(46, 'Admin','#',null),
(47, 'Admin','#',46),
(48, 'Overview','Admin.php',47),
(49, 'Permissions','#',46),
(50, 'Overview','Permission.php',49);
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel Permissions
--
CREATE TABLE IF NOT EXISTS permissions (
  perm_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  permission VARCHAR(50) NOT NULL,
  description VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (perm_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1;
-- Initial Data
INSERT INTO permissions (perm_id,permission,description) VALUES
(1,"Read",NULL),
(2,"Update",NULL),
(3,"Delete",NULL),
(4,"Activate",NULL),
(5,"Add",NULL),
(6,"AssignAccount","This Permission is for assigning Accounts"),
(7,"AccountOverview","This Permission is to see The assigned Accounts"),
(8,"AssignDevice","This Permission is to Assign a Device"),
(9,"DeviceOverview","This Permission is see the assigned Device"),
(10,"AssignMobile","This Permission is to Assign a Mobile"),
(11,"MobileOverview","This Permission is to see the assigned Mobiles"),
(12,"AssignSubscription","This Permission is to Assign a Subscription"),
(13,"SubscriptionOverview","This Permission is to see the assigned Subscription"),
(14,"AssignIdentity","This Permission is to Assign a Identity"),
(15,"IdentityOverview","This Permission is see the assigned Identities"),
(16,"AssignAppRole","This Permission is to Assign a Application Role"),
(17,"AppRoleOverview","This Permission is to  see the assigned Application Roles"),
(18,"AssignSysRole","This Permission is to Assign a System Role"),
(19,"SysRoleOverview","This Permission is to see the assigned System Roles"),
(20,"AssignApplication","This Permission is to Assign an Application"),
(21,"ApplicationOverview","This Permission is to see the assigned Application"),
(22,"AssignSystem","This Permission is to Assign a System"),
(23,"SystemOverview","This Permission is to see the assigned Systems"),
(24,"AssignKensington","This Permission is to Assign a Kensington"),
(25,"KeyOverview","This Permission is to see the assigned Kensington"),
(26,"ReleaseDevice","This Permission is to release Device"),
(27,"ReleaseAccount","This Permission is to release Account"),
(28,"ReleaseMobile","This Permission is to release Mobile"),
(29,"ReleaseSubscription","This Permission is to release Subscription"),
(30,"ReleaseIdentity","This Permission is to release Identity"),
(31,"ReleaseAppRole","This Permission is to release Application Role"),
(32,"ReleaseSysRole","This Permission is to release System Role"),
(33,"ReleaseApplication","This Permission is to release Application"),
(34,"ReleaseSystem","This Permission is to release System"),
(35,"ReleaseKensington","This Permission is to release Kensington"),
(36,"AssignLevel","This Permission is to Assign a Level"),
(37,"PermissionOverview","This Permission is to see the Permissions"),
(38,"MenuOverview","This Permission is to see the Menu");

-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel Role_permissions
--
CREATE TABLE IF NOT EXISTS role_perm (
	role_perm_id INTEGER UNSIGNED AUTO_INCREMENT,
	level INTEGER UNSIGNED NOT NULL,
	perm_id INTEGER UNSIGNED NOT NULL,
	menu_id INTEGER UNSIGNED NOT NULL,
	PRIMARY KEY (role_perm_id),
	FOREIGN KEY (perm_id) REFERENCES permissions(perm_id),
	FOREIGN KEY (menu_id) REFERENCES Menu(Menu_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1;
-- Initial Data for Level 9 
-- Identity =2
INSERT INTO role_perm (level, perm_id, menu_id) VALUES
(9, 1, 2),
(9, 2, 2),
(9, 3, 2),
(9, 4, 2),
(9, 5, 2),
(9, 6, 2),
(9, 7, 2),
(9, 8, 2),
(9, 9, 2),
(9, 10, 2),
(9, 11, 2),
(9, 12, 2),
(9, 13, 2),
(9, 26, 2),
(9, 27, 2);
-- IdentityType =30
INSERT INTO role_perm (level, perm_id, menu_id) VALUES
(9, 1, 30),
(9, 2, 30),
(9, 3, 30),
(9, 4,	 30),
(9, 5, 30);
-- Account =5
INSERT INTO role_perm (level, perm_id, menu_id) VALUES
(9, 1, 5),
(9, 2, 5),
(9, 3, 5),
(9, 4, 5),
(9, 5, 5),
(9, 14, 5),
(9, 15, 5),
(9, 30, 5);
-- Role = 8
INSERT INTO role_perm (level, perm_id, menu_id) VALUES
(9, 1, 8),
(9, 2, 8),
(9, 3, 8),
(9, 4, 8),
(9, 5, 8),
(9, 20, 8),
(9, 21, 8),
(9, 33, 8),
(9, 34, 8);
-- Laptop = 11
INSERT INTO role_perm (level, perm_id, menu_id) VALUES
(9, 1, 11),
(9, 2, 11),
(9, 3, 11),
(9, 4, 11),
(9, 5, 11),
(9, 14, 11),
(9, 15, 11),
(9, 24, 11),
(9, 25, 11),
(9, 30, 11),
(9, 35, 11);
-- Desktop = 13
INSERT INTO role_perm (level, perm_id, menu_id) VALUES
(9, 1, 13),
(9, 2, 13),
(9, 3, 13),
(9, 4, 13),
(9, 5, 13),
(9, 14, 13),
(9, 15, 13),
(9, 24, 13),
(9, 25, 13),
(9, 30, 13),
(9, 35, 13);
-- Monitor = 15
INSERT INTO role_perm (level, perm_id, menu_id) VALUES
(9, 1, 15),
(9, 2, 15),
(9, 3, 15),
(9, 4, 15),
(9, 5, 15),
(9, 14, 15),
(9, 15, 15),
(9, 24, 15),
(9, 25, 15),
(9, 30, 15),
(9, 35, 15);
-- Docking = 17
INSERT INTO role_perm (level, perm_id, menu_id) VALUES
(9, 1, 17),
(9, 2, 17),
(9, 3, 17),
(9, 4, 17),
(9, 5, 17),
(9, 14, 17),
(9, 15, 17),
(9, 24, 15),
(9, 25, 15),
(9, 30, 17),
(9, 35, 17);
-- Token = 19
INSERT INTO role_perm (level, perm_id, menu_id) VALUES
(9, 1, 19),
(9, 2, 19),
(9, 3, 19),
(9, 4, 19),
(9, 5, 19),
(9, 14, 19),
(9, 15, 19),
(9, 30, 19);
-- Kensington = 21
INSERT INTO role_perm (level, perm_id, menu_id) VALUES
(9, 1, 21),
(9, 2, 21),
(9, 3, 21),
(9, 4, 21),
(9, 5, 21),
(9, 8, 21),
(9, 9, 21),
(9, 26, 21);
-- Mobile = 23
INSERT INTO role_perm (level, perm_id, menu_id) VALUES
(9, 1, 23),
(9, 2, 23),
(9, 3, 23),
(9, 4, 23),
(9, 5, 23),
(9, 14, 23),
(9, 15, 23),
(9, 12, 23),
(9, 13, 23),
(9, 29, 23),
(9, 30, 23);
-- Subscription = 25
INSERT INTO role_perm (level, perm_id, menu_id) VALUES
(9, 1, 25),
(9, 2, 25),
(9, 3, 25),
(9, 4, 25),
(9, 5, 25),
(9, 10, 25),
(9, 11, 25),
(9, 14, 25),
(9, 15, 25),
(9, 26, 25),
(9, 30, 25);
-- Asset Type = 28
INSERT INTO role_perm (level, perm_id, menu_id) VALUES
(9, 1, 28),
(9, 2, 28),
(9, 3, 28),
(9, 4, 28),
(9, 5, 28);
-- Asset Category = 30
INSERT INTO role_perm (level, perm_id, menu_id) VALUES
(9, 1, 30),
(9, 2, 30),
(9, 3, 30),
(9, 4, 30),
(9, 5, 30);
-- Identity Type = 32
INSERT INTO role_perm (level, perm_id, menu_id) VALUES
(9, 1, 32),
(9, 2, 32),
(9, 3, 32),
(9, 4, 32),
(9, 5, 32);
-- ACCOUNT Type = 34
INSERT INTO role_perm (level, perm_id, menu_id) VALUES
(9, 1, 34),
(9, 2, 34),
(9, 3, 34),
(9, 4, 34),
(9, 5, 34);
-- Role Type = 36
INSERT INTO role_perm (level, perm_id, menu_id) VALUES
(9, 1, 36),
(9, 2, 36),
(9, 3, 36),
(9, 4, 36),
(9, 5, 36);
-- Subscription type = 38
INSERT INTO role_perm (level, perm_id, menu_id) VALUES
(9, 1, 38),
(9, 2, 38),
(9, 3, 38),
(9, 4, 38),
(9, 5, 38);
-- System = 41
INSERT INTO role_perm (level, perm_id, menu_id) VALUES
(9, 1, 41),
(9, 2, 41),
(9, 3, 41),
(9, 4, 41),
(9, 5, 41),
(9, 6, 41),
(9, 7, 41),
(9, 32, 41);
-- Application = 44
INSERT INTO role_perm (level, perm_id, menu_id) VALUES
(9, 1, 44),
(9, 2, 44),
(9, 3, 44),
(9, 4, 44),
(9, 5, 44),
(9, 6, 44),
(9, 7, 44),
(9, 31, 44);
-- Admin = 47
INSERT INTO role_perm (level, perm_id, menu_id) VALUES
(9, 1, 47),
(9, 2, 47),
(9, 3, 47),
(9, 4, 47),
(9, 5, 47),
(9, 6, 47),
(9, 7, 47),
(9, 27, 47);
-- Permission = 49
INSERT INTO role_perm (level, perm_id, menu_id) VALUES
(9, 1, 49),
(9, 2, 49),
(9, 3, 49),
(9, 5, 49);

-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel log
--
CREATE TABLE IF NOT EXISTS log (
  Log_ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  Log_Text varchar(255) DEFAULT NULL,
  Log_Date datetime DEFAULT NULL,
  Account INTEGER UNSIGNED DEFAULT NULL,
  AccountType INTEGER UNSIGNED DEFAULT NULL,
  Admin INTEGER UNSIGNED DEFAULT NULL,
  Application INTEGER UNSIGNED DEFAULT NULL,
  System INTEGER UNSIGNED DEFAULT NULL,
  AssetTag varchar(20) DEFAULT NULL,
  AssetType INTEGER UNSIGNED DEFAULT NULL,
  Category INTEGER UNSIGNED DEFAULT NULL,
  Identity INTEGER UNSIGNED DEFAULT NULL,
  Kensington INTEGER UNSIGNED DEFAULT NULL,
  Role INTEGER UNSIGNED DEFAULT NULL,
  RoleType INTEGER UNSIGNED DEFAULT NULL,
  IdentityType INTEGER UNSIGNED DEFAULT NULL,
  IMEI int(20) DEFAULT NULL,
  Subscription INTEGER UNSIGNED DEFAULT NULL,
  SubscriptionType INTEGER UNSIGNED DEFAULT NULL,
   permissions INTEGER UNSIGNED DEFAULT NULL,
   menu INTEGER UNSIGNED DEFAULT NULL,
   role_perm_id INTEGER UNSIGNED DEFAULT NULL,
  PRIMARY KEY (Log_ID),
  FOREIGN KEY (Account) REFERENCES account(Acc_ID),
  FOREIGN KEY (AccountType) REFERENCES accounttype(Type_ID),
  FOREIGN KEY (Admin) REFERENCES Admin(Admin_id),
  FOREIGN KEY (Application) REFERENCES Application(App_ID),
  FOREIGN KEY (System) REFERENCES system(Sys_id),
  FOREIGN KEY (AssetTag) REFERENCES Asset(AssetTag),
  FOREIGN KEY (AssetType) REFERENCES AssetType(Type_ID),
  FOREIGN KEY (Category) REFERENCES Category(ID),
  FOREIGN KEY (Identity) REFERENCES Identity(Iden_ID),
  FOREIGN KEY (Kensington) REFERENCES Kensington(Key_ID),
  FOREIGN KEY (Role) REFERENCES Role(Role_ID),
  FOREIGN KEY (RoleType) REFERENCES roletype(Type_ID),
  FOREIGN KEY (IdentityType) REFERENCES IdentityType(Type_ID),
  FOREIGN KEY (IMEI) REFERENCES Mobile(IMEI),
  FOREIGN KEY (Subscription) REFERENCES Subscription(Sub_ID),
  FOREIGN KEY (SubscriptionType) REFERENCES SubscriptionType(Type_ID),
  FOREIGN KEY (permissions) REFERENCES permissions(perm_id),
  FOREIGN KEY (menu) REFERENCES menu(menu_id),
  FOREIGN KEY (role_perm_id) REFERENCES role_perm(role_perm_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- -------------------------------------------------------------------------------
insert into log (Log_ID, Category, Log_text, Log_Date) values
(1,1,'Kensington Created by SQL Import','2012-01-01 00:00:00'),
(2,2,'Mobile Created by SQL Import','2012-01-01 00:00:00'),
(3,3,'Mobile Subscription Created by SQL Import','2012-01-01 00:00:00'),
(4,4,'Internet Subscription Created by SQL Import','2012-01-01 00:00:00'),
(5,5,'Laptop Created by SQL Import','2012-01-01 00:00:00'),
(6,6,'Desktop Created by SQL Import','2012-01-01 00:00:00'),
(7,7,'Token Created by SQL Import','2012-01-01 00:00:00');
insert into log (Log_ID, IdentityType, Log_text, Log_Date) values
(8,1,'Werknemer Created by SQL Import','2012-01-01 00:00:00'),
(9,2,'Exeterne Medewerker Created by SQL Import','2012-01-01 00:00:00');
insert into log (Log_ID, SubscriptionType, Log_text, Log_Date) values
(10,1,'Telenet Expresnet Created by SQL Import','2012-01-01 00:00:00'),
(11,2,'Telenet Fibernet Created by SQL Import','2012-01-01 00:00:00'),
(12,3,'Belgacom ADSL Created by SQL Import','2012-01-01 00:00:00'),
(13,4,'Belgacom VDSL Created by SQL Import','2012-01-01 00:00:00'),
(14,5,'Proximus Split Created by SQL Import','2012-01-01 00:00:00'),
(15,6,'Proximus EUR 25 Created by SQL Import','2012-01-01 00:00:00'),
(16,7,'Proximus FULL Created by SQL Import','2012-01-01 00:00:00');
insert into log (Log_ID, Identity, Log_text, Log_Date) values
(17,1,'Identity Stock Created by SQL Import','2012-01-01 00:00:00');
insert into log (Log_ID, AccountType, Log_text, Log_Date) values
(18,1,'Accounttype Normal User Created by SQL Import','2012-01-01 00:00:00'),
(19,2,'Accounttype Administrator Created by SQL Import','2012-01-01 00:00:00'),
(20,3,'Accounttype Servive User Created by SQL Import','2012-01-01 00:00:00');
insert into log (Log_ID, Application, Log_text, Log_Date) values
(21,1,'Application Active Directory Created by SQL Import','2012-01-01 00:00:00'),
(22,2,'Application CMDB Created by SQL Import','2012-01-01 00:00:00');
insert into log (Log_ID, RoleType, Log_text, Log_Date) values
(23,1,'Roletype Application Created by SQL Import','2012-01-01 00:00:00'),
(24,2,'Roletype System Created by SQL Import','2012-01-01 00:00:00');
insert into log (Log_ID, Role, Log_text, Log_Date) values
(25,1, 'The Role Administrator is created by SQL Import', '2012-01-01 00:00:00');