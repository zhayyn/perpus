# Skill: Audit Code (Comprehensive Security Edition)

## Objective
Act as `@qa` to ensure the generated codebase is perfectly functional, secure, and free from vulnerabilities.

---

## Audit Scope

```yaml
coverage:
  security: MANDATORY    # OWASP Top 10, CWE
  quality: MANDATORY     # Syntax, logic, edge cases
  secrets: MANDATORY     # Hardcoded credentials, API keys
  dependencies: MANDATORY # Vulnerable packages
```

---

## Execution Steps

### Phase 1: Specification Alignment Check

```yaml
alignment_check:
  features:
    - [ ] All specified features implemented
    - [ ] No undocumented features added

  security:
    - [ ] Authentication implemented as specified
    - [ ] Authorization model matches spec
    - [ ] Data encryption as specified
    - [ ] Input validation present
    - [ ] Output encoding present

  tech_stack:
    - [ ] Correct framework/library versions
    - [ ] Correct database engine
```

---

### Phase 2: Security Audit (OWASP Top 10)

#### A01: Broken Access Control
```
□ All endpoints require authentication (except public ones)
□ Users can only access their own resources
□ Role-based access control properly enforced
□ Direct object references are validated
```

#### A02: Cryptographic Failures
```
□ No sensitive data in URLs (tokens, passwords)
□ No sensitive data in logs
□ Proper encryption for data at rest
□ TLS/SSL for data in transit
□ No hardcoded encryption keys
□ Secure password hashing (bcrypt, argon2)
```

#### A03: Injection
```
□ ALL SQL queries use parameterization
□ No dynamic SQL with concatenation
□ OS command injection prevented
□ XSS prevention (output encoding)
□ No eval() with user input

Patterns to Find (VULNERABLE):
  - SELECT * FROM users WHERE id = ${id}
  - db.query(`SELECT * FROM ${table}`)

Patterns to Use (SAFE):
  - db.query('SELECT * FROM users WHERE id = $1', [id])
```

#### A04: Insecure Design
```
□ Rate limiting implemented
□ Brute force protection in place
□ Account lockout policies defined
□ Proper error messages (no enumeration)
```

#### A05: Security Misconfiguration
```
□ Security headers present (CSP, X-Frame-Options, etc.)
□ Error handling hides stack traces
□ Debug mode disabled in production
```

#### A06: Vulnerable Components
```
□ npm audit / pip audit run
□ Known CVEs checked
□ Components updated to latest versions
□ Unused dependencies removed

Commands to Run:
  npm audit --audit-level=high
  pip-audit
  composer audit
```

#### A07: Auth Failures
```
□ Password complexity requirements
□ MFA support (if specified)
□ Session timeout enforced
□ Session tokens are secure/random
□ Session invalidation on logout
```

#### A08: Software Integrity Failures
```
□ Package integrity verified (npm ci)
□ No integrity bypass
□ Lock files committed
```

#### A09: Security Logging Failures
```
□ Failed login attempts logged
□ Access control failures logged
□ Server-side errors logged
□ No sensitive data in logs
```

#### A10: SSRF (Server-Side Request Forgery)
```
□ URL validation for user-provided URLs
□ No direct user input in fetch/axios calls
□ Allowlist for external requests
```

---

### Phase 3: Secrets Scanning

**Patterns to Scan:**
```regex
# API Keys
api[_-]?key\s*[:=]\s*['"]?[a-zA-Z0-9]{20,}
AKIA[0-9A-Z]{16}

# Passwords
password\s*[:=]\s*['"][^'"]+['"]

# Tokens
bearer\s+[a-zA-Z0-9\-_]+\.[a-zA-Z0-9\-_]+
jwt\s*[:=]\s*['"][a-zA-Z0-9\-_.]+['"]

# Private Keys
-----BEGIN (RSA |EC |DSA |OPENSSH) PRIVATE KEY-----

# Database Connection Strings
(postgresql|mysql|mongodb)://.*:.*@
```

**Action on Detection:**
```
1. Flag the file and line number
2. Replace with placeholder pattern
3. Document what needs to be configured
```

---

### Phase 4: Quality Checks

```
□ No syntax errors
□ All imports resolved
□ No undefined variables
□ Promise rejections handled
□ Async/await properly used
□ Error boundaries in place
□ Empty input handled
□ Null/undefined values handled
```

---

### Phase 5: Dependency Audit

**Node.js:**
```bash
npm audit --audit-level=high
npm outdated
```

**Python:**
```bash
pip-audit
pip list --outdated
```

**PHP:**
```bash
composer audit
```

---

## Audit Report Format

```markdown
## 🔍 Audit Report

**Generated**: {timestamp}
**Audited Files**: {count}
**Spec File**: Technical_Specification.md

---

### 📊 Summary

| Category | Total | Critical | High | Medium | Low |
|----------|-------|---------|------|--------|-----|
| Security | | | | | |
| Quality | | | | | |
| Secrets | | | | | |
| Dependencies | | | | | |

---

### ❌ Issues Found

#### CRITICAL (Must Fix)
1. **[File: line]** - Issue description
   - Impact: ...
   - Fix: ...

---

### ✅ Passed Checks

- [ ] Authentication implemented
- [ ] Authorization enforced
- [ ] SQL injection prevented
- [ ] XSS prevented
- [ ] No hardcoded secrets
- [ ] Rate limiting present
- [ ] Security headers configured
- [ ] Dependencies up-to-date

---

### 🔧 Fixes Applied

| File | Issue | Action Taken |
|------|-------|--------------|
| | | |

---

**Audit Status**: PASSED / FAILED
**Ready for Deployment**: YES / NO
```

---

## Safety Rules

```
❌ DO NOT execute arbitrary code during audit
❌ DO NOT make external network calls (except dependency audit)
❌ DO NOT store or transmit credentials
❌ DO NOT modify files outside app_build/
✅ DO fix security vulnerabilities found
✅ DO document all issues
✅ DO preserve original architecture intent
```

---

**Version**: 2.0 - Comprehensive Security Edition
**Last Updated**: 2026-05-28