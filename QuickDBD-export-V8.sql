-- Exported from QuickDBD: https://www.quickdatabasediagrams.com/
-- Link to schema: https://app.quickdatabasediagrams.com/#/d/OFTrxi
-- NOTE! If you have used non-SQL datatypes in your design, you will have to change these here.

CREATE DATABASE catch;

USE catch;

CREATE TABLE `profiles` (
    `id` int  NOT NULL PRIMARY KEY auto_increment,
    `lastName` varchar(100)  NOT NULL ,
    `firstName` varchar(100)  NOT NULL ,
    `email` varchar(100)  NOT NULL ,
    `birthday` date  NOT NULL ,
    `gender` bool  NOT NULL ,
    `pswd` varchar(100)  NOT NULL ,
    `acceptCGV` bool ,
    `ban` bool ,
    `pseudo` varchar(100) ,
    `avatarId` int ,
    `town` varchar(100) ,
    `catchPhrase` varchar(255) ,
    `meetId` int ,
    `searchGender` bool
);

CREATE TABLE `match` (
    `id` int  NOT NULL PRIMARY KEY auto_increment,
    `profile1Id` int  NOT NULL ,
    `profile2Id` int  NOT NULL ,
    `profile1Status` enum('undef', 'accept', 'refuse')  NOT NULL ,
    `profile2Status` enum('undef', 'accept', 'refuse')  NOT NULL ,
    `date1Status` DATETIME,
    `date2Status` DATETIME,
    `matchStatus` bool  NOT NULL
);

CREATE TABLE `tchat` (
    `id` int  NOT NULL PRIMARY KEY auto_increment,
    `message` text(1000)  NOT NULL ,
    `dateHour` datetime  NOT NULL ,
    `emettorId` int  NOT NULL ,
    `matchId` int  NOT NULL
);

CREATE TABLE `hobbies` (
    `id` int  NOT NULL PRIMARY KEY auto_increment,
    `hobby` varchar(100)  NOT NULL
);

CREATE TABLE `hobby_profile` (
    `id` int  NOT NULL PRIMARY KEY auto_increment,
    `profileId` int  NOT NULL ,
    `hobbiesId` int  NOT NULL
);

CREATE TABLE `comment` (
    `id` int  NOT NULL PRIMARY KEY auto_increment,
    `subject` varchar(100)  NOT NULL ,
    `message` text(1000)  NOT NULL ,
    `signalId` int,
    `profilId` int  NOT NULL
);

CREATE TABLE pictures (
    img_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT ,
    img_nom VARCHAR(200) NOT NULL ,
    img_taille VARCHAR(25),
    img_type VARCHAR(25),
    img_desc VARCHAR(100),
    img_blob BLOB,
    `profilId` int  NOT NULL
);

CREATE TABLE meets (
    `id` int  NOT NULL PRIMARY KEY auto_increment,
    meet VARCHAR(50) NOT NULL
);

ALTER TABLE `match` ADD CONSTRAINT `fk_match_profile1Id` FOREIGN KEY(`profile1Id`)
REFERENCES `profiles` (`id`);

ALTER TABLE `match` ADD CONSTRAINT `fk_match_profile2Id` FOREIGN KEY(`profile2Id`)
REFERENCES `profiles` (`id`);

ALTER TABLE `tchat` ADD CONSTRAINT `fk_tchat_matchId` FOREIGN KEY(`matchId`)
REFERENCES `match` (`id`);

ALTER TABLE `hobby_profile` ADD CONSTRAINT `fk_hobby_profile_profileId` FOREIGN KEY(`profileId`)
REFERENCES `profiles` (`id`);

ALTER TABLE `hobby_profile` ADD CONSTRAINT `fk_hobby_profile_hobbiesId` FOREIGN KEY(`hobbiesId`)
REFERENCES `hobbies` (`id`);

ALTER TABLE `comment` ADD CONSTRAINT `fk_comment_profilId` FOREIGN KEY(`profilId`)
REFERENCES `profiles` (`id`);

ALTER TABLE `pictures` ADD CONSTRAINT `fk_pictures_profilId` FOREIGN KEY(`profilId`)
REFERENCES `profiles` (`id`);

ALTER TABLE `profiles` ADD CONSTRAINT `fk_profiles_meetId` FOREIGN KEY(`meetId`)
REFERENCES `meets` (`id`);

INSERT INTO meets (meet) VALUES ('short'), ('medium'), ('long');

INSERT INTO `profiles` (`lastName`,`firstName`,`email`,`birthday`,`gender`,`pswd`,`acceptCGV`,`ban`,`pseudo`,`avatarId`,`town`,`catchPhrase`,`meetId`,`searchGender`) VALUES ("Cook","Jackson","sodales@utquam.net","2001-10-08","1","LNV82SGH4OQ","1","0","Jacquette",391,"Lyon","turpis. Nulla aliquet. Proin velit. Sed malesuada augue ut lacus.","1","0"),
("Johnston","Marsden","Nulla.semper.tellus@neque.edu","2001-12-21","1","WLZ55FBD8OD","1","0","Maddie",79,"Marseille","rhoncus. Donec est. Nunc ullamcorper, velit in aliquet lobortis, nisi nibh lacinia orci, consectetuer euismod est arcu ac orci. Ut","2","0"),
("Horn","Uma","cursus.purus@magnaPhasellusdolor.edu","1982-08-10","1","BSE47GTB5AR","1","0","Irma",128,"Nantes","erat, in consectetuer ipsum nunc id enim. Curabitur massa. Vestibulum accumsan neque et nunc. Quisque","2","0"),
("Noble","Aaron","nec.ante@facilisismagnatellus.org","1930-10-31","0","LRF43UBY2EM","1","0","Aron",927,"Valence","nascetur ridiculus mus. Donec dignissim magna a tortor. Nunc commodo auctor velit. Aliquam nisl.","1","1"),
("Langley","George","aliquet.diam.Sed@ornarelectusante.edu","1930-07-16","0","FVW82WSZ3QN","1","0","Georgio",272,"Saint-Etienne","sodales nisi magna sed dui. Fusce aliquam, enim nec tempus scelerisque,","3","1"),
("Pennington","Barclay","Praesent@bibendumfermentummetus.net","1996-10-03","1","LED52YCO9QJ","1","1","Bimbie",581,"Paris","ante. Vivamus non lorem vitae odio sagittis semper. Nam tempor diam dictum","3","0"),
("Garrett","Indigo","lacus.Quisque@etarcuimperdiet.co.uk","2001-02-12","0","QXO47LPX1BO","1","1","Gringo",422,"Toulouse","rhoncus. Nullam velit dui, semper et, lacinia vitae, sodales at,","1","1"),
("Schultz","Norman","eget.laoreet.posuere@mollisIntegertincidunt.edu","1958-03-08","0","NLP32HZJ7BJ","1","0","Paulo",842,"Grenoble","fermentum convallis ligula. Donec luctus aliquet odio. Etiam ligula tortor, dictum eu,","1","1"),
("Randall","Samson","feugiat.Sed@Integeraliquamadipiscing.ca","2002-12-09","0","MDM50ISX2SP","1","0","Simon",62,"Villefranche","sem, vitae aliquam eros turpis non enim. Mauris quis turpis vitae purus gravida sagittis. Duis gravida.","2","1"),
("Morgan","Jelani","ligula.elit@Etiam.org","1976-07-24","1","YHW55NPQ7OK","1","0","Chrissie",288,"Lille","adipiscing. Mauris molestie pharetra nibh. Aliquam ornare, libero at auctor ullamcorper, nisl arcu iaculis","3","0");

INSERT INTO pictures (`img_nom`,`img_taille`,`img_type`,`img_desc`,`img_blob`,`profilId`)
VALUES ("image1.jpg", "100", "type", "toto", "blob", 1);
INSERT INTO pictures (`img_nom`,`img_taille`,`img_type`,`img_desc`,`img_blob`,`profilId`)
VALUES ("image2.jpg", "100", "type", "toto", "blob", 1);
INSERT INTO pictures (`img_nom`,`img_taille`,`img_type`,`img_desc`,`img_blob`,`profilId`)
VALUES ("image3.jpg", "100", "type", "toto", "blob", 2);
INSERT INTO pictures (`img_nom`,`img_taille`,`img_type`,`img_desc`,`img_blob`,`profilId`)
VALUES ("image4.jpg", "100", "type", "toto", "blob", 2);
INSERT INTO pictures (`img_nom`,`img_taille`,`img_type`,`img_desc`,`img_blob`,`profilId`)
VALUES ("image5.jpg", "100", "type", "toto", "blob", 2);
INSERT INTO pictures (`img_nom`,`img_taille`,`img_type`,`img_desc`,`img_blob`,`profilId`)
VALUES ("image6.jpg", "100", "type", "toto", "blob", 2);
