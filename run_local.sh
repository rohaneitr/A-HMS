#!/bin/bash

# Enterprise-Grade Local Docker Automation Script
# Target: HMS SaaS Security Patch Verification

echo "=========================================================="
echo "🚀 Starting HMS SaaS Local Hardened Stack Deployment..."
echo "=========================================================="

# ১. পুরানো কনটেইনার ডাউন করে ফ্রেশ বিল্ড সহ আপ করা
echo "🔄 Step 1: Restarting Docker containers with clean state..."
docker-compose down
docker-compose up -d

# ২. ডাটাবেজ কনটেইনার পুরোপুরি চালু হওয়ার জন্য ৮ সেকেন্ড অপেক্ষা করা
echo "⏳ Step 2: Waiting for MariaDB to initialize storage engines..."
sleep 8

# ৩. ডকার কনটেইনারের নাম স্বয়ংক্রিয়ভাবে খুঁজে বের করা
DB_CONTAINER=$(docker ps --filter "name=db" --format "{{.Names}}" | head -n 1)

if [ -z "$DB_CONTAINER" ]; then
    echo "❌ Error: Database container not found! Ensure your service name contains 'db'."
    exit 1
fi

echo "🎯 Found DB Container: $DB_CONTAINER"

# ৪. ডকার এক্সিকিউশনের মাধ্যমে ci_sessions InnoDB টেবিল তৈরি করা
echo "💾 Step 3: Injecting ci_sessions InnoDB table into 'hmssaas' database..."
docker exec -i "$DB_CONTAINER" mysql -u root -prootpassword hmssaas -e "
CREATE TABLE IF NOT EXISTS \`ci_sessions\` (
    \`id\`         varchar(128)         NOT NULL,
    \`ip_address\` varchar(45)          NOT NULL,
    \`timestamp\`  int(10) unsigned     DEFAULT 0 NOT NULL,
    \`data\`       blob                 NOT NULL,
    PRIMARY KEY (\`id\`),
    KEY \`ci_sessions_timestamp\` (\`timestamp\`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"

if [ $? -eq 0 ]; then
    echo "✅ Success: ci_sessions table created with atomic row-locking capabilities."
else
    echo "❌ Error: Failed to inject database table. Check DB credentials."
    exit 1
fi

echo "=========================================================="
echo "🎉 Local Environment is Ready for Pentesting!"
echo "🌐 URL: http://localhost:8080"
echo "=========================================================="