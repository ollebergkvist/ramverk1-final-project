--
-- Creating a User table.
--

--
-- Table User
--
DROP TABLE IF EXISTS User;
CREATE TABLE User (
    "username" TEXT PRIMARY KEY UNIQUE NOT NULL,
    "email" TEXT UNIQUE NOT NULL,
    "password" TEXT,
    "score" INTEGER,
    "level" TEXT,
    "permission" TEXT DEFAULT 'user',
    "created" DATETIME,
);
