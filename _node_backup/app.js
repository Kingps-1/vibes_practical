/**
 * Complaint & Service Request Management System
 * Main Express Application Entry Point
 */
require('dotenv').config();

const express = require('express');
const session = require('express-session');
const path = require('path');

// Initialize database connection & run schema.sql
const db = require('./config/db');

// Import route handlers
const authRoutes = require('./routes/auth');

// Create Express application instance
const app = express();

// View engine configuration (EJS)
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));

// Static assets (CSS, images, client scripts)
app.use(express.static(path.join(__dirname, 'public')));

// Request body parsers
app.use(express.urlencoded({ extended: true }));
app.use(express.json());

// Session management configuration
app.use(session({
  secret: process.env.SESSION_SECRET || 'complaint_system_secret_key_default',
  resave: false,
  saveUninitialized: false,
  cookie: {
    httpOnly: true,
    secure: process.env.NODE_ENV === 'production',
    maxAge: 1000 * 60 * 60 * 24 // 24 hours
  }
}));

// Global middleware to provide current user session info to all EJS templates
app.use((req, res, next) => {
  res.locals.currentUser = req.session.userId ? {
    id: req.session.userId,
    role: req.session.role,
    name: req.session.userName,
    email: req.session.userEmail
  } : null;
  next();
});

// Authentication routes (register, login, logout)
app.use('/', authRoutes);

// Home / Dashboard route
app.get('/', (req, res) => {
  res.render('index');
});

// 404 Not Found handler
app.use((req, res) => {
  res.status(404).render('error', {
    status: 404,
    message: 'The requested page could not be found.'
  });
});

// Global error handling middleware
app.use((err, req, res, next) => {
  console.error('[Server Error]', err);
  res.status(500).render('error', {
    status: 500,
    message: 'An internal server error occurred.'
  });
});

// Start the Express HTTP server when executed directly
const PORT = process.env.PORT || 3000;
if (require.main === module) {
  app.listen(PORT, () => {
    console.log(`[Server] Complaint Management System running at http://localhost:${PORT}`);
  });
}

module.exports = app;
