#!/usr/bin/env bash

# -----------------------------------------------------------------------------
# setup.sh — Automated Version Control & Scaffolding for Backend (Laravel)
# -----------------------------------------------------------------------------
# This professional script initializes Git, sets up branching per feature, creates
# placeholder scaffolding for Laravel files, commits using Conventional Commits,
# and optionally pushes to GitHub.
#
# Customization instructions:
# - Edit the configuration block below or export environment variables before run.
# - Choose branching model via BRANCH_STRATEGY: "github-flow" or "gitflow".
# - Use --only <feature> to process a single feature branch.
# - The script will run in DRY_RUN mode if remote auth details are missing.
#
# Requirements:
# - bash, git
# - For Windows users, run via Git Bash or WSL for best results.
#
# References:
# - Conventional Commits: https://www.conventionalcommits.org/
# - Branching models: GitHub Flow / Gitflow
# -----------------------------------------------------------------------------

set -euo pipefail
IFS=$'\n\t'

# -----------------------------
# Configuration (Editable)
# -----------------------------
: "${GITHUB_REPO_URL:=""}"          # e.g. https://github.com/<org>/<repo>.git
: "${GITHUB_USERNAME:=""}"          # GitHub username for HTTPS auth
: "${GITHUB_TOKEN:=""}"             # GitHub token (with repo permissions)
: "${PROJECT_ROOT:=""}"             # If empty, auto-detected from this script location
: "${BRANCH_STRATEGY:="github-flow"}" # "github-flow" or "gitflow"

# Example features and their scopes (branch names use "feature/<name>")
# You can customize this list. Names should be simple, URL/branch-safe.
FEATURES=(
  "auth:Implement user authentication (controllers, routes, migration, seeder):auth-module"
  "user:Add user model, migration, factory, and seeder:user-module"
  "order:Add order model, migration, factory, and tests:order-module"
  "event:Add event model, migration, factory, and listeners:event-module"
  "ticket:Add ticket model and CRUD resources:ticket-module"
  "venue:Add venue model and seed data:venue-module"
  "coupon:Add coupon model and seeders:coupon-module"
  "referral:Add referral model and tests:referral-module"
  "session:Add session model and basic CRUD:session-module"
)

# -----------------------------
# Argument parsing
# -----------------------------
ONLY_FEATURE=""
while [[ $# -gt 0 ]]; do
  case "$1" in
    --only)
      ONLY_FEATURE=${2:-}
      if [[ -z "$ONLY_FEATURE" ]]; then
        echo "[ERROR] --only requires a feature name (e.g., user-module)" >&2
        exit 1
      fi
      shift 2
      ;;
    -h|--help)
      cat <<'EOF'
Usage: ./setup.sh [--only <feature>]

Options:
  --only <feature>   Process only the specified feature (e.g., user-module)
  -h, --help         Show this help

Environment variables:
  GITHUB_REPO_URL    Remote repository URL (e.g., https://github.com/org/repo.git)
  GITHUB_USERNAME    GitHub username for HTTPS auth
  GITHUB_TOKEN       GitHub token (with repo permissions)
  PROJECT_ROOT       Project root directory (auto-detected if empty)
  BRANCH_STRATEGY    "github-flow" (default) or "gitflow"

Example branches and commits:
  Branch: feature/auth-module
  Commit: feat(auth): implement user authentication (controllers, routes, migration, seeder)

  Branch: feature/order-module
  Commit: feat(order): add order model, migration, factory, and tests

  Branch: feature/user-module
  Commit: feat(user): add user model, migration, factory, and seeder
EOF
      exit 0
      ;;
    *)
      echo "[WARN] Unknown argument: $1 (ignored)" >&2
      shift
      ;;
  esac
done

# -----------------------------
# Utilities
# -----------------------------
log_info()    { echo "[INFO] $*" >&2; }
log_warn()    { echo "[WARN] $*" >&2; }
log_error()   { echo "[ERROR] $*" >&2; }
log_success() { echo "[OK] $*" >&2; }

mask_token() {
  local s="$1"
  if [[ -n "$s" ]]; then
    echo "***"
  else
    echo ""
  fi
}

# Determine PROJECT_ROOT
if [[ -z "$PROJECT_ROOT" ]]; then
  SCRIPT_DIR="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" && pwd)"
  PROJECT_ROOT="$SCRIPT_DIR"
fi

cd "$PROJECT_ROOT"

# DRY_RUN if remote or credentials incomplete
DRY_RUN=0
if [[ -z "${GITHUB_REPO_URL}" ]]; then
  DRY_RUN=1
fi

# If URL provided but username/token missing, still allow push without credentials
# but warn that it may fail for private repos.
if [[ -n "${GITHUB_REPO_URL}" && ( -z "${GITHUB_USERNAME}" || -z "${GITHUB_TOKEN}" ) ]]; then
  log_warn "Remote URL set, but username/token missing. Push may fail if repo is private."
fi

run() {
  # Executes a command, or echoes if DRY_RUN is set
  if [[ "$DRY_RUN" -eq 1 ]]; then
    echo "+ $*"
  else
    eval "$*"
  fi
}

# -----------------------------
# Git setup helpers
# -----------------------------
ensure_git_repo() {
  if git rev-parse --git-dir >/dev/null 2>&1; then
    log_info "Git repository detected."
  else
    log_info "Initializing new git repository..."
    git init
  fi

  # Ensure user identity exists to avoid commit failures
  if ! git config user.name >/dev/null 2>&1; then
    git config user.name "Auto Setup"
  fi
  if ! git config user.email >/dev/null 2>&1; then
    git config user.email "auto-setup@example.com"
  fi
}

get_base_branch() {
  if [[ "$BRANCH_STRATEGY" == "gitflow" ]]; then
    echo "develop"
  else
    echo "main"
  fi
}

ensure_base_branches() {
  local base_branch
  base_branch="$(get_base_branch)"

  # Ensure main exists
  if ! git show-ref --verify --quiet refs/heads/main; then
    log_info "Creating base branch 'main'..."
    # If repo has no commits, create initial commit
    if ! git rev-parse HEAD >/dev/null 2>&1; then
      # Ensure there is at least one file to commit
      if [[ ! -f .gitignore ]]; then
        echo -e "/vendor\n/node_modules\n/storage\n.env" > .gitignore
      fi
      git add .gitignore
      git commit -m "chore: initial commit"
    fi
    git branch -M main
  fi

  # For gitflow, ensure develop exists
  if [[ "$base_branch" == "develop" ]]; then
    if ! git show-ref --verify --quiet refs/heads/develop; then
      log_info "Creating 'develop' branch from 'main'..."
      git checkout -q main
      git checkout -qb develop
    fi
  fi
}

ensure_remote() {
  if [[ -z "$GITHUB_REPO_URL" ]]; then
    log_warn "No GITHUB_REPO_URL provided. Running in DRY_RUN mode (no pushes)."
    DRY_RUN=1
    return
  fi

  if git remote get-url origin >/dev/null 2>&1; then
    log_info "Remote 'origin' already set."
  else
    log_info "Setting remote 'origin' to $GITHUB_REPO_URL"
    git remote add origin "$GITHUB_REPO_URL"
  fi

  # If username/token provided, set a temporary authenticated URL for push
  if [[ -n "$GITHUB_USERNAME" && -n "$GITHUB_TOKEN" ]]; then
    local auth_url
    # Convert URLs of the form https://github.com/org/repo.git to include credentials
    auth_url="${GITHUB_REPO_URL/https:\/\//https:\/\/${GITHUB_USERNAME}:$GITHUB_TOKEN@}"
    log_info "Using authenticated remote for pushes (token: $(mask_token "$GITHUB_TOKEN"))"
    echo "$auth_url" > .git/.origin_push_url
  else
    # Clear any previous temp URL
    : > .git/.origin_push_url || true
  fi
}

push_branch() {
  local branch="$1"
  local push_url
  if [[ "$DRY_RUN" -eq 1 ]]; then
    log_warn "DRY_RUN: Skipping push for '$branch'"
    return 0
  fi

  if [[ -f .git/.origin_push_url && -s .git/.origin_push_url ]]; then
    push_url="$(cat .git/.origin_push_url)"
    log_info "Pushing branch '$branch' to origin using authenticated URL..."
    if git push -u "$push_url" "$branch"; then
      log_success "Pushed '$branch'"
      return 0
    else
      log_warn "Push with authenticated URL failed. Attempting standard 'origin' remote..."
    fi
  fi

  log_info "Pushing branch '$branch' to origin..."
  if git push -u origin "$branch"; then
    log_success "Pushed '$branch'"
  else
    log_error "Push failed for '$branch'. Please push manually:"
    echo "  git push -u origin $branch"
  fi
}

# -----------------------------
# Scaffolding helpers (Laravel-style)
# -----------------------------
timestamp() {
  date +%Y_%m_%d_%H%M%S
}

php_class_stub() {
  local fqcn="$1"; shift
  local kind="$1"; shift
  cat <<PHP
<?php

namespace $fqcn;

class ${kind}
{
}
PHP
}

ensure_dir() {
  local d="$1"
  if [[ ! -d "$d" ]]; then
    mkdir -p "$d"
  fi
}

create_if_missing() {
  local file_path="$1"
  local content="$2"
  if [[ ! -f "$file_path" ]]; then
    ensure_dir "$(dirname "$file_path")"
    printf "%s\n" "$content" > "$file_path"
    log_info "Created $file_path"
  else
    log_info "Exists  $file_path (skipped)"
  fi
}

scaffold_feature() {
  local scope="$1"        # e.g., user
  local feature_name="$2" # e.g., user-module

  local ModelName ControllerName TableName SeederName FactoryName TestName EventName ListenerName

  case "$scope" in
    auth)
      ModelName="User"
      ControllerName="AuthController"
      TableName="users"
      SeederName="UserSeeder"
      FactoryName="UserFactory"
      TestName="AuthTest"
      EventName="UserRegistered"
      ListenerName="SendWelcomeEmail"
      ;;
    user)
      ModelName="User"
      ControllerName="UserController"
      TableName="users"
      SeederName="UserSeeder"
      FactoryName="UserFactory"
      TestName="UserTest"
      EventName="UserCreated"
      ListenerName="UserCreatedListener"
      ;;
    order)
      ModelName="Order"
      ControllerName="OrderController"
      TableName="orders"
      SeederName="OrderSeeder"
      FactoryName="OrderFactory"
      TestName="OrderTest"
      EventName="OrderPlaced"
      ListenerName="NotifyOrderPlaced"
      ;;
    event)
      ModelName="Event"
      ControllerName="EventController"
      TableName="events"
      SeederName="EventSeeder"
      FactoryName="EventFactory"
      TestName="EventTest"
      EventName="EventCreated"
      ListenerName="EventCreatedListener"
      ;;
    ticket)
      ModelName="Ticket"
      ControllerName="TicketController"
      TableName="tickets"
      SeederName="TicketSeeder"
      FactoryName="TicketFactory"
      TestName="TicketTest"
      EventName="TicketIssued"
      ListenerName="TicketIssuedListener"
      ;;
    venue)
      ModelName="Venue"
      ControllerName="VenueController"
      TableName="venues"
      SeederName="VenueSeeder"
      FactoryName="VenueFactory"
      TestName="VenueTest"
      EventName="VenueCreated"
      ListenerName="VenueCreatedListener"
      ;;
    coupon)
      ModelName="Coupon"
      ControllerName="CouponController"
      TableName="coupons"
      SeederName="CouponSeeder"
      FactoryName="CouponFactory"
      TestName="CouponTest"
      EventName="CouponCreated"
      ListenerName="CouponCreatedListener"
      ;;
    referral)
      ModelName="Referral"
      ControllerName="ReferralController"
      TableName="referrals"
      SeederName="ReferralSeeder"
      FactoryName="ReferralFactory"
      TestName="ReferralTest"
      EventName="ReferralCreated"
      ListenerName="ReferralCreatedListener"
      ;;
    session)
      ModelName="Session"
      ControllerName="SessionController"
      TableName="sessions"
      SeederName="SessionSeeder"
      FactoryName="SessionFactory"
      TestName="SessionTest"
      EventName="SessionCreated"
      ListenerName="SessionCreatedListener"
      ;;
    *)
      ModelName="$(tr '[:lower:]' '[:upper:]' <<< "${scope:0:1}")${scope:1}"
      ControllerName="${ModelName}Controller"
      TableName="${scope}s"
      SeederName="${ModelName}Seeder"
      FactoryName="${ModelName}Factory"
      TestName="${ModelName}Test"
      EventName="${ModelName}Created"
      ListenerName="${ModelName}CreatedListener"
      ;;
  esac

  # Files
  local model_path="app/Models/${ModelName}.php"
  local controller_path="app/Http/Controllers/${ControllerName}.php"
  local migration_path="database/migrations/$(timestamp)_create_${TableName}_table.php"
  local factory_path="database/factories/${ModelName}Factory.php"
  local seeder_path="database/seeders/${SeederName}.php"
  local test_path="tests/Feature/${TestName}.php"
  local event_path="app/Events/${EventName}.php"
  local listener_path="app/Listeners/${ListenerName}.php"

  # Create placeholders only if they do not already exist
  create_if_missing "$model_path" "<?php

namespace App\\Models;

use Illuminate\\Database\\Eloquent\\Model;

class ${ModelName} extends Model
{
    // Placeholder model for ${scope}
}"

  create_if_missing "$controller_path" "<?php

namespace App\\Http\\Controllers;

use Illuminate\\Http\\Request;

class ${ControllerName} extends Controller
{
    // Placeholder controller for ${scope}
    public function index() { /* ... */ }
}"

  # Migration: create only if no existing migration for this table
  if ! ls database/migrations/*_create_${TableName}_table.php >/dev/null 2>&1; then
    create_if_missing "$migration_path" "<?php

use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('${TableName}', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('${TableName}');
    }
};"
  else
    log_info "Existing migration found for table '${TableName}' (skipped)"
  fi

  create_if_missing "$factory_path" "<?php

namespace Database\\Factories;

use Illuminate\\Database\\Eloquent\\Factories\\Factory;

class ${ModelName}Factory extends Factory
{
    public function definition(): array
    {
        return [
            // Placeholder factory for ${scope}
        ];
    }
}"

  create_if_missing "$seeder_path" "<?php

namespace Database\\Seeders;

use Illuminate\\Database\\Seeder;

class ${SeederName} extends Seeder
{
    public function run(): void
    {
        // Placeholder seeder for ${scope}
    }
}"

  create_if_missing "$test_path" "<?php

namespace Tests\\Feature;

use Tests\\TestCase;

class ${TestName} extends TestCase
{
    public function test_placeholder(): void
    {
        \$this->assertTrue(true);
    }
}"

  # Events/Listeners as placeholders
  create_if_missing "$event_path" "<?php

namespace App\\Events;

class ${EventName}
{
    // Placeholder event for ${scope}
}"

  create_if_missing "$listener_path" "<?php

namespace App\\Listeners;

class ${ListenerName}
{
    // Placeholder listener for ${scope}
}"

  # Return list of created/target files for staging
  echo "$model_path"; echo "$controller_path"; echo "$factory_path"; echo "$seeder_path"; echo "$test_path"; echo "$event_path"; echo "$listener_path"
  # migration_path may not exist if skipped, add if present
  if [[ -f "$migration_path" ]]; then echo "$migration_path"; fi
}

commit_if_changes() {
  local scope="$1"
  local desc="$2"
  local files_to_add=("$@")
  # first two args are scope and desc, files start at arg3
  files_to_add=("${files_to_add[@]:2}")

  if [[ ${#files_to_add[@]} -gt 0 ]]; then
    git add "${files_to_add[@]}" || true
  fi

  if ! git diff --cached --quiet; then
    local msg="feat(${scope}): ${desc}"
    git commit -m "$msg" -m "Automated commit by setup.sh"
    echo "$msg"
  else
    log_info "No staged changes for scope '${scope}' (skipping commit)"
    echo ""
  fi
}

# -----------------------------
# Main flow
# -----------------------------
ensure_git_repo
ensure_base_branches
ensure_remote

BASE_BRANCH="$(get_base_branch)"
git checkout -q "$BASE_BRANCH"

declare -a SUMMARY_BRANCHES=()
declare -a SUMMARY_COMMITS=()

process_feature() {
  local scope="$1"
  local description="$2"
  local name="$3"              # e.g. user-module

  local branch="feature/${name}"
  log_info "=============================="
  log_info "Processing feature: $branch"

  # Create/checkout branch
  if git show-ref --verify --quiet "refs/heads/${branch}"; then
    git checkout -q "$branch"
  else
    git checkout -q -b "$branch" "$BASE_BRANCH"
  fi

  # Scaffold and collect created file paths
  mapfile -t created_paths < <(scaffold_feature "$scope" "$name")

  # Commit
  local commit_msg
  commit_msg="$(commit_if_changes "$scope" "$description" "${created_paths[@]}")"

  # Push
  push_branch "$branch"

  # Back to base
  git checkout -q "$BASE_BRANCH"

  SUMMARY_BRANCHES+=("$branch")
  SUMMARY_COMMITS+=("${commit_msg}")
}

# Iterate features
for item in "${FEATURES[@]}"; do
  # item format: "scope:description:name"
  IFS=":" read -r scope description name <<< "$item"
  if [[ -n "$ONLY_FEATURE" && "$name" != "$ONLY_FEATURE" ]]; then
    continue
  fi
  process_feature "$scope" "$description" "$name"
done

# Summary
echo
log_info "Summary of created/updated branches and commits:"
for i in "${!SUMMARY_BRANCHES[@]}"; do
  printf " - Branch: %s\n" "${SUMMARY_BRANCHES[$i]}"
  if [[ -n "${SUMMARY_COMMITS[$i]}" ]]; then
    printf "   Commit: %s\n" "${SUMMARY_COMMITS[$i]}"
  else
    printf "   Commit: (no changes committed)\n"
  fi
done

echo
if [[ "$DRY_RUN" -eq 1 ]]; then
  log_warn "DRY_RUN was enabled. To enable automatic pushes:"
  echo "  export GITHUB_REPO_URL=\"https://github.com/<org>/<repo>.git\""
  echo "  export GITHUB_USERNAME=\"<username>\""
  echo "  export GITHUB_TOKEN=\"<token>\""
  echo "  ./setup.sh"
fi

log_success "Done."


