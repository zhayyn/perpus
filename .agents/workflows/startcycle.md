# Workflow: Start Cycle (Security-Enhanced)

## Command Definition
When the user types `/startcycle <idea>`, execute the following pipeline using definitions from `.agents/agents.md`.

---

## 🎯 Overview

This workflow orchestrates the autonomous development team through 5 phases:

```
┌─────────────┐    ┌─────────────┐    ┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│   PHASE 0   │ →  │   PHASE 1   │ →  │   PHASE 2   │ →  │   PHASE 3   │ →  │   PHASE 4   │
│   Prepare   │    │   Design    │    │   Build     │    │   Audit     │    │   Deploy    │
│    @pm      │    │    @pm      │    │  @engineer  │    │    @qa      │    │   @devops   │
└─────────────┘    └─────────────┘    └─────────────┘    └─────────────┘    └─────────────┘
      ↓                  ↓                  ↓                  ↓                  ↓
   Validate         Write Specs         Generate           Audit Code        Deploy App
   Request         (Wait YES)           Code              (Auto-fix)         (Gate)
```

---

## Pipeline Execution

### Phase 0: Pre-Validation

**Triggered**: Before Phase 1 begins

**Purpose**: Reject obviously problematic requests early

```
Check if request involves:
□ Malicious software
□ Phishing tools
□ Unauthorized access tools
□ Data exfiltration
□ Illegal activities

If ANY match → REJECT immediately
If CLEAN → Continue to Phase 1
```

---

### Phase 1: Design & Specification (@pm)

**Agent**: `@pm` (Lead Architect)
**Task**: Execute `skills/write_specs.md`

**Steps**:
1. Analyze requirements from `<idea>`
2. Draft comprehensive Technical Specification
3. Include security considerations
4. Export to `production_artifacts/Technical_Specification.md`
5. Present to user for review

**Gate**: MUST wait for explicit `YES` from user
**Loop**: If user requests modifications, revise and re-present until approved

---

### Phase 2: Code Generation (@engineer)

**Agent**: `@engineer` (Polyglot Builder)
**Triggered**: After Phase 1 approval

**Task**: Execute `skills/generate_code.md`

**Steps**:
1. Read approved specification
2. Validate specification exists
3. Create folder structure
4. Generate secure code
5. Implement security patterns
6. Save to `app_build/`
7. Self-validate for secrets/hardcoding

---

### Phase 3: Security Audit (@qa)

**Agent**: `@qa` (Security & Logic Auditor)
**Triggered**: After Phase 2 completion

**Task**: Execute `skills/audit_code.md`

**Steps**:
1. Load specification for alignment check
2. Run OWASP Top 10 audit
3. Scan for hardcoded secrets
4. Check for dependency vulnerabilities
5. Audit code quality
6. Apply patches for critical/high issues
7. Generate audit report

**Gate**: If CRITICAL issues remain, pause and require user decision

---

### Phase 4: Deployment (@devops)

**Agent**: `@devops` (Deployment Wizard)
**Triggered**: After Phase 3 (if passed or user approved)

**Task**: Execute `skills/deploy_app.md`

**Steps**:
1. Detect tech stack
2. Present execution plan
3. **WAIT for approval gate**
4. Execute installation
5. Execute start command
6. Verify server is running
7. Report URL

**Gate**: MUST wait for explicit `YES`

---

## 🚨 Emergency Stop

**At any phase**, user can type:
- `STOP` - Halt entire pipeline
- `SKIP TO DEPLOY` - Jump to Phase 4 (use with caution)
- `RESTART` - Start from Phase 1
- `ABORT` - Stop and cleanup everything

---

## 📊 Pipeline Summary

```markdown
## 🎬 Start Cycle Complete

### Execution Timeline
| Phase | Agent | Duration | Status |
|-------|-------|----------|--------|
| 0 | Pre-validation | ~5s | ✅ |
| 1 | @pm - Design | ~30s | ✅ |
| 2 | @engineer - Build | ~60s | ✅ |
| 3 | @qa - Audit | ~45s | ✅ |
| 4 | @devops - Deploy | ~30s | ✅ |

### Total Time: ~3 minutes

### Result
- Specification: production_artifacts/Technical_Specification.md
- Code: app_build/
- Server: http://localhost:{port}

---

**Development cycle complete!**
```

---

## 🔧 Configuration Options

| Flag | Description |
|------|-------------|
| `--skip-audit` | Skip Phase 3 (use only if you trust the code) |
| `--skip-deploy` | Stop after Phase 3 (generates code but doesn't deploy) |
| `--dry-run` | Shows what would happen without executing anything |
| `--force` | Bypasses all safety gates (use ONLY in isolated environments) |

---

## ⚠️ Warnings

```
⚠️ Always review the specification before approving
⚠️ Always review the code before approving deployment
⚠️ Never use --force on untrusted code
⚠️ Deployment runs commands on YOUR local machine
```

---

**Version**: 2.0 - Security Enhanced
**Last Updated**: 2026-05-28