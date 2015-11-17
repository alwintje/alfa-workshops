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

De SQL voor de tabellen:
------------

    CREATE TABLE IF NOT EXISTS `aw_settings` (
    `id` int(3) NOT NULL,
      `setting_name` varchar(30) NOT NULL,
      `email_hosts` text NOT NULL,
      `active` tinyint(1) NOT NULL DEFAULT '0'
    ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;


    INSERT INTO `aw_settings` (`id`, `setting_name`, `email_hosts`, `active`) VALUES
    (1, 'default', 'student.alfa-college.nl,alfa-college.nl', 1);

------------

    CREATE TABLE IF NOT EXISTS `aw_users` (
    `id` int(5) NOT NULL,
      `email` varchar(100) NOT NULL,
      `firstname` varchar(30) NOT NULL,
      `lastname` varchar(30) NOT NULL,
      `password` varchar(32) NOT NULL
    ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;


    INSERT INTO `aw_users` (`id`, `email`, `firstname`, `lastname`, `password`) VALUES
    (1, 'a.kroesen@student.alfa-college.nl', 'Alwin', 'Kroesen', '5f64d4a67b4b6bc5ea65c958637f7840');


Wachtwoorden
------------

    Wachtwoord wordt opgeslagen op de volgende manier:
    md5("wachtwoord"."email")   -- In de class security is een functie makePass()!
