USE ums;

-- Existing admin account: change the old hashed password to a simple password.
-- Email: admin@ums.com
-- Password: admin123
UPDATE users
SET password = 'admin123'
WHERE email = 'admin@ums.com';
