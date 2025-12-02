-- SEUP Manual Database Schema
-- Database: 8core_manual
-- Charset: utf8mb4
-- Auto-run on index.php or admin/index.php access

CREATE TABLE IF NOT EXISTS `manual_sections` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `content` TEXT NOT NULL,
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_active` (`is_active`),
  INDEX `idx_slug` (`slug`),
  INDEX `idx_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `type` ENUM('info', 'warning', 'success', 'error') DEFAULT 'info',
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `expires_at` TIMESTAMP NULL DEFAULT NULL,
  INDEX `idx_active` (`is_active`),
  INDEX `idx_type` (`type`),
  INDEX `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Demo data for manual sections (only insert if table is empty)
INSERT INTO `manual_sections` (`title`, `slug`, `content`, `sort_order`)
SELECT * FROM (SELECT
  'Uvod u SEUP' as title,
  'uvod' as slug,
  '<p>SEUP (Sistem za Elektronsku Upravnu Podrsku) je moderan sustav za upravljanje administrativnim procesima.</p><p>Ovaj manual ce vas provesti kroz sve funkcionalnosti sustava.</p>' as content,
  1 as sort_order
) AS tmp
WHERE NOT EXISTS (
  SELECT slug FROM `manual_sections` WHERE slug = 'uvod'
) LIMIT 1;

INSERT INTO `manual_sections` (`title`, `slug`, `content`, `sort_order`)
SELECT * FROM (SELECT
  'Osnove koristenja' as title,
  'osnove' as slug,
  '<h3>Prijava u sustav</h3><p>Za pristup sustavu potrebno je koristiti vase korisnicko ime i lozinku.</p><h3>Osnovna navigacija</h3><p>Navigacija se nalazi na lijevoj strani ekrana. Kliknite na stavke menija za pristup pojedinim modulima.</p>' as content,
  2 as sort_order
) AS tmp
WHERE NOT EXISTS (
  SELECT slug FROM `manual_sections` WHERE slug = 'osnove'
) LIMIT 1;

INSERT INTO `manual_sections` (`title`, `slug`, `content`, `sort_order`)
SELECT * FROM (SELECT
  'Upravljanje dokumentima' as title,
  'dokumenti' as slug,
  '<h3>Dodavanje novog dokumenta</h3><p>1. Kliknite na gumb "Novi dokument"<br>2. Popunite obavezna polja<br>3. Prilozte datoteke ako je potrebno<br>4. Kliknite "Spremi"</p><h3>Pretraga dokumenata</h3><p>Koristite traku za pretragu na vrhu stranice za brzo pronalazenje dokumenata.</p>' as content,
  3 as sort_order
) AS tmp
WHERE NOT EXISTS (
  SELECT slug FROM `manual_sections` WHERE slug = 'dokumenti'
) LIMIT 1;

INSERT INTO `manual_sections` (`title`, `slug`, `content`, `sort_order`)
SELECT * FROM (SELECT
  'Izvjestaji' as title,
  'izvjestaji' as slug,
  '<p>Modul izvjestaja omogucuje generiranje razlicitih vrsta izvjestaja:</p><ul><li>Dnevni izvjestaji</li><li>Mjesecni izvjestaji</li><li>Godisnji izvjestaji</li><li>Prilagodeni izvjestaji</li></ul>' as content,
  4 as sort_order
) AS tmp
WHERE NOT EXISTS (
  SELECT slug FROM `manual_sections` WHERE slug = 'izvjestaji'
) LIMIT 1;

INSERT INTO `manual_sections` (`title`, `slug`, `content`, `sort_order`)
SELECT * FROM (SELECT
  'Podrska' as title,
  'podrska' as slug,
  '<p>Za tehnicku podrsku kontaktirajte:</p><p>Email: support@8core.com<br>Telefon: +385 1 234 5678<br>Radno vrijeme: Pon-Pet 8:00-16:00</p>' as content,
  5 as sort_order
) AS tmp
WHERE NOT EXISTS (
  SELECT slug FROM `manual_sections` WHERE slug = 'podrska'
) LIMIT 1;

-- Demo data for notifications (only insert if specific notification doesn't exist)
INSERT INTO `notifications` (`title`, `message`, `type`, `expires_at`)
SELECT * FROM (SELECT
  'Dobrodosli u SEUP' as title,
  'Hvala sto koristite SEUP sustav. Pregledajte User Manual za detalje o svim funkcionalnostima.' as message,
  'success' as type,
  NULL as expires_at
) AS tmp
WHERE NOT EXISTS (
  SELECT title FROM `notifications` WHERE title = 'Dobrodosli u SEUP'
) LIMIT 1;

INSERT INTO `notifications` (`title`, `message`, `type`, `expires_at`)
SELECT * FROM (SELECT
  'Zakazano odrzavanje' as title,
  'Sustav ce biti nedostupan u subotu 07.12.2025. od 02:00 do 06:00 radi odrzavanja.' as message,
  'warning' as type,
  '2025-12-07 06:00:00' as expires_at
) AS tmp
WHERE NOT EXISTS (
  SELECT title FROM `notifications` WHERE title = 'Zakazano odrzavanje'
) LIMIT 1;

INSERT INTO `notifications` (`title`, `message`, `type`, `expires_at`)
SELECT * FROM (SELECT
  'Nova verzija' as title,
  'Dostupna je nova verzija SEUP-a v2.1 sa poboljsanjima u brzini i sigurnosti.' as message,
  'info' as type,
  '2025-12-31 23:59:59' as expires_at
) AS tmp
WHERE NOT EXISTS (
  SELECT title FROM `notifications` WHERE title = 'Nova verzija'
) LIMIT 1;

INSERT INTO `notifications` (`title`, `message`, `type`, `expires_at`)
SELECT * FROM (SELECT
  'Sigurnosno upozorenje' as title,
  'Molimo promijenite lozinku ako je niste mijenjali duze od 90 dana.' as message,
  'warning' as type,
  NULL as expires_at
) AS tmp
WHERE NOT EXISTS (
  SELECT title FROM `notifications` WHERE title = 'Sigurnosno upozorenje'
) LIMIT 1;
