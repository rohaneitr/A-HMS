-- ============================================================
-- HMS SaaS — CI3 Database Session Table
-- SESS-001 Remediation: Required for 'database' session driver
-- Run this against the 'hmssaas' database
-- ============================================================
CREATE TABLE IF NOT EXISTS `ci_sessions` (
    `id`         varchar(128)         NOT NULL,
    `ip_address` varchar(45)          NOT NULL,
    `timestamp`  int(10) unsigned     DEFAULT 0 NOT NULL,
    `data`       blob                 NOT NULL,
    KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
