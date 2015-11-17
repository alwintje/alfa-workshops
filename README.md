Ik heb een aantal dingen gedaan.
De database werkt!

- Nu moet je een database hebben met de naam: school
- Daarin moet je een tabel aanmaken met de naam: aw_users
- Die tabel moet 5 colums hebben:
    - id = int(5)
    - email = varchar(100)
    - firstname = varchar(30)
    - lastname = varchar(30)
    - password = varchar(32)

- Je moet nog een tabel hebben met de naam: aw_settings
- Die tabel moet 3 colums hebben:
    - id = int(5)
    - setting_name = varchar(30)
    - email_hosts = text
    - active = boolean of tinyint(1)
- De SQL hiervoor:

    CREATE TABLE IF NOT EXISTS `aw_settings` (
    `id` int(3) NOT NULL,
      `setting_name` varchar(30) NOT NULL,
      `email_hosts` text NOT NULL,
      `active` tinyint(1) NOT NULL DEFAULT '0'
    ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;


    INSERT INTO `aw_settings` (`id`, `setting_name`, `email_hosts`, `active`) VALUES
    (1, 'default', 'student.alfa-college.nl,alfa-college.nl', 1);


------------

    Wachtwoord wordt opgeslagen op de volgende manier:
    md5("wachtwoord"."email")   -- In de class security is een functie makePass()!
