--
-- Creating a Vote table.
--

--
-- Table Vote
--
DROP TABLE IF EXISTS Vote;
CREATE TABLE Vote (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "user" INTEGER NOT NULL,
    "post" INTEGER NOT NULL,
    "vote" TEXT 
);
