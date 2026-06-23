-- ============================================================
-- CodeIgniter Database Session Table
-- Required for sess_driver = 'database' (SESS-001 remediation)
-- Must use InnoDB for SELECT ... FOR UPDATE row locking
-- ============================================================
USE hmssaas;

CREATE TABLE IF NOT EXISTS `ci_sessions` (
    `id`         varchar(128)         NOT NULL,
    `ip_address` varchar(45)          NOT NULL,
    `timestamp`  int(10) unsigned     DEFAULT 0 NOT NULL,
    `data`       blob                 NOT NULL,
    PRIMARY KEY (`id`),
    KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
