/**
 * Authentication Routes (Register, Login, Logout)
 * Uses prepared SQLite statements and bcrypt password hashing.
 * Stores user ID and role in session.
 */
const express = require('express');
const router = express.Router();
const bcrypt = require('bcrypt');
const db = require('../config/db');
const { isGuest, isAuthenticated } = require('../middleware/auth');

// Salt rounds for bcrypt hashing
const SALT_ROUNDS = 10;

// Prepared statements for user queries (optimized & SQL injection safe)
const findUserByEmailStmt = db.prepare('SELECT id, name, email, password_hash, role FROM users WHERE email = ?');
const insertUserStmt = db.prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)');

/**
 * GET /register - Display registration form
 */
router.get('/register', isGuest, (req, res) => {
  res.render('register', {
    error: null,
    values: {}
  });
});

/**
 * POST /register - Handle user registration
 */
router.post('/register', isGuest, async (req, res) => {
  const { name, email, password, confirm_password, role } = req.body;
  const sanitizedName = (name || '').trim();
  const sanitizedEmail = (email || '').trim().toLowerCase();
  const selectedRole = ['user', 'staff', 'admin'].includes(role) ? role : 'user';

  // Input validation
  if (!sanitizedName || !sanitizedEmail || !password) {
    return res.status(400).render('register', {
      error: 'Please fill in all required fields.',
      values: { name: sanitizedName, email: sanitizedEmail, role: selectedRole }
    });
  }

  // Basic email pattern validation
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(sanitizedEmail)) {
    return res.status(400).render('register', {
      error: 'Please provide a valid email address.',
      values: { name: sanitizedName, email: sanitizedEmail, role: selectedRole }
    });
  }

  // Minimum password length check
  if (password.length < 6) {
    return res.status(400).render('register', {
      error: 'Password must be at least 6 characters long.',
      values: { name: sanitizedName, email: sanitizedEmail, role: selectedRole }
    });
  }

  // Password confirmation check if provided
  if (confirm_password && password !== confirm_password) {
    return res.status(400).render('register', {
      error: 'Passwords do not match.',
      values: { name: sanitizedName, email: sanitizedEmail, role: selectedRole }
    });
  }

  try {
    // Check if an account already exists with the given email (Prepared Statement)
    const existingUser = findUserByEmailStmt.get(sanitizedEmail);
    if (existingUser) {
      return res.status(409).render('register', {
        error: 'An account with this email already exists.',
        values: { name: sanitizedName, email: sanitizedEmail, role: selectedRole }
      });
    }

    // Hash password with bcrypt
    const passwordHash = await bcrypt.hash(password, SALT_ROUNDS);

    // Insert user into database using prepared statement
    const insertResult = insertUserStmt.run(
      sanitizedName,
      sanitizedEmail,
      passwordHash,
      selectedRole
    );

    // Store user ID and role in session
    req.session.userId = Number(insertResult.lastInsertRowid);
    req.session.role = selectedRole;
    req.session.userName = sanitizedName;
    req.session.userEmail = sanitizedEmail;

    return res.redirect('/');
  } catch (error) {
    console.error('[Auth] Registration error:', error);
    return res.status(500).render('register', {
      error: 'An unexpected error occurred during registration. Please try again.',
      values: { name: sanitizedName, email: sanitizedEmail, role: selectedRole }
    });
  }
});

/**
 * GET /login - Display login form
 */
router.get('/login', isGuest, (req, res) => {
  res.render('login', {
    error: null,
    success: req.query.registered ? 'Registration successful! Please log in.' : null,
    email: ''
  });
});

/**
 * POST /login - Handle user authentication
 */
router.post('/login', isGuest, async (req, res) => {
  const { email, password } = req.body;
  const sanitizedEmail = (email || '').trim().toLowerCase();

  // Validate presence of credentials
  if (!sanitizedEmail || !password) {
    return res.status(400).render('login', {
      error: 'Please enter both email and password.',
      success: null,
      email: sanitizedEmail
    });
  }

  try {
    // Look up user by email using prepared statement
    const user = findUserByEmailStmt.get(sanitizedEmail);

    // Check user existence and compare bcrypt password hash
    if (!user || !(await bcrypt.compare(password, user.password_hash))) {
      return res.status(401).render('login', {
        error: 'Invalid email or password.',
        success: null,
        email: sanitizedEmail
      });
    }

    // Store user ID and role in session
    req.session.userId = user.id;
    req.session.role = user.role;
    req.session.userName = user.name;
    req.session.userEmail = user.email;

    return res.redirect('/');
  } catch (error) {
    console.error('[Auth] Login error:', error);
    return res.status(500).render('login', {
      error: 'An unexpected error occurred while logging in. Please try again.',
      success: null,
      email: sanitizedEmail
    });
  }
});

/**
 * GET/POST /logout - Terminate session and redirect to login
 */
const handleLogout = (req, res) => {
  req.session.destroy((err) => {
    if (err) {
      console.error('[Auth] Error destroying session on logout:', err);
    }
    res.clearCookie('connect.sid');
    return res.redirect('/login');
  });
};

router.get('/logout', handleLogout);
router.post('/logout', handleLogout);

module.exports = router;
