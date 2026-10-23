PRAGMA foreign_keys = ON;

CREATE TABLE IF NOT EXISTS users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  email TEXT UNIQUE NOT NULL,
  password_hash TEXT NOT NULL,
  role TEXT NOT NULL DEFAULT 'user'
       CHECK (role IN ('admin','staff','user')),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT UNIQUE NOT NULL
);

CREATE TABLE IF NOT EXISTS complaints (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
  category_id INTEGER REFERENCES categories(id),
  title TEXT NOT NULL,
  description TEXT NOT NULL,
  priority TEXT DEFAULT 'medium'
           CHECK (priority IN ('low','medium','high')),
  status TEXT DEFAULT 'open'
         CHECK (status IN ('open','in_progress','resolved')),
  assigned_to INTEGER REFERENCES users(id),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS status_updates (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  complaint_id INTEGER REFERENCES complaints(id) ON DELETE CASCADE,
  updated_by INTEGER REFERENCES users(id),
  note TEXT,
  new_status TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);