# wp-geo-post

Admin:
- be lehet állítani hogy melyik post type-nál jelenjen meg a meta box
- metabox-ban meg lehet adni a min és max latitude es longitude értékeket

Front end:
- Shortcode:
    - [wpgeopost id="NUMBER" count="NUMBER" list-type="post,page..."]
    id = templateben használjuk ha szükséges
    *count = hány postot listázzunk
    list-type = post type nevek vesszővel felsorolva, [defaul: config]


Mükődés:
- adatokat performance szempont miatt külön táblában tároljuk
- minden oldal letöltésnél frissül a tárolt cookie , 1 nap valid idővel