/**
 * Database Configuration (SQLite via better-sqlite3)
 * Handles database connection, foreign key pragmas, and schema initialization.
 */
const Database = require('better-sqlite3');
const path = require('path');
const fs = require('fs');

// Resolve path to the SQLite database file
const dbPath = process.env.DB_PATH 
  ? path.resolve(process.env.DB_PATH) 
  : path.join(__dirname, '..', 'database.sqlite');

// Resolve path to the SQL schema definition
const schemaPath = path.join(__dirname, '..', 'schema.sql');

// Open the SQLite database connection
const db = new Database(dbPath, {
  // verbose: console.log // Uncomment for SQL query logging in development
});

// Enforce foreign key constraints in SQLite
db.pragma('foreign_keys = ON');

// Execute schema.sql on startup to create tables if they do not exist
try {
  if (fs.existsSync(schemaPath)) {
    const schemaSql = fs.readFileSync(schemaPath, 'utf8');
    db.exec(schemaSql);
    console.log('[DB] Connected to SQLite database and executed schema.sql successfully.');
  } else {
    console.warn(`[DB] Warning: schema.sql not found at ${schemaPath}`);
  }
} catch (error) {
  console.error('[DB] Failed to initialize database schema:', error.message);
  throw error;
}

module.exports = db;
