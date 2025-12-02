-- SEUP Manual Database Schema
-- Database: 8core_manual
-- Charset: utf8mb4

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

-- Demo data for manual sections
INSERT INTO `manual_sections` (`title`, `slug`, `content`, `sort_order`) VALUES
('Uvod u SEUP', 'uvod', '<p>SEUP (Sistem za Elektronsku Upravnu Podrsku) je moderan sustav za upravljanje administrativnim procesima.</p><p>Ovaj manual ce vas provesti kroz sve funkcionalnosti sustava.</p>', 1),
('Osnove koristenja', 'osnove', '<h3>Prijava u sustav</h3><p>Za pristup sustavu potrebno je koristiti vase korisnicko ime i lozinku.</p><h3>Osnovna navigacija</h3><p>Navigacija se nalazi na lijevoj strani ekrana. Kliknite na stavke menija za pristup pojedinim modulima.</p>', 2),
('Upravljanje dokumentima', 'dokumenti', '<h3>Dodavanje novog dokumenta</h3><p>1. Kliknite na gumb "Novi dokument"<br>2. Popunite obavezna polja<br>3. Prilozte datoteke ako je potrebno<br>4. Kliknite "Spremi"</p><h3>Pretraga dokumenata</h3><p>Koristite traku za pretragu na vrhu stranice za brzo pronalazenje dokumenata.</p>', 3),
('Izvjestaji', 'izvjestaji', '<p>Modul izvjestaja omogucuje generiranje razlicitih vrsta izvjestaja:</p><ul><li>Dnevni izvjestaji</li><li>Mjesecni izvjestaji</li><li>Godisnji izvjestaji</li><li>Prilagodeni izvjestaji</li></ul>', 4),
('Podrska', 'podrska', '<p>Za tehnicku podrsku kontaktirajte:</p><p>Email: support@8core.com<br>Telefon: +385 1 234 5678<br>Radno vrijeme: Pon-Pet 8:00-16:00</p>', 5);

-- Demo data for notifications
INSERT INTO `notifications` (`title`, `message`, `type`, `expires_at`) VALUES
('Dobrodosli u SEUP', 'Hvala sto koristite SEUP sustav. Pregledajte User Manual za detalje o svim funkcionalnostima.', 'success', NULL),
('Zakazano odrzavanje', 'Sustav ce biti nedostupan u subotu 07.12.2025. od 02:00 do 06:00 radi odrzavanja.', 'warning', '2025-12-07 06:00:00'),
('Nova verzija', 'Dostupna je nova verzija SEUP-a v2.1 sa poboljsanjima u brzini i sigurnosti.', 'info', '2025-12-31 23:59:59'),
('Sigurnosno upozorenje', 'Molimo promijenite lozinku ako je niste mijenjali duze od 90 dana.', 'warning', NULL);
