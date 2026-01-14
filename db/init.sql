CREATE TABLE IF NOT EXISTS offices (
  id INT AUTO_INCREMENT PRIMARY KEY,
  organization VARCHAR(255) NOT NULL,
  street_address VARCHAR(255) NOT NULL,
  latitude DECIMAL(10,8) NOT NULL,
  longitude DECIMAL(11,8) NOT NULL
);

TRUNCATE TABLE offices;

INSERT INTO offices (organization, street_address, latitude, longitude) VALUES
('Headquarters - Sacramento', '1120 N Street, Sacramento, CA 95814', 38.5767, -121.4934),
('District 1 - Eureka', '1656 Union Street, Eureka, CA 95501', 40.8021, -124.1637),
('District 2 - Redding', '1657 Riverside Drive, Redding, CA 96001', 40.5865, -122.3917),
('District 3 - Marysville / Sacramento', '703 B Street, Marysville, CA 95901', 39.1457, -121.5914),
('District 4 - Bay Area / Oakland', '111 Grand Ave, Oakland, CA 94612', 37.8113, -122.2625),
('District 5 - San Luis Obispo / Santa Barbara', '50 Higuera Street, San Luis Obispo, CA 93401', 35.2797, -120.6649),
('District 6 - Fresno / Bakersfield', '1352 W. Olive Avenue, Fresno, CA 93728', 36.7468, -119.7726),
('District 7 - Los Angeles', '100 South Main Street, Los Angeles, CA 90012', 34.0537, -118.2428),
('District 8 - San Bernardino / Riverside', '464 W. 4th Street, San Bernardino, CA 92401', 34.1083, -117.2898),
('District 9 - Bishop', '500 South Main Street, Bishop, CA 93514', 37.3635, -118.3951),
('District 10 - Stockton', '1976 East Dr. Martin Luther King Jr. Blvd., Stockton, CA 95205', 37.9577, -121.2908);
