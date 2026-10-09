# Skill: Deploy App (Safety-Gated Edition)

## Objective
Act as `@devops` to safely package, install dependencies, and launch the application with mandatory safety gates.

---

## ⚠️ CRITICAL SAFETY NOTICE

**This skill involves terminal execution. Safety gates are MANDATORY.**

All execution MUST pass through approval checkpoints. No exceptions.

---

## Execution Protocol

### Phase 0: Pre-Check Validation

```
1. Verify running in proper directory context
2. Confirm app_build/ exists and contains code
3. Check for required configuration files
```

---

### Phase 1: Stack Detection

**Identify Tech Stack:**

```yaml
Package.json exists?  → Node.js (npm/yarn/pnpm/bun)
requirements.txt exists? → Python (pip)
composer.json exists?  → PHP (composer)
go.mod exists?        → Go
Cargo.toml exists?    → Rust

If multiple detected → Use the PRIMARY (usually package.json)
```

**Map to Commands:**
```yaml
nodejs:
  install: "npm install"
  dev: "npm run dev"
  start: "npm start"
  build: "npm run build"

python:
  install: "pip install -r requirements.txt"
  dev: "python app.py"

php:
  install: "composer install"
  dev: "php artisan serve"
```

---

### Phase 2: Safety Gate - User Approval (MANDATORY)

```markdown
## 🛡️ Deployment Safety Gate

**Tech Stack Detected**: {stack}
**Commands to Execute**:

1. `cd app_build/`
2. `{install_command}`
3. `{start_command}`

**Execution Context**:
- Directory: {absolute_path}/app_build/
- Environment: Development (default)
- Network: Localhost only

---

### ⚠️ Safety Confirmations

Please confirm:

1. **I understand these terminal commands will be executed locally**
2. **I have reviewed the code in app_build/**
3. **I am ready to deploy**

**Commands will NOT execute without explicit YES**
```

**Wait for Approval:**
```
DO NOT PROCEED until user responds with "YES"

Acceptable responses:
- YES / yes / proceed → Continue to Phase 3
- NO / cancel → Abort deployment
- DRY-RUN → Show what would execute without executing
```

---

### Phase 3: Dry-Run Mode (Optional)

If Dry-Run Requested:
```
Show:
1. The exact commands that would be run
2. The current state of app_build/
3. Any potential issues detected
4. Environment variables that would be needed

DO NOT EXECUTE ANYTHING
```

---

### Phase 4: Secure Execution

#### Pre-Execution Checklist
```yaml
checklist:
  - user_approval_received: MANDATORY
  - app_build_directory_exists: MANDATORY
  - no_suspicious_files: MANDATORY
  - correct_directory_context: MANDATORY
  - commands_whitelisted: MANDATORY
```

#### Execute Installation
```
Command: {install_command}
Working Directory: app_build/
Timeout: 300 seconds

On Success → Continue
On Failure → STOP, Report Error
```

#### Execute Start
```
Command: {start_command}
Working Directory: app_build/
Background: Yes
Timeout: 30 seconds for startup

On Success → Continue
On Failure → STOP, Report Error
```

#### Post-Execution Verification
```
1. Check if process is running
2. Verify port is listening
3. Test endpoint accessibility
4. Check logs for errors
```

---

### Phase 5: Report & Output

#### Success Report
```markdown
## ✅ Deployment Successful

**Server**: [Server name if set]
**URL**: http://localhost:{port}

### Verification
| Check | Status |
|-------|--------|
| Process Running | ✅ |
| Port Listening | ✅ |
| Endpoint Accessible | ✅ |
| No Errors in Log | ✅ |

---

**Server is running. Type STOP to terminate.**
```

#### Failure Report
```markdown
## ❌ Deployment Failed

**Error**: {error description}
**Failed At**: {command that failed}

### Troubleshooting
{Diagnostic information}

### Actions Taken
- Process cleaned up
- No partial state left

---

**Fix the issues and try again with /deploy_app**
```

---

## 🔒 Security Controls

### Whitelisted Commands Only

**ALLOWED:**
- `npm install`, `npm run dev`, `npm run start`, `npm run build`
- `pip install -r requirements.txt`, `python app.py`
- `composer install`, `php artisan serve`
- `bun install`, `pnpm install`
- `go mod download`, `go run .`

**BLOCKED:**
- `rm -rf` (any form)
- `sudo` commands
- `curl | bash`
- `wget | bash`
- `git push`
- Docker privileged mode
- Any command with shell expansion `$( )` or backticks

---

## Emergency Stop Protocol

**Trigger Conditions:**
1. User says "STOP", "ABORT", "CANCEL"
2. Command execution produces suspicious output
3. Unknown error occurs

**Emergency Actions:**
```
1. Terminate any running processes
2. Do NOT leave background processes
3. Report what was executing
4. Report current state
5. Await user instruction
```

---

**Version**: 2.0 - Safety-Gated Edition
**Last Updated**: 2026-05-28
**Requires**: Explicit user approval before execution