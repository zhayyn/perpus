# Skill: Generate Code (Security-Enhanced)

## Objective
Act as `@engineer` to write physical, secure, production-ready code strictly based on the approved specification.

---

## Pre-Execution Checklist

```yaml
prerequisites:
  - specification_exists: REQUIRED
  - specification_approved: REQUIRED
  - security_considerations_present: REQUIRED

code_safety:
  - no_hardcoded_secrets: MANDATORY
  - parameterized_queries: MANDATORY
  - input_validation: MANDATORY
  - output_encoding: MANDATORY
```

---

## Execution Steps

### Step 1: Read_Contract
```
1. Open production_artifacts/Technical_Specification.md
2. Parse all sections thoroughly
3. Identify security requirements
4. Note tech stack choices
5. List acceptance criteria
```

### Step 2: Scaffold_Structure

**Create standard folder structure:**

```text
app_build/
├── src/                    # Source code
├── config/                 # Configuration (no secrets here!)
├── tests/                  # Test files
├── .env.example            # Placeholder for required env vars
├── .gitignore              # Git ignore (include .env)
├── package.json            # Dependencies
└── README.md               # Setup instructions
```

### Step 3: Execute_Code (SECURE)

#### 3.1 Environment Configuration

**Create .env.example:**
```bash
# Server Configuration
PORT=3000
NODE_ENV=development

# Database (template - no real values)
DB_HOST=localhost
DB_PORT=5432
DB_NAME=app_db
DB_USER=your_db_user
DB_PASSWORD=your_db_password

# Authentication
JWT_SECRET=your_jwt_secret_here

# External APIs
API_KEY=your_api_key_here
```

**CRITICAL: Add .env to .gitignore:**
```
# Environment files
.env
.env.local
.env.*.local
```

#### 3.2 Database Security Pattern

**WRONG (Vulnerable):**
```javascript
// ❌ NEVER DO THIS
const query = `SELECT * FROM users WHERE id = ${userId}`;
```

**CORRECT (Parameterized):**
```javascript
// ✅ ALWAYS USE PARAMETERIZED QUERIES
const query = 'SELECT * FROM users WHERE id = $1';
db.query(query, [userId]);
```

#### 3.3 Input Validation Pattern

```javascript
// ✅ Validate ALL inputs
function validateInput(data, schema) {
  const result = schema.validate(data);
  if (result.error) {
    throw new ValidationError('Invalid input');
  }
  return result.value;
}
```

#### 3.4 Authentication Pattern

```javascript
// ✅ Secure auth middleware
const authMiddleware = async (req, res, next) => {
  try {
    const token = extractToken(req);
    if (!token) return res.status(401).json({ error: 'No token provided' });
    const decoded = verifyToken(token);
    req.user = decoded;
    next();
  } catch (err) {
    return res.status(401).json({ error: 'Invalid token' });
  }
};
```

#### 3.5 Error Handling Pattern

```javascript
// ✅ NEVER expose stack traces in production
app.use((err, req, res, next) => {
  if (process.env.NODE_ENV === 'production') {
    logger.error({ message: err.message, stack: err.stack });
    return res.status(500).json({ error: 'Internal server error' });
  }
  res.status(500).json({ error: err.message, stack: err.stack });
});
```

#### 3.6 XSS Prevention

```javascript
// ✅ For web output, encode ALL user content
const escapeHtml = (unsafe) => {
  return unsafe
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
};
```

#### 3.7 Secure Password Handling

```javascript
// ✅ NEVER store plaintext passwords
const bcrypt = require('bcrypt');
const SALT_ROUNDS = 12;

async function hashPassword(password) {
  return bcrypt.hash(password, SALT_ROUNDS);
}
```

### Step 4: Commit_To_Build

```
1. Save ALL code files to app_build/
2. Verify package.json or requirements.txt exists
3. Include .env.example with all required variables
4. Add .gitignore with .env protection
5. Create README.md with setup instructions
6. Verify no real secrets in any files
```

---

## 🚫 Prohibited Code Patterns

```
1. String interpolation in SQL queries
2. eval() or new Function() with user input
3. innerHTML with user content
4. Hardcoded passwords/API keys
5. Disabled SSL verification
6. Missing authentication on protected routes
7. System commands with user input
```

---

**Version**: 2.0 - Security Enhanced
**Last Updated**: 2026-05-28