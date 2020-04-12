create table `strana` (
  id int unsigned not null auto_increment,
  `name` varchar(32) default null,
  primary key (id)
);

create table `gorod` (
  id int unsigned not null auto_increment,
  `name` varchar(32) default null,
  `strana_id` int unsigned default null,
  primary key (id),
  foreign key (strana_id) references strana(id)
  on update cascade
  on delete cascade
);

create table `uchebnye_zavedeniya` (
  id int unsigned not null auto_increment,
  `name` varchar(10) default null,
  `gorod_id` int unsigned default null,
  primary key (id),
  foreign key (gorod_id) references gorod(id)
  on update cascade
  on delete cascade
);

create table `fakultety` (
  id int unsigned not null auto_increment,
  `name` varchar(4) default null,
  `kolichestvo_mest` tinyint(4) default null,
  primary key (id)
);

create table `kafedry` (
  id int unsigned not null auto_increment,
  `name` varchar(46) default null,
  `fakultety_id` int unsigned default null,
  primary key (id),
  foreign key (fakultety_id) references fakultety(id)
  on update cascade
  on delete cascade
);

create table `gruppy` (
  id int unsigned not null auto_increment,
  `name` int(11) default null,
  `kafedry_id` int unsigned default null,
  primary key (id),
  foreign key (kafedry_id) references kafedry(id)
  on update cascade
  on delete cascade
);

create table `predmety` (
  id int unsigned not null auto_increment,
  `name` varchar(16) default null,
   primary key (id)
);

create table `lgoty_postupayuschih` (
  id int unsigned not null auto_increment,
  `name` varchar(18) default null,
  primary key (id)
);

create table `potoki` (
  id int unsigned not null auto_increment,
  `name` int(11) default null,
  primary key (id)
);

create table `potok_grupp` (
  id int unsigned not null auto_increment,
  `potoki_id` int unsigned default null,
  `gruppy_id` int unsigned default null,
  primary key (id),
  foreign key (potoki_id) references potoki(id)  on update cascade  on delete cascade,
  foreign key (gruppy_id) references gruppy(id)  on update cascade  on delete cascade
);

create table `tip_raspisaniya` (
  id int unsigned not null auto_increment,
  `name` varchar(12) default null,
  primary key (id)
);

create table `abiturienty` (
  id int unsigned not null auto_increment,
  `familiya` varchar(11) default null,
  `name` varchar(9) default null,
  `otchestvo` varchar(13) default null,
  `data_rozh` varchar(19) default null,
  `uchebnye_zavedeniya_id` int unsigned default null,
  `pasport` varchar(9) default null,
  `data_okonchaniya` varchar(19) default null,
  `lgoty_postupayuschih_id` int unsigned default null,
  `gruppy_id` int unsigned default null,
  `nomer` tinyint(4) default null,
  primary key (id),
  foreign key (uchebnye_zavedeniya_id) references uchebnye_zavedeniya(id)  on update cascade  on delete cascade,
  foreign key (lgoty_postupayuschih_id) references lgoty_postupayuschih(id)  on update cascade  on delete cascade,
  foreign key (gruppy_id) references gruppy(id)  on update cascade  on delete cascade
);

INSERT INTO `institution`.`abiturienty` 
(`familiya`, `name`, `otchestvo`, `data_rozh`, `uchebnye_zavedeniya_id`, `pasport`, `data_okonchaniya`, `gruppy_id`, `nomer`)
VALUES 
('Здончик','Диана','Ивановна','1999-03-23 00:00:00',1,'КН2465952','2016-05-30 00:00:00',4,11),
('Прохоров','Артем','Владимирович','1998-10-14 00:00:00',4,'МР8556414','2016-05-29 00:00:00',2,12),
('Тодоренко','Регина','Петровна','1998-06-05 00:00:00',5,'КН6865413','2016-05-30 00:00:00',1,13),
('Здончик','Катя','Ивановна','1999-03-20 00:00:00',4,'КН8791365','2017-05-29 00:00:00',1,14),
('Прохоров','Кирилл','Владимирович','1999-02-10 00:00:00',1,'КН8798413','2017-05-30 00:00:00',3,15),
('Титович','Евгений','Викторович','1999-11-07 00:00:00',2,'КН6521458','2017-05-30 00:00:00',3,16),
('Белов','Юрий','Александрович','1998-11-10 00:00:00',3,'КН2135462','2016-05-30 00:00:00',1,17),
('Малащенко','Михаил','Викторович','1998-11-14 00:00:00',7,'КН3236851','2016-05-30 00:00:00',4,18),
('Карачинский','Александр','Александрович','1997-11-16 00:00:00',2,'КН4566521','2015-05-29 00:00:00',3,19),
('Жолнерович','Андрей','Михайлович','1998-11-09 00:00:00',8,'МР1548254','2016-05-30 00:00:00',2,20),
('Чаплыгин','Дмитрий','Иванович','1998-11-08 00:00:00',4,'КН5489136','2016-05-30 00:00:00',1,21),
('Здончик','Иван','Иванович','1999-11-15 00:00:00',6,'КН1549632','2017-05-29 00:00:00',4,22),
('Сидор','Егор','Александрович','2000-11-15 00:00:00',4,'КН5447512','2018-05-30 00:00:00',2,23),
('Король','Екатерина','Викторовна','1998-11-23 00:00:00',6,'КН5165462','2016-05-30 00:00:00',3,24);

create table `ekzamenacionnyj_list` (
  id int unsigned not null auto_increment,
  `name` varchar(12) default null,
  `predmety_id` int unsigned default null,
  `abiturienty_id` int unsigned default null,
  primary key (id),
  foreign key (predmety_id) references predmety(id)  on update cascade  on delete cascade,
  foreign key (abiturienty_id) references abiturienty(id)  on update cascade  on delete cascade
);

create table `vedomosti` (
  id int unsigned not null auto_increment,
  `ocenka` tinyint(4) default null,
  `primechanie` varchar(0) default null,
  `ekzamenacionnyj_list_id` int unsigned default null,
  primary key (id),
  foreign key (ekzamenacionnyj_list_id) references ekzamenacionnyj_list(id)  on update cascade  on delete cascade
);

create table `konkurs_na_kafedru` (
	id int unsigned not null auto_increment,
  `kafedry_id` int unsigned default null,
  `prohodnoj_ball` decimal(2,1) default null,
  `predmet_1` varchar(10) default null,
  `predmet_2` varchar(16) default null,
  `predmet_3` varchar(15) default null,
  primary key (id),
  foreign key (kafedry_id) references kafedry(id)  on update cascade  on delete cascade
);

create table `raspisanie` (
  id int unsigned not null auto_increment,
  `tip_raspisaniya_id` int unsigned default null,
  `predmety_id` int unsigned default null,
  `potoki_id` int unsigned default null,
  `data_provedeniya` varchar(19) default null,
  `auditoriya` varchar(9) default null,
  primary key (id),
  foreign key (tip_raspisaniya_id) references tip_raspisaniya(id)  on update cascade  on delete cascade,
  foreign key (predmety_id) references predmety(id)  on update cascade  on delete cascade,
  foreign key (potoki_id) references potoki(id)  on update cascade  on delete cascade
);

CREATE TABLE IF NOT EXISTS `role` (
   id INT UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS `users` (
  id INT UNSIGNED AUTO_INCREMENT NOT NULL,
 `login` varchar(100) NOT NULL,
 `password` varchar(255) NOT NULL,
  role_id INT UNSIGNED NOT NULL DEFAULT 1,
  fullName varchar(255) NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (role_id) REFERENCES role(id)  ON UPDATE CASCADE ON DELETE CASCADE
);

INSERT INTO `institution`.`role`
(`id`, `name`)
VALUES
(1, 'Админ'),
(2, 'Пользователи');

INSERT INTO users (`login`, `password`, `fullName`, `role_id`) VALUES('diana', '12345', 'test', 1);

INSERT INTO `institution`.`strana`
(`name`)
VALUES
('Беларусь'),('Россия');

INSERT INTO `institution`.`gorod`
(`name`,
`strana_id`)
VALUES
('Минск',1),('Витебск',1),('Лида',2),('Москва',2),('Санкт-Петербург',2);

INSERT INTO `institution`.`uchebnye_zavedeniya` 
(`name`, `gorod_id`)
VALUES 
('Школа 1',3),('Школа 2',1),('Школа 5',3),('Школа 4',4),('Школа 5',1),('Гимназия 1',3),('Гимназия 2',1),('Лицей',4);

INSERT INTO `institution`.`lgoty_postupayuschih` 
(`name`)
VALUES 
('Золотая медаль'),('Серебрянная медаль'),('Многодетная семья'),('тмо'),('Чернобылец');

INSERT INTO `institution`.`fakultety` 
(`name`, `kolichestvo_mest`)
VALUES 
('МТФ',50),('ФИТР',60);

INSERT INTO `institution`.`kafedry` 
(`name`, `fakultety_id`)
VALUES 
('Автомобили',1),
('Гидропневмоавтоматика и гидропневмопривод',1),
('Двигатели внутреннего сгорания',1),
('Инженерная графика машиностроительного профиля',2),
('Робототехнические системы',1),
('Техническая эксплуатация автомобилей',1),
('Тракторы',1),
('Экономика и логистика',1);

INSERT INTO `institution`.`gruppy`
(`name`, `kafedry_id`)
VALUES 
(10703216,4),(10701216,5),(10706216,3),(10705216,7);

INSERT INTO `institution`.`abiturienty` 
(`familiya`, `name`, `otchestvo`, `data_rozh`, `uchebnye_zavedeniya_id`, `pasport`, `data_okonchaniya`, `gruppy_id`, `nomer`)
VALUES 
('Здончик','Диана','Ивановна','1999-03-23 00:00:00',1,'КН2465952','2016-05-30 00:00:00',4,11),
('Прохоров','Артем','Владимирович','1998-10-14 00:00:00',4,'МР8556414','2016-05-29 00:00:00',2,12),
('Тодоренко','Регина','Петровна','1998-06-05 00:00:00',5,'КН6865413','2016-05-30 00:00:00',1,13),
('Здончик','Катя','Ивановна','1999-03-20 00:00:00',4,'КН8791365','2017-05-29 00:00:00',1,14),
('Прохоров','Кирилл','Владимирович','1999-02-10 00:00:00',1,'КН8798413','2017-05-30 00:00:00',3,15),
('Титович','Евгений','Викторович','1999-11-07 00:00:00',2,'КН6521458','2017-05-30 00:00:00',3,16),
('Белов','Юрий','Александрович','1998-11-10 00:00:00',3,'КН2135462','2016-05-30 00:00:00',1,17),
('Малащенко','Михаил','Викторович','1998-11-14 00:00:00',7,'КН3236851','2016-05-30 00:00:00',4,18),
('Карачинский','Александр','Александрович','1997-11-16 00:00:00',2,'КН4566521','2015-05-29 00:00:00',3,19),
('Жолнерович','Андрей','Михайлович','1998-11-09 00:00:00',8,'МР1548254','2016-05-30 00:00:00',2,20),
('Чаплыгин','Дмитрий','Иванович','1998-11-08 00:00:00',4,'КН5489136','2016-05-30 00:00:00',1,21),
('Здончик','Иван','Иванович','1999-11-15 00:00:00',6,'КН1549632','2017-05-29 00:00:00',4,22),
('Сидор','Егор','Александрович','2000-11-15 00:00:00',4,'КН5447512','2018-05-30 00:00:00',2,23),
('Король','Екатерина','Викторовна','1998-11-23 00:00:00',6,'КН5165462','2016-05-30 00:00:00',3,24);

INSERT INTO `institution`.`predmety` 
(`name`)
VALUES 
('Математика'),('Русский язык'),('Белорусский язык'),('Химия'),('Физика'),('Обществоведение');

INSERT INTO `institution`.`tip_raspisaniya` 
(`name`)
VALUES 
('Консультация'),('Экзамен');

INSERT INTO `institution`.`potoki`
(`name`)
VALUES 
(1021125),(1025545),(125454);

INSERT INTO `institution`.`potok_grupp` 
(`potoki_id`, `gruppy_id`)
VALUES 
(1,1),(2,2),(1,3),(3,4),(1,2),(2,1),(1,3);

INSERT INTO `institution`.`konkurs_na_kafedru` 
(`kafedry_id`, `prohodnoj_ball`, `predmet_1`, `predmet_2`, `predmet_3`)
VALUES 
(1,7.0,'Математика','Русский язык','Физика'),
(2,8.5,'Математика','Русский язык','Физика'),
(3,8.0,'Математика','Белорусский язык','Химия'),
(4,9.0,'Математика','Русский язык','Физика'),
(5,7.5,'Математика','Белорусский язык','Химия'),
(6,8.0,'Математика','Русский язык','Физика'),
(7,9.0,'Математика','Русский язык','Физика'),
(8,8.5,'Математика','Русский язык','Обществоведение');

INSERT INTO `institution`.`raspisanie`
(`tip_raspisaniya_id`, `predmety_id`, `potoki_id`, `data_provedeniya`, `auditoriya`)
VALUES 
(1,3,2,'2019-11-06 08:00:00','каб. №213'),
(1,5,2,'2019-11-08 08:00:00','каб. №211'),
(2,2,1,'2019-11-02 17:00:00','каб. №220'),
(2,3,2,'2019-11-03 14:30:00','каб. №215'),
(1,1,3,'2019-11-18 17:00:00','309'),
(1,2,3,'2019-11-19 17:00:00','310');

INSERT INTO `institution`.`ekzamenacionnyj_list` 
(`name`, `predmety_id`, `abiturienty_id`)
VALUES 
(1,1,1),(2,2,1),
(3,5,1),(4,1,2),
(5,3,2),(6,4,2),
(7,1,3),(8,2,3),
(9,5,3),(10,1,4),
(11,2,4),(12,5,4),
(13,1,5),(14,3,5),
(15,4,5),(16,1,6),
(17,3,6),(18,4,6),
(19,1,7),(20,2,7),
(21,5,7),(22,1,8),
(23,2,8),(24,5,8),
(25,1,9),(26,3,9),
(27,4,9),(28,1,10),
(29,3,10),(30,4,10),
(31,1,11),(32,2,11),
(33,5,11),(34,1,12),
(35,2,12),(36,5,12),
(37,1,13),(38,3,13),
(39,4,13),(40,1,14),
(41,3,14),(42,4,14);

INSERT INTO `institution`.`vedomosti` 
(`ocenka`, `primechanie`, `ekzamenacionnyj_list_id`)
VALUES 
(9,'',1),(9,'',2),(8,'',3),
(8,'',4),(8,'',5),(7,'',6),
(10,'',7),(10,'',8),(10,'',9),
(7,'',10),(8,'',11),(9,'',12),
(8,'',13),(9,'',14),(9,'',15),
(10,'',16),(9,'',17),(8,'',18),
(5,'',19),(6,'',20),(7,'',21),
(8,'',22),(8,'',23),(9,'',24),
(7,'',25),(8,'',26),(9,'',27),
(10,'',28),(6,'',29),(7,'',30),
(9,'',31),(7,'',32),(8,'',33),
(9,'',34),(10,'',35),(10,'',36),
(9,'',37),(10,'',38),(8,'',39),
(8,'',40),(8,'',41),(7,'',42);

-- Запрос 1. Информация о поступлении студента
SELECT abiturienty.familiya, abiturienty.name AS abiturientyName, abiturienty.otchestvo, fakultety.name AS fakultetyName, kafedry.name AS kafedryName, gruppy.name AS gruppyName
	FROM abiturienty
    INNER JOIN gruppy ON gruppy.id = abiturienty.gruppy_id
    INNER JOIN kafedry ON kafedry.id = gruppy.kafedry_id
    INNER JOIN fakultety ON fakultety.id = kafedry.fakultety_id
    ORDER BY gruppy.name asc, abiturienty.familiya asc;

-- Запрос 1. Информация о поступлении студента
DELIMITER $$
DROP PROCEDURE IF EXISTS `getAbiturients` $$
CREATE PROCEDURE getAbiturients()
BEGIN
SELECT abiturienty.familiya, abiturienty.name, abiturienty.otchestvo, fakultety.name, kafedry.name, gruppy.name 
	FROM abiturienty
    INNER JOIN gruppy ON gruppy.id = abiturienty.gruppy_id
    INNER JOIN kafedry ON kafedry.id = gruppy.kafedry_id
    INNER JOIN fakultety ON fakultety.id = kafedry.fakultety_id
    ORDER BY gruppy.name asc, abiturienty.familiya asc;
END$$

CALL getAbiturients();users

-- Запрос 2. Какие предметы сдавать студенту
SELECT abiturienty.id, abiturienty.familiya, abiturienty.name AS abiturientName, 
abiturienty.otchestvo, gruppy.name AS gruppyName, kafedry.name AS kafedryName, 
konkurs_na_kafedru.predmet_1, konkurs_na_kafedru.predmet_2, konkurs_na_kafedru.predmet_3 
	FROM konkurs_na_kafedru 
    INNER JOIN kafedry ON kafedry.id = konkurs_na_kafedru.kafedry_id 
    INNER JOIN gruppy ON gruppy.kafedry_id = kafedry.id 
    INNER JOIN abiturienty ON abiturienty.gruppy_id = gruppy.id  
    ORDER BY abiturienty.id asc, abiturienty.familiya asc;


-- Запрос 3. Конкурс на факультеты
SELECT fakultety.name AS fakultetyName, fakultety.kolichestvo_mest
	FROM fakultety
    ORDER BY fakultetyName asc;

    
-- Запрос 4. Максимальный балл по предмету
SELECT abiturienty.familiya, abiturienty.name AS abiturientName, gruppy.name AS gruppyName, 
predmety.name AS predmetyName, MAX(vedomosti.ocenka) AS maxOcenka
	FROM predmety
    INNER JOIN ekzamenacionnyj_list ON ekzamenacionnyj_list.predmety_id = predmety.id
    INNER JOIN abiturienty ON abiturienty.id = ekzamenacionnyj_list.abiturienty_id
    INNER JOIN gruppy ON gruppy.id = abiturienty.gruppy_id
    INNER JOIN vedomosti ON vedomosti.ekzamenacionnyj_list_id = ekzamenacionnyj_list.id
	GROUP BY ekzamenacionnyj_list.predmety_id;

-- Запрос 4. Максимальный балл по предмету
DELIMITER $$
DROP PROCEDURE IF EXISTS `getMaxOcenka` $$
CREATE PROCEDURE getMaxOcenka()
BEGIN
SELECT abiturienty.familiya, abiturienty.name AS abiturientName, gruppy.name AS gruppyName, 
predmety.name AS predmetyName, MAX(vedomosti.ocenka) AS maxOcenka
	FROM predmety
    INNER JOIN ekzamenacionnyj_list ON ekzamenacionnyj_list.predmety_id = predmety.id
    INNER JOIN abiturienty ON abiturienty.id = ekzamenacionnyj_list.abiturienty_id
    INNER JOIN gruppy ON gruppy.id = abiturienty.gruppy_id
    INNER JOIN vedomosti ON vedomosti.ekzamenacionnyj_list_id = ekzamenacionnyj_list.id
	GROUP BY ekzamenacionnyj_list.predmety_id;
END$$

CALL getMaxOcenka();


-- Запрос 5. Средний балл по предмету на факультет
SELECT predmety.name AS predmetyName, fakultety.name AS fakultetyName, AVG(vedomosti.ocenka) AS sredniBal
	FROM fakultety
    INNER JOIN kafedry ON kafedry.fakultety_id = fakultety.id
    INNER JOIN gruppy ON gruppy.kafedry_id = kafedry.id
    INNER JOIN abiturienty ON abiturienty.gruppy_id = gruppy.id
    INNER JOIN ekzamenacionnyj_list ON ekzamenacionnyj_list.abiturienty_id = abiturienty.id
    INNER JOIN predmety ON predmety.id = ekzamenacionnyj_list.predmety_id
    INNER JOIN vedomosti ON vedomosti.ekzamenacionnyj_list_id = ekzamenacionnyj_list.id
	GROUP BY predmety.name, fakultety.name;

-- Запрос 5. Средний балл по предмету на факультет
DELIMITER $$
DROP PROCEDURE IF EXISTS `getAvgBal` $$
CREATE PROCEDURE getAvgBal()
BEGIN
SELECT predmety.name AS predmetyName, fakultety.name AS fakultetyName, AVG(vedomosti.ocenka) AS sredniBal
	FROM fakultety
    INNER JOIN kafedry ON kafedry.fakultety_id = fakultety.id
    INNER JOIN gruppy ON gruppy.kafedry_id = kafedry.id
    INNER JOIN abiturienty ON abiturienty.gruppy_id = gruppy.id
    INNER JOIN ekzamenacionnyj_list ON ekzamenacionnyj_list.abiturienty_id = abiturienty.id
    INNER JOIN predmety ON predmety.id = ekzamenacionnyj_list.predmety_id
    INNER JOIN vedomosti ON vedomosti.ekzamenacionnyj_list_id = ekzamenacionnyj_list.id
	GROUP BY predmety.name, fakultety.name;
END$$

CALL getAvgBal();



-- Запрос 6. Оценки абитуриента
SELECT abiturienty.name AS abiturientyName, abiturienty.familiya, predmety.name AS predmetyName, vedomosti.ocenka, gruppy.name AS gruppyName
	FROM vedomosti
    INNER JOIN ekzamenacionnyj_list ON ekzamenacionnyj_list.id = vedomosti.ekzamenacionnyj_list_id
    INNER JOIN predmety ON predmety.id = ekzamenacionnyj_list.predmety_id
    INNER JOIN abiturienty ON abiturienty.id = ekzamenacionnyj_list.abiturienty_id
    INNER JOIN gruppy ON gruppy.id = abiturienty.gruppy_id
    WHERE abiturienty.familiya LIKE 'Здончик' AND gruppy.name LIKE '10703216'

-- Запрос 6. Оценки абитуриента
DELIMITER $$
DROP PROCEDURE IF EXISTS `getOcenkiAbiturienta` $$
CREATE PROCEDURE getOcenkiAbiturienta(IN in_familiya varchar(256), IN in_groupName varchar(256))
BEGIN
SELECT abiturienty.name AS abiturientyName, abiturienty.familiya, predmety.name AS predmetyName, 
vedomosti.ocenka, gruppy.name AS gruppyName
	FROM vedomosti
    INNER JOIN ekzamenacionnyj_list ON ekzamenacionnyj_list.id = vedomosti.ekzamenacionnyj_list_id
    INNER JOIN predmety ON predmety.id = ekzamenacionnyj_list.predmety_id
    INNER JOIN abiturienty ON abiturienty.id = ekzamenacionnyj_list.abiturienty_id
    INNER JOIN gruppy ON gruppy.id = abiturienty.gruppy_id
    WHERE abiturienty.familiya LIKE in_familiya AND gruppy.name LIKE in_groupName;
END$$

CALL getOcenkiAbiturienta('Здончик', '10703216');


SELECT DISTINCT abiturienty.familiya
	FROM abiturienty
    ORDER BY abiturienty.familiya asc;
    
    
SELECT gruppy.id, gruppy.name
	FROM gruppy
    
    CALL getOcenkiAbiturienta('Здончик', '10706216');

    
    
    
-- Запрос 7. Получить расписание консультаций и экзаменов
SELECT tip_raspisaniya.name AS tipRaspisaniya, raspisanie.data_provedeniya AS dataProvedenia, raspisanie.auditoriya, abiturienty.familiya, gruppy.name AS gruppaName, predmety.name AS predmetName
	FROM tip_raspisaniya
    INNER JOIN raspisanie ON raspisanie.tip_raspisaniya_id = tip_raspisaniya.id
    INNER JOIN predmety ON predmety.id = raspisanie.predmety_id
    INNER JOIN potoki ON potoki.id = raspisanie.potoki_id
    INNER JOIN potok_grupp ON potok_grupp.potoki_id = potoki.id
    INNER JOIN gruppy ON gruppy.id = potok_grupp.gruppy_id
    INNER JOIN abiturienty ON abiturienty.gruppy_id = gruppy.id
    WHERE abiturienty.familiya LIKE 'Здончик' AND gruppy.name LIKE '10703216' AND predmety.name LIKE 'Белорусский язык'

-- Запрос 7. Получить расписание консультаций и экзаменов
DELIMITER $$
DROP PROCEDURE IF EXISTS `getRaspisanieConsultAndExzamenov` $$
CREATE PROCEDURE getRaspisanieConsultAndExzamenov(IN in_familiya varchar(256), IN in_groupName varchar(256), IN in_predmetName varchar(256))
BEGIN
SELECT tip_raspisaniya.name AS tipRaspisaniya, raspisanie.data_provedeniya AS dataProvedenia, raspisanie.auditoriya, 
abiturienty.familiya, gruppy.name AS gruppaName, predmety.name AS predmetName
	FROM tip_raspisaniya
    INNER JOIN raspisanie ON raspisanie.tip_raspisaniya_id = tip_raspisaniya.id
    INNER JOIN predmety ON predmety.id = raspisanie.predmety_id
    INNER JOIN potoki ON potoki.id = raspisanie.potoki_id
    INNER JOIN potok_grupp ON potok_grupp.potoki_id = potoki.id
    INNER JOIN gruppy ON gruppy.id = potok_grupp.gruppy_id
    INNER JOIN abiturienty ON abiturienty.gruppy_id = gruppy.id
    WHERE abiturienty.familiya LIKE in_familiya AND gruppy.name LIKE in_groupName AND predmety.name LIKE in_predmetName
	ORDER BY tip_raspisaniya.name asc;
END$$

CALL getRaspisanieConsultAndExzamenov('Здончик', '10703216', 'Белорусский язык');

role

SELECT DISTINCT abiturienty.familiya
	FROM abiturienty
    ORDER BY abiturienty.familiya asc;
    
    
SELECT gruppy.id, gruppy.name
	FROM gruppy
    
    
SELECT predmety.id, predmety.name
	FROM predmety

-- Запрос 8. Получить экзамены для группы
SELECT tip_raspisaniya.name AS tipRaspisaniya, raspisanie.auditoriya, raspisanie.data_provedeniya AS dataProvedenia, predmety.name AS predmetName, gruppy.name AS gruppaName
	FROM tip_raspisaniya
    INNER JOIN raspisanie ON raspisanie.tip_raspisaniya_id = tip_raspisaniya.id
    INNER JOIN predmety ON predmety.id = raspisanie.predmety_id
    INNER JOIN potoki ON potoki.id = raspisanie.potoki_id
    INNER JOIN potok_grupp ON potok_grupp.potoki_id = potoki.id
    INNER JOIN gruppy ON gruppy.id = potok_grupp.gruppy_id
    WHERE gruppy.name LIKE '10703216'

-- Запрос 8. Получить экзамены для группы
DELIMITER $$
DROP PROCEDURE IF EXISTS `getExzameny` $$
CREATE PROCEDURE getExzameny(IN in_groupName varchar(256))
BEGIN
SELECT tip_raspisaniya.name AS tipRaspisaniya, raspisanie.auditoriya, 
raspisanie.data_provedeniya AS dataProvedenia, predmety.name AS predmetName, 
gruppy.name AS gruppaName
	FROM tip_raspisaniya
    INNER JOIN raspisanie ON raspisanie.tip_raspisaniya_id = tip_raspisaniya.id
    INNER JOIN predmety ON predmety.id = raspisanie.predmety_id
    INNER JOIN potoki ON potoki.id = raspisanie.potoki_id
    INNER JOIN potok_grupp ON potok_grupp.potoki_id = potoki.id
    INNER JOIN gruppy ON gruppy.id = potok_grupp.gruppy_id
    WHERE gruppy.name LIKE in_groupName;
END$$

CALL getExzameny('10703216');


SELECT gruppy.id, gruppy.name
	FROM gruppy
    

-- Запрос 9. Список абитуриентов на заданный факультет
SELECT abiturienty.name AS abiturientName, abiturienty.familiya, fakultety.name AS fakultetName
	FROM fakultety
    INNER JOIN kafedry ON kafedry.fakultety_id = fakultety.id
    INNER JOIN gruppy ON gruppy.kafedry_id = kafedry.id
    INNER JOIN abiturienty ON abiturienty.gruppy_id = gruppy.id
    WHERE fakultety.name LIKE 'ФИТР'

-- Запрос 9. Список абитуриентов на заданный факультет
DELIMITER $$
DROP PROCEDURE IF EXISTS `getSpisokAbitNaFacultet` $$
CREATE PROCEDURE getSpisokAbitNaFacultet(IN in_facultetName varchar(256))
BEGIN
SELECT abiturienty.name AS abiturientName, abiturienty.familiya, fakultety.name AS fakultetName
	FROM fakultety
    INNER JOIN kafedry ON kafedry.fakultety_id = fakultety.id
    INNER JOIN gruppy ON gruppy.kafedry_id = kafedry.id
    INNER JOIN abiturienty ON abiturienty.gruppy_id = gruppy.id
    WHERE fakultety.name LIKE in_facultetName;
END$$

CALL getSpisokAbitNaFacultet('ФИТР');
    
    

SELECT fakultety.id, fakultety.name
	FROM fakultety;