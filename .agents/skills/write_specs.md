# Skill: Write Specs (Security-Enhanced)

## Objective
Act as `@pm` to generate rigorous technical specifications with security-first mindset, and explicitly WAIT for user approval.

---

## Execution Steps

### Step 1: Analyze_Requirements
```
1. Parse user's raw idea thoroughly
2. Identify core functionality
3. List stakeholders and users
4. Define success criteria
5. Check for potential security implications
6. Reject malicious or harmful requests IMMEDIATELY
```

### Step 2: Draft_Specification
Create a structured document with ALL sections:

```markdown
# Technical Specification

## 1. Executive Summary
- Project Name:
- Purpose:
- Target Users:
- Security Classification: [PUBLIC/INTERNAL/CONFIDENTIAL]

## 2. Functional Requirements
### 2.1 Core Features
| ID | Feature | Priority | Security Impact |
|----|---------|----------|----------------|
| F01 | ... | HIGH | LOW |

### 2.2 User Interactions
- [ ] Login/Authentication flow
- [ ] Data input points
- [ ] Output/presentation points

## 3. Non-Functional Requirements
### 3.1 Security Requirements
- Authentication: [Method]
- Authorization: [Model]
- Data Protection: [Encryption standard]
- Input Validation: [Rules]
- Output Encoding: [Standards]

### 3.2 Performance
- Expected load:
- Response time SLA:

### 3.3 Compliance
- [ ] GDPR / HIPAA / SOC2 considerations

## 4. Architecture & Tech Stack
### 4.1 System Architecture
[ASCII diagram]

### 4.2 Tech Stack
| Component | Technology | Version | Security Notes |
|-----------|------------|---------|----------------|
| Frontend | | | |
| Backend | | | |
| Database | | | |
| Auth | | | |

## 5. State Management & Data Flow
### 5.1 Data Flow Diagram
[ASCII diagram]

### 5.2 Data Classification
| Data Type | Sensitivity | Storage | Encryption |

## 6. Security Design
### 6.1 Threat Model
| Threat | Likelihood | Impact | Mitigation |
|--------|------------|--------|------------|
| | | | |

### 6.2 OWASP Considerations
- [ ] Injection prevention
- [ ] Authentication flaws
- [ ] Sensitive data exposure
- [ ] XSS
- [ ] Access control
- [ ] Security misconfiguration
- [ ] Dependency vulnerabilities
- [ ] Insufficient logging

### 6.3 Security Checklist
```yaml
authentication:
  method: [specify]
  password_policy: [specify]

authorization:
  model: [RBAC/ABAC/etc]

data_protection:
  encryption_at_rest: [yes/no]
  encryption_in_transit: [yes/no]
```

## 7. API Design (if applicable)
| Endpoint | Method | Auth | Input | Output | Rate Limit |

## 8. Acceptance Criteria
### 8.1 Functional
- [ ]

### 8.2 Security
- [ ] No hardcoded secrets
- [ ] Parameterized queries only
- [ ] Input validation implemented
- [ ] XSS prevention in place

## 9. Risks & Mitigations
| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|-----------|

## 10. Out of Scope
- [List explicitly what is NOT included]
```

### Step 3: Export_Artifact
```
1. Create production_artifacts/ directory if not exists
2. Save as: production_artifacts/Technical_Specification.md
3. Add metadata header
```

### Step 4: Halt_And_Assert (MANDATORY)
```
OUTPUT TO USER:
---
## 📋 Specification Ready for Review

**File**: production_artifacts/Technical_Specification.md

---

## 🛑 APPROVAL REQUIRED

Tuan Muda, specification telah siap untuk direview.

**Respon yang valid:**
- `YES` - Approve dan lanjut ke build
- `NO` - Tolak dan jelaskan
- `MODIFY [section]` - Minta perubahan spesifik

**Saya MENUNGGU persetujuan sebelum melanjutkan.**
---
```

---

## 🚫 Rejection Criteria

**AUTOMATICALLY REJECT and report if request involves:**

```
1. Malicious software (malware, ransomware, spyware)
2. Phishing or social engineering tools
3. Unauthorized access/penetration testing tools
4. Data exfiltration mechanisms
5. Cryptojacking / unauthorized mining
6. Stolen credentials handling
7. Copyright circumvention
8. Any illegal activity
```

---

**Version**: 2.0 - Security Enhanced
**Last Updated**: 2026-05-28