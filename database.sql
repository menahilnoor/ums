CREATE DATABASE IF NOT EXISTS ums;
USE ums;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone VARCHAR(30),
    dob DATE,
    role VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    profile_picture VARCHAR(255),
    department VARCHAR(120),
    current_semester VARCHAR(20),
    main_subject VARCHAR(150)
);

CREATE TABLE IF NOT EXISTS personal_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    religion VARCHAR(50),
    blood_group VARCHAR(10),
    nationality VARCHAR(80),
    domicile VARCHAR(100),
    cnic VARCHAR(30),
    address TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS family_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    father_name VARCHAR(100),
    father_cnic VARCHAR(30),
    father_occupation VARCHAR(100),
    father_contact VARCHAR(30),
    mother_name VARCHAR(100),
    guardian_name VARCHAR(100),
    guardian_contact VARCHAR(30),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS emergency_contact (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    contact_name VARCHAR(100),
    relation VARCHAR(80),
    phone VARCHAR(30),
    alt_phone VARCHAR(30),
    address TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS qualifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    degree VARCHAR(150),
    institution VARCHAR(150),
    passing_year VARCHAR(20),
    marks VARCHAR(30),
    grade VARCHAR(30),
    specialization VARCHAR(150),
    study_group VARCHAR(150),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS residence_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    residence_status VARCHAR(50),
    hostel_name VARCHAR(150),
    room_no VARCHAR(50),
    transport_used VARCHAR(20),
    transport_route VARCHAR(150),
    pick_up_point VARCHAR(150),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    semester VARCHAR(20),
    course_code VARCHAR(50),
    course_title VARCHAR(150),
    credit_hours VARCHAR(20),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS semester_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    semester VARCHAR(20) NOT NULL,
    department VARCHAR(120),
    subject VARCHAR(150),
    cgpa VARCHAR(20),
    grade VARCHAR(20),
    attendance VARCHAR(20),
    remarks VARCHAR(255),
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS teacher_certifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    certification_name VARCHAR(150),
    issued_by VARCHAR(150),
    issue_date DATE,
    expiry_date DATE,
    credential_no VARCHAR(100),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS teacher_qualifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    qualification_name VARCHAR(150),
    university_institute VARCHAR(150),
    specialization VARCHAR(150),
    passing_year VARCHAR(20),
    grade VARCHAR(30),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS teacher_teaching (
    id INT AUTO_INCREMENT PRIMARY KEY,
    teacher_id INT NOT NULL,
    semester VARCHAR(20) NOT NULL,
    department VARCHAR(120),
    subject VARCHAR(150),
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Default admin account.
-- Email: admin@ums.com
-- Password: admin123
INSERT INTO users (name, email, phone, dob, role, password)
SELECT
    'UMS Admin',
    'admin@ums.com',
    NULL,
    NULL,
    'admin',
    'admin123'
WHERE NOT EXISTS (
    SELECT 1 FROM users WHERE email = 'admin@ums.com'
);

CREATE TABLE IF NOT EXISTS it_staff_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    religion VARCHAR(50),
    blood_group VARCHAR(10),
    nationality VARCHAR(80),
    domicile VARCHAR(100),
    cnic VARCHAR(30),
    address TEXT,
    highest_qualification VARCHAR(150),
    institution VARCHAR(180),
    specialization VARCHAR(150),
    graduation_year VARCHAR(20),
    study_group VARCHAR(100),
    education_details TEXT,
    certification_name VARCHAR(180),
    certification_body VARCHAR(180),
    issue_date DATE,
    expiry_date DATE,
    credential_no VARCHAR(120),
    total_experience VARCHAR(80),
    previous_employer VARCHAR(180),
    previous_job_title VARCHAR(150),
    previous_start_date DATE,
    previous_end_date DATE,
    experience_details TEXT,
    job_title VARCHAR(150),
    employment_type VARCHAR(50),
    job_location VARCHAR(150),
    responsibilities TEXT,
    department_center VARCHAR(150),
    category ENUM('hardware','software','network') DEFAULT NULL,
    joining_date DATE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


-- IT Staff self-entered profile sections (one record per section)
CREATE TABLE IF NOT EXISTS it_staff_education (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    degree VARCHAR(150),
    institution VARCHAR(180),
    specialization VARCHAR(150),
    passing_year VARCHAR(20),
    study_group VARCHAR(100),
    education_details TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS it_staff_certifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    certification_name VARCHAR(180),
    issued_by VARCHAR(180),
    issue_date DATE,
    expiry_date DATE,
    credential_no VARCHAR(120),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS it_staff_experience (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    total_experience VARCHAR(80),
    previous_employer VARCHAR(180),
    previous_job_title VARCHAR(150),
    previous_start_date DATE,
    previous_end_date DATE,
    experience_details TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- IT Staff schedule (admin-managed, IT Staff view only)
CREATE TABLE IF NOT EXISTS it_staff_schedule (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    department_center VARCHAR(150) NOT NULL,
    category ENUM('hardware','software','network') NOT NULL,
    schedule_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    task VARCHAR(255) NOT NULL,
    location VARCHAR(180) NOT NULL,
    remarks TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
