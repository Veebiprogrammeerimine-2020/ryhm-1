# Ryhm-1
veebiprogrammeerimise kursuse rühm 1

Siia kogume kõik veebiprogrammeerimise kursusega seonduva info.

### Ühendus Greeny'sse

* Windows (WinSCP) | [VIDEO](https://youtu.be/kg5NAsRQAJ8)

    * [Putty tunneli tegemise õpetus (eesti keeles, ekraanitõmmistega)](http://minitorn.tlu.ee/~jaagup/kool/java/kursused/09/veebipr/naited/greenytunnel/greenytunnel.pdf)
    
     * [ligipääs Greenyle Windows, macOS ja Linuxi masinatest, Viktor Lillemäe](https://github.com/vktrl/TLU-Server-Guide)

* Mac OS | [VIDEO](https://youtu.be/RJc-Gvpn9M4)
```
1. open Terminal app

2. write:
ssh university_username@lin2.tlu.ee -L 5555:greeny.cs.tlu.ee:80
3. then write TLU password

    Now you can access greeny from browser localhost:5555

3. open new tab in Terminal (cmd+t) and write:
ssh university_username@lin2.tlu.ee -L 2222:greeny.cs.tlu.ee:22
4. then write TLU password

5. now open FTP client (CyberDuck, FileZilla, Coda) for example and connect to greeny via SFTP
    host: localhost or some require 127.0.0.1 (127.0.0.1 = localhost)
    port: 2222
    username: your_greeny_username
    password: your_greeny_password

6. choose one Terminal tab and connect to greeny via ssh, write:
ssh greeny_username@greeny.cs.tlu.ee
7. then enter your Greeny username password
    ls             – to view files and folders in current path
    cd folder_name - to enter folder
    cd ..          – to exit folder to previous path

```

### Kodus greeny serveris olevate veebilehtede brauseris vaatamiseks
Vaadake tlu.ee kodulehel peamenüüs "Ülikool" tugiüksuste alajaotusest infotehnoloogia osakonda. Selle lehelt leiate vasakul menüüs "juhendid ja abimaterjalid" (https://www.tlu.ee/taxonomy/term/79/juhendid-ja-abimaterjalid) ning nende seas uurige "Tallinna Ülikooli veebipuhvri teenuse kasutamine väljaspool ülikooli arvutivõrku"!
