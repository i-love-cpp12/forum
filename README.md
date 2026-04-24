To prosta, nowoczesna aplikacja forum, gdzie ludzie mogą normalnie pogadać i wymieniać się wiedzą. Możesz tu dodawać posty z tytułem, treścią i przypisanymi kategoriami. Do każdego posta da się pisać komentarze, więc rozmowy mogą się rozkręcać w wątki. Są też reakcje w postaci like’ów i dislike’ów, żeby łatwo pokazać co się podoba, a co nie. Logowanie i rejestracja działają na tokenach, więc każdy ma swoje konto i sesję. Zalogowani użytkownicy mogą tworzyć posty i ogarniać swój profil. Strona ma dynamiczne elementy jak dropdowny do kategorii i sortowania. Wszystko jest pobierane z API i od razu renderowane na stronie. Można filtrować i sortować posty według różnych opcji.

Startowe dane forum\backend\db\data\startDataForum.sql
Erd forum\backend\db\schema\erd.png
API forum\backend\api.txt
Statystyki forum\stats.png

Aby uruchomic należy: rozpakować zip w folderze htdocs  -> nazwać folder forum o ile juz nie jest tak nazwany -> uruchomić xampp apache i mysql -> wejść w przeglądarce na http://localhost/forum/index.html -> *zalogować się

Aby się zalogowac należy: f12 -> console -> wkleić: localStorage.setItem("token", "2a4a27b4685d2977cd45e9cc3fdea31d7430727f96f56828a227e3ad63f038ea") -> odświerzyć

github: https://github.com/i-love-cpp12/forum.git

PROJEKT NIE SKOŃCZONY (js)