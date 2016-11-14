CREATE TABLE `yup`.`code` (
`id_checkpoint` INT NOT NULL ,
`phrase_claire` TEXT NOT NULL ,
`phrase_crypte` TEXT NOT NULL ,
PRIMARY KEY ( `id_checkpoint` )
) ENGINE = InnoDB;

ALTER TABLE `checkpoint` ADD `cid` VARCHAR(100) NOT NULL AFTER `id`;

ALTER TABLE `itineraire_rw` ADD `validated_by` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `utilisateur` CHANGE `id` `id` INT( 11 ) NOT NULL AUTO_INCREMENT; 
INSERT INTO `yup`.`utilisateur` (
`id` ,
`login` ,
`password` ,
`derniere_connexion`
)
VALUES (
NULL , 'flo', MD5( 'amyeva' ) , ''
);

ALTER TABLE `itineraire_rw` CHANGE `etat` `etat` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ;

ALTER TABLE `itineraire_rw` ADD `checksum` VARCHAR( 255 ) NOT NULL ;

CREATE TABLE `parameters` (
`id` VARCHAR( 50 ) NOT NULL ,
`value` TEXT NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = InnoDB;


ALTER TABLE `code` CHANGE `phrase_crypte` `phrase_azimut` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL 