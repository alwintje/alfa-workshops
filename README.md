Ik heb een aantal dingen gedaan.
De database werkt!

Nu moet je een database hebben met de naam: school
Daarin moet je een tabel aanmaken met de naam: aw_users
Die tabel moet 5 colums hebben:
- id = int(5)
- email = varchar(100)
- firstname = varchar(30)
- lastname = varchar(30)
- password = varchar(32)
    
---------------
- -Wachtwoord wordt opgeslagen op de volgende manier:
    md5("wachtwoord"+"email")   --natuurlijk is de + een punt in php
