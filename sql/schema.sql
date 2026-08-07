CREATE DATABASE IF NOT EXISTS bloodconnect CHARACTER SET utf8mb4;
USE bloodconnect;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone VARCHAR(30) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    blood_group VARCHAR(5) NOT NULL,
    gender VARCHAR(20) NOT NULL,
    dob DATE NOT NULL,
    division VARCHAR(80) NOT NULL,
    district VARCHAR(80) NOT NULL,
    last_donation DATE NULL,
    photo VARCHAR(255) DEFAULT 'images/user.png',
    cover_image LONGTEXT,
    postal_code VARCHAR(20) DEFAULT '',
    country VARCHAR(80) DEFAULT 'Bangladesh',
    address VARCHAR(255) DEFAULT '',
    weight VARCHAR(10) DEFAULT '',
    availability VARCHAR(50) DEFAULT '',
    emergency_contact VARCHAR(30) DEFAULT '',
    bio TEXT,
    facebook VARCHAR(150) DEFAULT '',
    linkedin VARCHAR(150) DEFAULT '',
    instagram VARCHAR(150) DEFAULT '',
    website VARCHAR(150) DEFAULT '',
    email_notification TINYINT(1) DEFAULT 1,
    sms_notification TINYINT(1) DEFAULT 1,
    emergency_notification TINYINT(1) DEFAULT 1,
    show_email TINYINT(1) DEFAULT 1,
    show_phone TINYINT(1) DEFAULT 1,
    show_location TINYINT(1) DEFAULT 1,
    reliability VARCHAR(10) DEFAULT '98%',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    post_type VARCHAR(30) NOT NULL,      -- 'Blood Available' or 'Blood Request'
    blood_group VARCHAR(5) NOT NULL,
    hospital VARCHAR(150) DEFAULT '',
    location VARCHAR(150) DEFAULT '',
    contact VARCHAR(30) DEFAULT '',
    urgency VARCHAR(30) DEFAULT '',
    required_date DATE NULL,
    description TEXT,
    image LONGTEXT,                       -- base64 data URL, matches existing compressImage() flow
    emergency TINYINT(1) DEFAULT 0,
    likes INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE post_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE post_likes (
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    PRIMARY KEY (post_id, user_id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE hospitals (
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

CREATE TABLE blood_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    requester_id INT NOT NULL,
    patient_name VARCHAR(120) NOT NULL,
    blood_group VARCHAR(5) NOT NULL,
    units INT NOT NULL DEFAULT 1,
    hospital VARCHAR(150) NOT NULL,
    hospital_id INT NULL,
    location VARCHAR(150) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    urgency VARCHAR(30) DEFAULT 'Normal',
    needed_date DATE NOT NULL,
    notes TEXT,
    status ENUM('Pending','Matched','Completed') DEFAULT 'Pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (requester_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Donors "offer" on a request; the requester later confirms exactly one,
-- which is what turns a request into a verified, countable donation.
CREATE TABLE request_responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    request_id INT NOT NULL,
    donor_id INT NOT NULL,
    status ENUM('Pending','Confirmed','Declined') DEFAULT 'Pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_response (request_id, donor_id),
    FOREIGN KEY (request_id) REFERENCES blood_requests(id) ON DELETE CASCADE,
    FOREIGN KEY (donor_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    request_id INT NULL,
    hospital VARCHAR(150) NOT NULL,
    blood_group VARCHAR(5) NOT NULL,
    donation_date DATE NOT NULL,
    status VARCHAR(30) DEFAULT 'Completed',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (request_id) REFERENCES blood_requests(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE emergency_responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    responder_id INT NOT NULL,
    message VARCHAR(255) DEFAULT '',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (responder_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT,
    image LONGTEXT,
    is_read TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;
