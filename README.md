# TrustIndex – Medior PHP Fejlesztői Tesztfeladat

## Projekt leírás

Ez a projekt a TrustIndex Medior PHP Fejlesztői tesztfeladatának megoldása Symfony 7 és Doctrine ORM használatával.

Az alkalmazás lehetőséget biztosít céges vélemények rögzítésére, megjelenítésére, keresésére, rendezésére és statisztikai összesítésére.

### Főbb funkciók

* Új vélemény rögzítése Symfony Form segítségével
* Vélemények listázása
* Cégnév szerinti keresés
* Rendezés cégnév, értékelés és dátum szerint
* Lapozás (KnpPaginatorBundle)
* Oldalanként megjelenő elemek számának kiválasztása
* Vélemény részletező oldal
* Cégenkénti statisztikák
* CSV export
* Responsive Bootstrap 5 felület
* Dark Mode
* Unit és Functional tesztek
* Doctrine Fixtures demó adatok létrehozásához

---

# Használt technológiák

* PHP 8.4
* Symfony 7
* Doctrine ORM
* Twig
* Bootstrap 5
* Bootstrap Icons
* KnpPaginatorBundle
* Doctrine Fixtures Bundle
* PHPUnit

---

# Telepítés

## Függőségek telepítése

```bash
composer install
```

---

## Adatbázis létrehozása

A `.env` fájlban állítsd be a megfelelő adatbázis kapcsolatot, majd futtasd:

```bash
php bin/console doctrine:database:create
```

---

## Migrációk futtatása

```bash
php bin/console doctrine:migrations:migrate
```

---

## Demó adatok betöltése

```bash
php bin/console doctrine:fixtures:load
```

Ez létrehoz több céget és hozzájuk tartozó teszt véleményeket.

---

## Fejlesztői szerver indítása

Symfony CLI használatával:

```bash
symfony serve
```


---

# Tesztek futtatása

Teszt adatbázis létrehozása
```bash
php bin/console doctrine:database:create --env=test
php bin/console doctrine:migrations:migrate --env=test
```

```bash
php bin/phpunit
```

---

# Projekt felépítése

```
src/
 ├── Controller/
 ├── Entity/
 ├── Repository/
 ├── Form/
 └── DataFixtures/

templates/
tests/
migrations/
```

---

# Megvalósított extrák

A kötelező feladatokon felül az alábbi funkciók kerültek megvalósításra:

* Dark Mode kapcsoló
* CSV export
* Doctrine Fixtures demó adatokhoz
* Bootstrap 5 alapú modern felület
* Vélemények lapozása KnpPaginator használatával
* Cégnév szerinti keresés
* Rendezhető táblázat
* Oldalanként választható elemszám
* Egy e-mail cím ugyanahhoz a céghez csak egy véleményt küldhet be
* 404-es hibakezelés

---

# Munkaidő napló

| Feladat                                      |     Idő |
| -------------------------------------------- | ------: |
| Symfony projekt létrehozása és konfigurálása | 0,5 óra |
| Doctrine Entity és migrációk                 | 0,5 óra |
| Vélemény beküldő űrlap                       |   1 óra |
| Vélemények listázása                         | 0,5 óra |
| Céges statisztikák                           | 0,5 óra |
| Keresés, rendezés és lapozás                 | 0,5 óra |
| CSV export                                   | 0,2 óra |
| Dark Mode                                    | 0,2 óra |
| Doctrine Fixtures                            | 0,3 óra |
| Unit tesztek                                 | 0,3 óra |
| Functional tesztek                           | 0,3 óra |
| Hibakezelés, validációk és finomhangolás     | 0,6 óra |
| Dokumentáció és README                       | 0,5 óra |

**Összes ráfordított idő: ~6 óra**

---

# Megjegyzés

A projekt célja egy letisztult, Symfony ajánlásait követő megoldás készítése volt, amely jól elkülöníti az üzleti logikát, a megjelenítést és az adatkezelést, valamint bemutatja a modern Symfony komponensek (Forms, Doctrine, Twig, PHPUnit) használatát.

## Jövőbeni fejlesztési ötletek

A projekt a feladatkiírásnak megfelelően készült el, ugyanakkor számos irányban továbbfejleszthető.

* **Egységes cégkezelés:** A jelenlegi megoldásban a cégnevek szándékosan kis- és nagybetű érzékenyek, így például a `Google` és a `google` külön cégként kezelhető. Ez a vélemény rögzítésénél és a cégstatisztikákban is érvényesül, míg a kereső felhasználóbarát módon továbbra is mindkét változatot megtalálja. Egy éles rendszerben célszerű lenne egy központi, adminisztrációs felületen kezelhető céglista bevezetése, ahol a vélemények már egy `company_id` alapján kapcsolódnának a cégekhez. Ez kiküszöbölné az elgépeléseket és biztosítaná az adatok konzisztenciáját.

* **JavaScript keretrendszer bevezetése:** A felhasználói élmény tovább javítható lenne egy modern frontend keretrendszer (például React) alkalmazásával. Ennek segítségével gyorsabb, interaktívabb felület, dinamikus keresés, kliensoldali szűrés és fejlettebb komponens-alapú felépítés valósítható meg.

* **Statikus erőforrások kiszervezése:** A jelenlegi projektben néhány CSS és JavaScript közvetlenül a Twig sablonokban található. Nagyobb alkalmazás esetén ezek külön CSS és JavaScript fájlokba szervezhetők, ami átláthatóbb projektstruktúrát, egyszerűbb karbantarthatóságot és jobb újrafelhasználhatóságot eredményez.

* **Többnyelvű támogatás:** A Symfony Translation komponensének alkalmazásával az alkalmazás könnyen többnyelvűvé tehető. A felület szövegei nyelvi fájlokba szervezhetők, így egyszerűen támogatható lenne például a magyar és az angol nyelv is.
