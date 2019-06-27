CREATE DATABASE IF NOT EXISTS testing;
CREATE USER 'testing'@'%';
GRANT ALL ON testing.* TO 'testing'@'%' IDENTIFIED BY 'testing';
