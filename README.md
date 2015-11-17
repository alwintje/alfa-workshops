

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


    CREATE TABLE IF NOT EXISTS `aw_users` (
    `id` int(5) NOT NULL,
      `email` varchar(100) NOT NULL,
      `firstname` varchar(30) NOT NULL,
      `lastname` varchar(30) NOT NULL,
      `password` varchar(32) NOT NULL
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;



Wachtwoorden
------------

    Wachtwoord wordt opgeslagen op de volgende manier:
    md5("wachtwoord"."email")   -- In de class security is een functie makePass()!


