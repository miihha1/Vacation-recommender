# WEBTE2 Zadanie 4 - Kam na dovolenku?

Laravel aplikácia, ktorá odporúča dovolenkové destinácie podľa termínu, dĺžky pobytu, typu dovolenky, teploty a vzdialenosti letu z Viedne.

## Použité technológie

- PHP 8.5
- Laravel 13
- MySQL/MariaDB
- Blade, CSS, JavaScript
- Chart.js na grafy
- Lucide icons na ikony

## Použité externé API

- GeoNames flags: `https://www.geonames.org/flags/x/{iso}.gif`
  - zobrazenie vlajky krajiny podľa dvojpísmenového ISO kódu.
- Frankfurter API: `https://api.frankfurter.app/latest`
  - aktuálny kurz cudzej meny voči euru.
- Open-Meteo Forecast API: `https://api.open-meteo.com/v1/forecast`
  - aktuálna predpoveď počasia pre súradnice destinácie.

## Funkcie podľa zadania

- Vyhľadávací formulár:
  - mesiac alebo dátumový rozsah,
  - počet dní,
  - typ dovolenky,
  - preferovaná teplota,
  - vzdialenosť letu z Viedne.
- Výsledky zoradené podľa zhody s preferenciami.
- Pri každej destinácii je vysvetlené, prečo bola odporúčaná.
- Detail destinácie:
  - priemerná mesačná teplota, minimum, maximum,
  - aktuálna predpoveď, ak je dostupná,
  - najbližšie letisko,
  - vlajka, krajina a hlavné mesto,
  - mena a kurz voči euru,
  - automaticky generované odporúčanie.
- Porovnanie dvoch destinácií.
- Štatistiky:
  - celkové a unikátne návštevy za posledných 60 minút,
  - návštevnosť podľa dennej doby,
  - tabuľka vyhľadávaných destinácií so sortable stĺpcami,
  - graf preferencií návštevníkov.

IP adresa sa do databázy neukladá. Pre unikátne návštevy sa používa HMAC hash IP adresy s Laravel `APP_KEY`.

## Databázový model

- `destinations` - destinácie, typy dovolenky, mena, poloha, letová vzdialenosť.
- `climates` - priemerné mesačné minimá a maximá.
- `countries` - štát, ISO kód a hlavné mesto.
- `airports` - letiská pre výpočet najbližšieho letiska.
- `visits` - návštevy portálu bez uloženia IP adresy.
- `search_logs` - uložené výsledky vyhľadávania pre štatistiky.

Model je definovaný aj v Laravel migrácii:

```bash
database/migrations/2026_05_04_000001_create_vacation_tables.php
```

SQL dump je v súbore:

```bash
database/dump.sql
```

## Nasadenie

1. Nahrajte priečinok `dovolenka` na server.
2. Nastavte document root na `dovolenka/public`.
3. Upravte `.env`:

```env
APP_URL=https://node21.webte.fei.stuba.sk/dovolenka/public
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=webte2_1
DB_USERNAME=xadamenkom
DB_PASSWORD=...
```

4. Nainštalujte závislosti:

```bash
composer install --no-dev
php artisan key:generate
```

5. Importujte databázu:

```bash
mysql -u xadamenkom -p webte2_1 < database/dump.sql
```

Alternatívne:

```bash
php artisan migrate:fresh --seed
```

## Požiadavky servera

Pre čistú Laravel inštaláciu sú potrebné najmä PHP rozšírenia:

- `pdo_mysql`
- `mbstring`
- `dom`
- `xml`
- `xmlwriter`
- `curl`
- `zip`

Na aktuálnom VPS chýbajú niektoré rozšírenia, preto je v projekte malý polyfill pre `mb_split()`. Na štandardnom Laravel prostredí nie je potrebný.

## Odovzdanie

Do ZIP archívu nepatria adresáre:

- `vendor`
- `node_modules`

Odovzdávajú sa zdrojové súbory, `composer.json`, `composer.lock`, `package.json`, SQL dump, README a Nginx konfigurácia `nginx.conf`.
