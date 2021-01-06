--
-- Creating a User table.
--

--
-- Table User
--
DROP TABLE IF EXISTS User;
CREATE TABLE User (
    "username" TEXT PRIMARY KEY NOT NULL,
    "email" TEXT NOT NULL,
    "password" TEXT,
    "score" INTEGER NOT NULL DEFAULT 0,
    "level" TEXT DEFAULT 'NOOB',
    "permission" TEXT NOT NULL DEFAULT 'user',
    "created" DATETIME
);
