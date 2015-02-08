# For SQLite
CREATE TABLE "customer" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "firstName" TEXT,
    "lastName" TEXT NOT NULL,
    "address" TEXT,
    "email" TEXT NOT NULL
);
CREATE TABLE sqlite_sequence(name,seq);
