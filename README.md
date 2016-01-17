# wp-geo-post

Admin:
- be lehet állítani hogy melyik post type-nál jelenjen meg a meta box
- metabox-ban meg lehet adni a min és max latitude es longitude értékeket

Front end:
- Shortcode:
    - [wpgeopost id="CHAR" count="NUMBER" list-type="post,page..."]
    id = templateben használjuk ha szükséges és template fájl név generálásnál
    *count = hány postot listázzunk
    list-type = post type nevek vesszővel felsorolva, [defaul: config]

- rendszer autó berakja egy kis js-t ami rögtön el is kéri az engedélyt


Template themeből:
- wp-geo-post-shortcode-not-found.php - ha nincs találat vagy nincs gps adat
- wp-geo-post-shortcode-list-SHORTCODEID.php - paraméterben megadott id-val a lista template
- wp-geo-post-list-shortcode.php - lista template például: wp-geo-post/templates/wp-geo-post-shortcode-list.php

Mükődés:
- adatokat performance szempont miatt külön táblában tároljuk
- minden oldal letöltésnél frissül a tárolt cookie , 1 nap valid idővel

TODO:
- server alapú geolocation

Tested:
- Simple WP 4.4.1 install