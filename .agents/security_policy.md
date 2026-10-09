# 🛡️ Security Policy - SenopaTEA Framework

> **Security is not a feature. It is a foundation.**

---

## Overview

This document defines the security posture, policies, and guidelines for the SenopaTEA autonomous development framework.

---

## 1. Core Security Principles

### 1.1 Defense in Depth
```
Multiple layers of security controls:
┌─────────────────────────────────────────────────────────────┐
│  Layer 1: Pre-Validation (Reject bad requests)              │
├─────────────────────────────────────────────────────────────┤
│  Layer 2: Design Security (Security by design)              │
├─────────────────────────────────────────────────────────────┤
│  Layer 3: Code Generation (Secure patterns)                 │
├─────────────────────────────────────────────────────────────┤
│  Layer 4: Audit (OWASP + Secrets scan)                     │
├─────────────────────────────────────────────────────────────┤
│  Layer 5: Deployment Safety Gates (Approval + Whitelist)   │
└─────────────────────────────────────────────────────────────┘
```

### 1.2 Zero Trust Model
```
ALL inputs are untrusted until validated:
- User requests → Validated in Phase 0
- Generated code → Audited in Phase 3
- Terminal commands → Whitelist + Approval gate
- External packages → Dependency audit
- Secrets → Never hardcoded, always from .env
```

### 1.3 Least Privilege
```
Agents operate with MINIMUM necessary permissions:
- @pm: Read specification, write specification only
- @engineer: Write code in app_build/ only
- @qa: Read and fix files in app_build/ only
- @devops: Execute whitelisted commands only

NO agent can:
- Access system files outside workspace
- Execute arbitrary shell commands
- Access credentials not provided
- Modify version control
```

---

## 2. Prohibited Actions

### 2.1 Absolute Prohibitions (Never Allowed)

```yaml
never_allowed:
  shell_injection:
    - command: "rm -rf /"
    - command: "sudo rm"
    - command: "curl | bash"
    - command: "wget -O- | bash"
    - pattern: "any shell command with user input interpolation"

  credential_theft:
    - action: "Access .env files"
    - action: "Read system credential stores"
    - action: "Extract API keys from system"

  network_abuse:
    - action: "Port scanning"
    - action: "Network enumeration"
    - action: "Brute force attacks"
    - action: "DDoS tools"

  malicious_software:
    - malware: "Any form of malware"
    - ransomware: "Ransomware or encryption tools"
    - spyware: "Spyware or keyloggers"
    - cryptojacker: "Unauthorized cryptocurrency miners"

  data_exfiltration:
    - action: "Read sensitive files"
    - action: "Transmit data to external servers"
    - action: "Log credentials or secrets"

  illegal_activities:
    - piracy: "Copyright circumvention tools"
    - hacking: "Unauthorized access tools"
    - fraud: "Social engineering tools"
```

### 2.2 Conditional Prohibitions (Allowed with Restrictions)

```yaml
restricted:
  git_operations:
    allowed: false
    reason: "Prevent accidental exposure of code/secrets"

  system_commands:
    allowed: false
    reason: "Prevent system compromise"

  docker_privileged:
    allowed: false
    reason: "Prevent container escape attacks"

  global_package_install:
    allowed: false
    reason: "Prevent system pollution"
```

---

## 3. OWASP Top 10 Coverage

| # | Category | Mitigation | Status |
|---|----------|------------|--------|
| A01 | Broken Access Control | Auth middleware, RBAC | ✅ |
| A02 | Cryptographic Failures | No secrets in code, TLS | ✅ |
| A03 | Injection | Parameterized queries, sanitization | ✅ |
| A04 | Insecure Design | Rate limiting, error handling | ✅ |
| A05 | Security Misconfiguration | Security headers, proper config | ✅ |
| A06 | Vulnerable Components | npm audit, dependency scan | ✅ |
| A07 | Auth Failures | Password policy, session management | ✅ |
| A08 | Software Integrity | Package lock, integrity checks | ✅ |
| A09 | Security Logging | Logging middleware, no PII in logs | ✅ |
| A10 | SSRF | URL validation, allowlists | ✅ |

---

## 4. Emergency Stop Protocol

**Trigger Conditions:**
- User says "STOP", "ABORT", "CANCEL"
- Suspicious commands detected
- Unknown errors occur

**Actions:**
1. Terminate running processes
2. Do NOT leave background processes
3. Report current state
4. Await user instruction

---

## 5. Compliance

```yaml
standards:
  owasp: "OWASP Top 10 2021"
  cwe: "CWE Top 25 2023"
  nist: "NIST Cybersecurity Framework"
```

---

**Document Version**: 2.0
**Last Updated**: 2026-05-28
**Classification**: Internal Use