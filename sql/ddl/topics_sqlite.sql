--
-- Table Topics
--
DROP TABLE IF EXISTS Topics;
CREATE TABLE Topics (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "subject" TEXT NOT NULL,
    "date" DATETIME NOT NULL,
    "category" INTEGER NOT NULL,
    "author" TEXT NOT NULL,
    "tags" INTEGER NOT NULL,
    "posts" INTEGER,
    FOREIGN KEY (category) REFERENCES Categories(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (author) REFERENCES User(username) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (tags) REFERENCES Tags(id)
);
