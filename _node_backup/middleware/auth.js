/**
 * Authentication and Authorization Middleware
 * Protects routes based on login session state and assigned roles.
 */

/**
 * Ensures the user is logged in before accessing protected routes.
 * Redirects unauthenticated visitors to the login page.
 */
function isAuthenticated(req, res, next) {
  if (req.session && req.session.userId) {
    return next();
  }
  return res.redirect('/login');
}

/**
 * Restricts access for already authenticated users.
 * Redirects logged-in users away from login/register pages to the home dashboard.
 */
function isGuest(req, res, next) {
  if (req.session && req.session.userId) {
    return res.redirect('/');
  }
  return next();
}

/**
 * Role-based access control middleware factory.
 * Verifies that the logged-in user possesses one of the allowed roles ('admin', 'staff', 'user').
 * @param  {...string} allowedRoles - List of permitted roles
 */
function requireRole(...allowedRoles) {
  return (req, res, next) => {
    if (!req.session || !req.session.userId) {
      return res.redirect('/login');
    }

    if (!allowedRoles.includes(req.session.role)) {
      return res.status(403).render('error', {
        message: 'Access Denied: You do not have permission to view this resource.',
        status: 403
      });
    }

    return next();
  };
}

module.exports = {
  isAuthenticated,
  isGuest,
  requireRole
};
