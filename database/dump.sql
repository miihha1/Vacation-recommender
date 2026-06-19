SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS search_logs;
DROP TABLE IF EXISTS visits;
DROP TABLE IF EXISTS climates;
DROP TABLE IF EXISTS airports;
DROP TABLE IF EXISTS countries;
DROP TABLE IF EXISTS destinations;

CREATE TABLE destinations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    country_code CHAR(2) NOT NULL,
    country_name VARCHAR(120) NOT NULL,
    capital VARCHAR(120) NOT NULL,
    currency_code CHAR(3) NOT NULL,
    currency_name VARCHAR(80) NOT NULL,
    types VARCHAR(160) NOT NULL,
    flight_hours DECIMAL(3,1) NOT NULL,
    latitude DECIMAL(9,6) NOT NULL,
    longitude DECIMAL(9,6) NOT NULL,
    image_url VARCHAR(500) NOT NULL,
    summary TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE climates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    destination_id INT NOT NULL,
    month TINYINT NOT NULL,
    avg_min DECIMAL(4,1) NOT NULL,
    avg_max DECIMAL(4,1) NOT NULL,
    UNIQUE KEY uniq_climates_destination_month (destination_id, month),
    CONSTRAINT fk_climates_destination FOREIGN KEY (destination_id) REFERENCES destinations(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE countries (
    code CHAR(2) PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    capital VARCHAR(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE airports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(160) NOT NULL,
    iata CHAR(3) NOT NULL,
    latitude DECIMAL(9,6) NOT NULL,
    longitude DECIMAL(9,6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE visits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    visitor_hash VARCHAR(64) NOT NULL,
    user_agent VARCHAR(255) NULL,
    visited_at DATETIME NOT NULL,
    INDEX idx_visits_time (visited_at),
    INDEX idx_visits_hash_time (visitor_hash, visited_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE search_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    destination_id INT NOT NULL,
    travel_month TINYINT NOT NULL,
    days_count INT NOT NULL,
    types VARCHAR(160) NOT NULL,
    temperature_pref VARCHAR(20) NOT NULL,
    distance_pref VARCHAR(20) NOT NULL,
    searched_at DATETIME NOT NULL,
    INDEX idx_searches_time (searched_at),
    CONSTRAINT fk_search_logs_destination FOREIGN KEY (destination_id) REFERENCES destinations(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO countries (code, name, capital) VALUES
('ES', 'Španielsko', 'Madrid'),
('PT', 'Portugalsko', 'Lisabon'),
('IT', 'Taliansko', 'Rím'),
('GR', 'Grécko', 'Atény'),
('HR', 'Chorvátsko', 'Záhreb'),
('IS', 'Island', 'Reykjavík'),
('NO', 'Nórsko', 'Oslo'),
('FR', 'Francúzsko', 'Paríž'),
('GB', 'Spojené kráľovstvo', 'Londýn'),
('CZ', 'Česko', 'Praha'),
('MA', 'Maroko', 'Rabat'),
('TR', 'Turecko', 'Ankara'),
('JP', 'Japonsko', 'Tokio'),
('US', 'Spojené štáty', 'Washington');

INSERT INTO airports (name, iata, latitude, longitude) VALUES
('Vienna International Airport', 'VIE', 48.110300, 16.569700),
('Barcelona El Prat Airport', 'BCN', 41.297100, 2.078500),
('Lisbon Humberto Delgado Airport', 'LIS', 38.774200, -9.134200),
('Rome Fiumicino Airport', 'FCO', 41.800300, 12.238900),
('Athens International Airport', 'ATH', 37.936400, 23.944500),
('Split Airport', 'SPU', 43.538900, 16.298000),
('Keflavík International Airport', 'KEF', 63.985000, -22.605600),
('Oslo Gardermoen Airport', 'OSL', 60.193900, 11.100400),
('Paris Charles de Gaulle Airport', 'CDG', 49.009700, 2.547900),
('London Heathrow Airport', 'LHR', 51.470000, -0.454300),
('Václav Havel Airport Prague', 'PRG', 50.100800, 14.263200),
('Marrakesh Menara Airport', 'RAK', 31.606900, -8.036300),
('Istanbul Airport', 'IST', 41.275300, 28.751900),
('Tokyo Haneda Airport', 'HND', 35.549400, 139.779800),
('John F. Kennedy International Airport', 'JFK', 40.641300, -73.778100);

INSERT INTO destinations
(id, name, country_code, country_name, capital, currency_code, currency_name, types, flight_hours, latitude, longitude, image_url, summary) VALUES
(1, 'Barcelona', 'ES', 'Španielsko', 'Madrid', 'EUR', 'euro', 'beach,city,history', 2.4, 41.387400, 2.168600, 'https://images.unsplash.com/photo-1583422409516-2895a77efded?auto=format&fit=crop&w=1200&q=80', 'Pláže, architektúra a mestský rytmus v jednom kompaktnom pobyte.'),
(2, 'Madeira', 'PT', 'Portugalsko', 'Lisabon', 'EUR', 'euro', 'nature,activity,beach', 4.2, 32.760700, -16.959500, 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/Camara_de_Lobos_Madeira.jpg/1280px-Camara_de_Lobos_Madeira.jpg', 'Ostrov levád, útesov a mierneho počasia počas väčšiny roka.'),
(3, 'Rím', 'IT', 'Taliansko', 'Rím', 'EUR', 'euro', 'history,city', 1.7, 41.902800, 12.496400, 'https://images.unsplash.com/photo-1552832230-c0197dd311b5?auto=format&fit=crop&w=1200&q=80', 'Historické mesto s výborným jedlom a množstvom pamiatok na pešo.'),
(4, 'Kréta', 'GR', 'Grécko', 'Atény', 'EUR', 'euro', 'beach,history,nature', 2.5, 35.240100, 24.809300, 'https://images.unsplash.com/photo-1530841377377-3ff06c0ca713?auto=format&fit=crop&w=1200&q=80', 'Slnečný ostrov pre pláže, horské dediny a minojské pamiatky.'),
(5, 'Split', 'HR', 'Chorvátsko', 'Záhreb', 'EUR', 'euro', 'beach,history,city', 1.3, 43.508100, 16.440200, 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a7/Croatia.Split.Riva.jpg/1280px-Croatia.Split.Riva.jpg', 'Jadran, Diokleciánov palác a jednoduché výlety na ostrovy.'),
(6, 'Reykjavík', 'IS', 'Island', 'Reykjavík', 'ISK', 'islandská koruna', 'nature,activity,city', 4.1, 64.146600, -21.942600, 'https://images.unsplash.com/photo-1504829857797-ddff29c27927?auto=format&fit=crop&w=1200&q=80', 'Výborná základňa pre geotermálne kúpele, vodopády a severnú prírodu.'),
(7, 'Lofoty', 'NO', 'Nórsko', 'Oslo', 'NOK', 'nórska koruna', 'nature,activity', 4.8, 68.208900, 13.845600, 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=80', 'Dramatické ostrovy pre turistiku, fotografiu a pokoj mimo veľkých miest.'),
(8, 'Paríž', 'FR', 'Francúzsko', 'Paríž', 'EUR', 'euro', 'city,history', 2.0, 48.856600, 2.352200, 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&w=1200&q=80', 'Klasický mestský výlet s galériami, architektúrou a gastronómiou.'),
(9, 'Londýn', 'GB', 'Spojené kráľovstvo', 'Londýn', 'GBP', 'britská libra', 'city,history', 2.3, 51.507200, -0.127600, 'https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?auto=format&fit=crop&w=1200&q=80', 'Múzeá, divadlá, parky a rôznorodé štvrte na dlhší víkend.'),
(10, 'Praha', 'CZ', 'Česko', 'Praha', 'CZK', 'česká koruna', 'city,history', 0.8, 50.075500, 14.437800, 'https://images.unsplash.com/photo-1519677100203-a0e668c92439?auto=format&fit=crop&w=1200&q=80', 'Blízka historická destinácia s výbornou dostupnosťou a atmosférou.'),
(11, 'Marrákeš', 'MA', 'Maroko', 'Rabat', 'MAD', 'marocký dirham', 'history,city,activity', 4.0, 31.629500, -7.981100, 'https://images.unsplash.com/photo-1597212618440-806262de4f6b?auto=format&fit=crop&w=1200&q=80', 'Trhy, záhrady, riady a brána k výletom do pohoria Atlas.'),
(12, 'Antalya', 'TR', 'Turecko', 'Ankara', 'TRY', 'turecká líra', 'beach,history,activity', 2.7, 36.896900, 30.713300, 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/de/Kemer_beach%2C_Antalya.jpg/1280px-Kemer_beach%2C_Antalya.jpg', 'Teplé more, rezorty a antické pamiatky na pobreží Stredozemného mora.'),
(13, 'Tokio', 'JP', 'Japonsko', 'Tokio', 'JPY', 'japonský jen', 'city,history,activity', 13.0, 35.676200, 139.650300, 'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?auto=format&fit=crop&w=1200&q=80', 'Veľkomesto pre kultúru, jedlo, technológie a denné výlety vlakom.'),
(14, 'New York', 'US', 'Spojené štáty', 'Washington', 'USD', 'americký dolár', 'city,history,activity', 9.2, 40.712800, -74.006000, 'https://images.unsplash.com/photo-1485871981521-5b1fd3805eee?auto=format&fit=crop&w=1200&q=80', 'Ikonický mestský výlet s galériami, parkmi a silnou kultúrnou scénou.');

INSERT INTO climates (destination_id, month, avg_min, avg_max) VALUES
(1,1,6,14),(1,2,7,15),(1,3,9,17),(1,4,11,19),(1,5,15,23),(1,6,19,27),(1,7,22,30),(1,8,22,30),(1,9,19,27),(1,10,15,23),(1,11,10,18),(1,12,7,15),
(2,1,14,19),(2,2,14,19),(2,3,14,20),(2,4,15,21),(2,5,16,22),(2,6,18,24),(2,7,19,26),(2,8,20,27),(2,9,20,26),(2,10,18,24),(2,11,16,22),(2,12,15,20),
(3,1,4,13),(3,2,5,14),(3,3,7,17),(3,4,9,20),(3,5,13,24),(3,6,17,29),(3,7,20,32),(3,8,20,32),(3,9,17,28),(3,10,13,23),(3,11,9,18),(3,12,5,14),
(4,1,9,15),(4,2,9,16),(4,3,10,18),(4,4,12,21),(4,5,16,25),(4,6,20,29),(4,7,23,31),(4,8,23,31),(4,9,20,28),(4,10,17,24),(4,11,13,20),(4,12,10,16),
(5,1,5,11),(5,2,6,12),(5,3,8,15),(5,4,11,18),(5,5,15,23),(5,6,19,27),(5,7,22,30),(5,8,22,30),(5,9,18,26),(5,10,14,21),(5,11,10,16),(5,12,6,12),
(6,1,-1,3),(6,2,-1,3),(6,3,0,4),(6,4,2,7),(6,5,5,10),(6,6,8,13),(6,7,10,15),(6,8,9,14),(6,9,7,11),(6,10,3,7),(6,11,1,4),(6,12,-1,3),
(7,1,-2,2),(7,2,-2,2),(7,3,-1,3),(7,4,1,6),(7,5,5,10),(7,6,8,13),(7,7,11,16),(7,8,10,15),(7,9,7,11),(7,10,3,7),(7,11,0,4),(7,12,-2,2),
(8,1,3,8),(8,2,3,9),(8,3,6,13),(8,4,8,16),(8,5,11,20),(8,6,14,23),(8,7,16,26),(8,8,16,26),(8,9,13,22),(8,10,10,17),(8,11,6,11),(8,12,4,8),
(9,1,3,8),(9,2,3,8),(9,3,5,11),(9,4,7,15),(9,5,10,18),(9,6,13,21),(9,7,15,23),(9,8,15,23),(9,9,12,20),(9,10,9,15),(9,11,6,11),(9,12,4,8),
(10,1,-3,2),(10,2,-2,4),(10,3,1,9),(10,4,4,15),(10,5,9,20),(10,6,12,23),(10,7,14,25),(10,8,13,25),(10,9,10,20),(10,10,5,14),(10,11,1,7),(10,12,-2,3),
(11,1,6,19),(11,2,8,21),(11,3,10,24),(11,4,12,26),(11,5,15,30),(11,6,19,35),(11,7,22,38),(11,8,22,38),(11,9,19,33),(11,10,15,28),(11,11,10,23),(11,12,7,20),
(12,1,6,15),(12,2,7,16),(12,3,9,19),(12,4,12,22),(12,5,16,27),(12,6,20,32),(12,7,24,35),(12,8,24,35),(12,9,20,31),(12,10,16,26),(12,11,11,21),(12,12,8,16),
(13,1,1,10),(13,2,2,11),(13,3,5,14),(13,4,10,19),(13,5,15,23),(13,6,19,26),(13,7,23,30),(13,8,24,31),(13,9,21,27),(13,10,15,22),(13,11,9,17),(13,12,4,12),
(14,1,-3,4),(14,2,-2,5),(14,3,2,10),(14,4,7,17),(14,5,12,22),(14,6,18,27),(14,7,21,30),(14,8,20,29),(14,9,16,25),(14,10,10,18),(14,11,5,12),(14,12,0,7);

SET FOREIGN_KEY_CHECKS=1;
