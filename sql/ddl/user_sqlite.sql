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
    "score" INTEGER NOT NULL DEFAULT 0,
    "level" TEXT DEFAULT 'NOOB',
    "permission" TEXT DEFAULT 'user',
    "created" DATETIME,
);
