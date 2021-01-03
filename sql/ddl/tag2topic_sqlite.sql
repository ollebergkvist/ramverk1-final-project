--
-- Creating a Tag2topic table.
--

--
-- Table Tag2topic
--
DROP TABLE IF EXISTS Tag2topic;
CREATE TABLE Tag2topic (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "tag" INTEGER NOT NULL,
    "topic" INTEGER NOT NULL
);
