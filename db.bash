#!/usr/bin/env bash

# Create a database file and change the mode
echo "Creating SQlite database: 'data/db.sqlite'..."
touch data/db.sqlite
chmod 777 data/db.sqlite

echo "Creating tables for database..."
# Create the database tables
sqlite3 data/db.sqlite < sql/ddl/user_sqlite.sql
sqlite3 data/db.sqlite < sql/ddl/categories_sqlite.sql
sqlite3 data/db.sqlite < sql/ddl/topics_sqlite.sql
sqlite3 data/db.sqlite < sql/ddl/posts_sqlite.sql
sqlite3 data/db.sqlite < sql/ddl/tags_sqlite.sql
sqlite3 data/db.sqlite < sql/ddl/tag2topic_sqlite.sql
sqlite3 data/db.sqlite < sql/ddl/vote_sqlite.sql
sqlite3 data/db.sqlite < sql/ddl/vote2topic_sqlite.sql

echo "Inserting default table data..."
# Inserting data into tables
sqlite3 data/db.sqlite < sql/ddl/user_fill.sql
sqlite3 data/db.sqlite < sql/ddl/categories_fill.sql
sqlite3 data/db.sqlite < sql/ddl/topics_fill.sql
sqlite3 data/db.sqlite < sql/ddl/tags_fill.sql

echo "Database has successfully been initialized."