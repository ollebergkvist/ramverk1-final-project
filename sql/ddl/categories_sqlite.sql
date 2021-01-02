--
-- Creating a very small Categories table.
--



--
-- Table Categories
--
DROP TABLE IF EXISTS Categories;
CREATE TABLE Categories (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "name" TEXT UNIQUE NOT NULL,
    "description" TEXT NOT NULL,
    "topics" INTEGER
);
