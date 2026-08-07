-- ============================================================
-- Migration: real partner hospitals + verified donation flow
-- Run this once in phpMyAdmin's SQL tab (safe to run on the
-- existing bloodconnect database - it only adds new things).
-- ============================================================

CREATE TABLE IF NOT EXISTS hospitals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    location VARCHAR(150) DEFAULT '',
    type VARCHAR(30) DEFAULT '',
    status VARCHAR(30) DEFAULT 'open',
    status_label VARCHAR(50) DEFAULT 'Open',
    rating VARCHAR(10) DEFAULT '',
    description TEXT,
    blood_groups VARCHAR(100) DEFAULT '',
    phone VARCHAR(50) DEFAULT '',
    photo VARCHAR(255) DEFAULT '',
    lat DECIMAL(10,7) DEFAULT NULL,
    lng DECIMAL(10,7) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO hospitals (name, location, type, status, status_label, rating, description, blood_groups, phone, photo, lat, lng) VALUES
('Sylhet MAG Osmani Medical College Hospital', 'Kajolshah, Sylhet', 'Government', 'emergency', 'Emergency', '4.6', 'The largest government hospital in Sylhet with a dedicated emergency blood bank and trauma center.', 'A+,B+,O+,AB+', 'tel:+880821-713736', 'samples/somc.jpg', 24.9004000, 91.8687000),
('Mount Adora Hospital', 'Amberkhana, Sylhet', 'Private', 'open', 'Open 24/7', '4.7', 'Leading private hospital in Sylhet offering advanced diagnostics, surgery, and blood transfusion services.', 'O+,O-,A+,B-', 'tel:+880821-711750', 'samples/adora.png', 24.8996000, 91.8710000),
('North East Medical College Hospital', 'Toltikor, Sylhet', 'Private', 'open', 'Available', '4.5', 'Teaching hospital with a growing blood donation program and 24-hour emergency services.', 'A+,AB-,O+,B+', 'tel:+880821-761020', 'samples/nemc.webp', 24.8811000, 91.8493000),
('Ibn Sina Hospital, Sylhet', 'Subid Bazar, Sylhet', 'Private', 'open', 'Open', '4.6', 'Modern private hospital with ICU, surgical care, and an on-site blood bank for patients and donors.', 'A-,AB+,O+,B+', 'tel:+880821-2871644', 'samples/ibn.jpg', 24.8935000, 91.8630000),
('Combined Military Hospital (CMH) Sylhet', 'Sylhet Cantonment, Sylhet', 'Government', 'open', 'Open 24/7', '4.8', 'Military hospital serving both armed forces personnel and civilians, with a well-equipped blood bank and trauma unit.', 'A+,B+,O+,O-', 'tel:+880821-716317', 'samples/cmh.jfif', 24.9382901, 91.9790164);

-- Link a blood request to a real partner hospital when one was picked
-- (nullable - stays null if the requester chose "Other / not listed").
ALTER TABLE blood_requests ADD COLUMN hospital_id INT NULL AFTER hospital,
    ADD FOREIGN KEY (hospital_id) REFERENCES hospitals(id) ON DELETE SET NULL;

-- Donors "offer" on a request; the requester later confirms exactly one,
-- which is what turns a request into a verified, countable donation.
CREATE TABLE IF NOT EXISTS request_responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    request_id INT NOT NULL,
    donor_id INT NOT NULL,
    status ENUM('Pending','Confirmed','Declined') DEFAULT 'Pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_response (request_id, donor_id),
    FOREIGN KEY (request_id) REFERENCES blood_requests(id) ON DELETE CASCADE,
    FOREIGN KEY (donor_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Track which request a logged donation fulfilled, if any (self-logged
-- donations from the profile page still work fine with this left NULL).
ALTER TABLE donations ADD COLUMN request_id INT NULL AFTER user_id,
    ADD FOREIGN KEY (request_id) REFERENCES blood_requests(id) ON DELETE SET NULL;
