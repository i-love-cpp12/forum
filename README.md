To prosta, nowoczesna aplikacja forum, gdzie ludzie mogą normalnie pogadać i wymieniać się wiedzą. Możesz tu dodawać posty z tytułem, treścią i przypisanymi kategoriami. Do każdego posta da się pisać komentarze, więc rozmowy mogą się rozkręcać w wątki. Są też reakcje w postaci like’ów i dislike’ów, żeby łatwo pokazać co się podoba, a co nie. Logowanie i rejestracja działają na tokenach, więc każdy ma swoje konto i sesję. Zalogowani użytkownicy mogą tworzyć posty i ogarniać swój profil. Strona ma dynamiczne elementy jak dropdowny do kategorii i sortowania. Wszystko jest pobierane z API i od razu renderowane na stronie. Można filtrować i sortować posty według różnych opcji.

Startowe dane forum\backend\db\data\startDataForum.sql
Erd forum\backend\db\schema\erd.png
Opis normalizacji forum\backend\db\schema\normalization.txt
API forum\backend\api.txt
Statystyki forum\stats.png

Aby uruchomic należy: rozpakować zip w folderze C:/xampp/htdocs  -> nazwać folder forum o ile juz nie jest tak nazwany -> uruchomić C:/xampp/xampp-control.exe a w nim uruchomić apache i mysql -> uruchomić cmd (win + r) -> wykonać 'copy "C:\xampp\htdocs\forum\backend\db\data\startDataForum.sql" "C:\xampp\mysql\bin\"' -> wykonać 'cd C:\xampp\mysql\bin' -> wykonać 'mysql -u root' -> wykonać 'CREATE DATABASE forum;' -> wykonać ctrl + c -> wykonać 'mysql -u root forum < startDataForum.sql' -> wejść w przeglądarce na http://localhost/forum/index.html

konto admin:
    email: admin@forum.com
    hasło: Forum#123


github: https://github.com/i-love-cpp12/forum.git

PROJEKT NIE SKOŃCZONY (js)