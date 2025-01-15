#!/bin/bash

# Get current date for filename
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="backup_${DATE}.sql"

# Database credentials from .env
DB_DATABASE="a9_skansapung"
DB_USERNAME="root"
DB_PASSWORD="1q2w3e4r5_YRK"

# Create backup
mysqldump --user=$DB_USERNAME --password=$DB_PASSWORD $DB_DATABASE > $BACKUP_FILE

# Check if backup was successful
if [ $? -eq 0 ]; then
    echo "Backup created successfully: $BACKUP_FILE"
    # Make the backup file readable
    chmod 644 $BACKUP_FILE
else
    echo "Backup failed"
fi 