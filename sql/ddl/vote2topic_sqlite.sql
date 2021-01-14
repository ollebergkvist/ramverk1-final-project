--
-- Creating a Vote table.
--

--
-- Table Vote2Topic
--
DROP TABLE IF EXISTS Vote2Topic;
CREATE TABLE Vote2Topic (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "user" INTEGER NOT NULL,
    "topic" INTEGER NOT NULL,
    "vote" TEXT 
);
