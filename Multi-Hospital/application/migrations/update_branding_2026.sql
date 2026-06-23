-- =====================================================================
-- FAST TECHNOLOGIES - A++HMS BRANDING MIGRATION SCRIPT
-- FILE: update_branding_2026.sql
-- =====================================================================

-- 1. Safely update 'settings' table columns for the superadmin portal
UPDATE `settings`
SET `title` = 'A++HMS',
    `system_vendor` = 'Fast Technologies',
    `footer_message` = 'By Fast Technologies'
WHERE `hospital_id` = 'superadmin';

-- 2. Safely update 'settings' table columns for existing hospitals
UPDATE `settings`
SET `system_vendor` = 'Fast Technologies - Hospital management System'
WHERE `system_vendor` = 'Code Aristos - Hospital management System';

UPDATE `settings`
SET `system_vendor` = 'Fast Technologies | Hospital management System'
WHERE `system_vendor` = 'Code Aristos | Hospital management System';

UPDATE `settings`
SET `footer_message` = 'By Fast Technologies'
WHERE `footer_message` = 'By Code Aristos';

UPDATE `settings`
SET `footer_message` = 'By Fast Technologies'
WHERE `footer_message` = 'BycaSoft';

-- 3. Update 'website_settings' table to replace legacy URLs and title
UPDATE `website_settings`
SET `title` = 'A++HMS',
    `facebook_id` = 'https://www.facebook.com/FastTechnologies/',
    `twitter_id` = 'https://www.twitter.com/FastTechnologies/',
    `google_id` = 'https://www.google.com/FastTechnologies/',
    `youtube_id` = 'https://www.youtube.com/FastTechnologies/',
    `skype_id` = 'https://www.skype.com/FastTechnologies/',
    `twitter_username` = 'FastTechnologies'
WHERE `id` = 1;

-- 4. Update 'site_settings' table to patch copyright descriptions
UPDATE `site_settings`
SET `description` = REPLACE(`description`, 'Code Aristos', 'Fast Technologies')
WHERE `description` LIKE '%Code Aristos%';

-- 5. Create State Log Table for SSLCOMMERZ SameSite Cookie Redirection Fallback
CREATE TABLE IF NOT EXISTS `sslcommerz_payments_state` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `tran_id` VARCHAR(100) NOT NULL UNIQUE,
  `patient_id` INT DEFAULT NULL,
  `payment_id` INT DEFAULT NULL,
  `amount` DECIMAL(10,2) DEFAULT NULL,
  `user_id` INT DEFAULT NULL,
  `hospital_id` VARCHAR(100) DEFAULT NULL,
  `redirect_link` VARCHAR(100) DEFAULT NULL,
  `status` VARCHAR(20) DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Insert Distinct SSLCOMMERZ Rows in paymentGateway settings
INSERT INTO `paymentGateway` (`name`, `status`, `APIUsername`, `APIPassword`, `hospital_id`)
SELECT 'SSLCOMMERZ', 'test', 'Store ID', 'Store Password', h.id
FROM `hospital` h
WHERE NOT EXISTS (
    SELECT 1 FROM `paymentGateway` pg 
    WHERE pg.`name` = 'SSLCOMMERZ' AND pg.`hospital_id` = h.id
);

INSERT INTO `paymentGateway` (`name`, `status`, `APIUsername`, `APIPassword`, `hospital_id`)
SELECT 'SSLCOMMERZ', 'test', 'Store ID', 'Store Password', 'superadmin'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM `paymentGateway` pg 
    WHERE pg.`name` = 'SSLCOMMERZ' AND pg.`hospital_id` = 'superadmin'
);

-- 7. Insert Distinct LOCAL_SMS Rows in sms_settings 
INSERT INTO `sms_settings` (`name`, `username`, `password`, `api_id`, `sender`, `authkey`, `sid`, `token`, `sendernumber`, `hospital_id`)
SELECT 'LOCAL_SMS', NULL, NULL, NULL, NULL, 'Your API Auth Key', NULL, NULL, NULL, h.id
FROM `hospital` h
WHERE NOT EXISTS (
    SELECT 1 FROM `sms_settings` s 
    WHERE s.`name` = 'LOCAL_SMS' AND s.`hospital_id` = h.id
);

INSERT INTO `sms_settings` (`name`, `username`, `password`, `api_id`, `sender`, `authkey`, `sid`, `token`, `sendernumber`, `hospital_id`)
SELECT 'LOCAL_SMS', NULL, NULL, NULL, NULL, 'Your API Auth Key', NULL, NULL, NULL, 'superadmin'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM `sms_settings` s 
    WHERE s.`name` = 'LOCAL_SMS' AND s.`hospital_id` = 'superadmin'
);
