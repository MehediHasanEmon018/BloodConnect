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

CREATE TABLE blood_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    requester_id INT NOT NULL,
    patient_name VARCHAR(120) NOT NULL,
    blood_group VARCHAR(5) NOT NULL,
    units INT NOT NULL DEFAULT 1,
    hospital VARCHAR(150) NOT NULL,
    location VARCHAR(150) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    urgency VARCHAR(30) DEFAULT 'Normal',
    needed_date DATE NOT NULL,
    notes TEXT,
    status ENUM('Pending','Matched','Completed') DEFAULT 'Pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (requester_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    hospital VARCHAR(150) NOT NULL,
    blood_group VARCHAR(5) NOT NULL,
    donation_date DATE NOT NULL,
    status VARCHAR(30) DEFAULT 'Completed',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
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
