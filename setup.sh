#!/bin/bash

# =============================================================================
# Vue.js Frontend Project Setup Script
# =============================================================================
# This script organizes and commits Vue.js frontend project files into GitHub
# in a clean, structured way using feature branches and conventional commits.
#
# Usage:
#   ./setup.sh                    # Process all modules
#   ./setup.sh --only <module>    # Process only specific module
#
# Author: Generated for Event Management System
# =============================================================================

set -e  # Exit on any error

# Color codes for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Configuration
REPO_URL="https://github.com/dalal-abdulghani/event-management-system.git"
GITHUB_USERNAME="dalal-abdulghani"
MAIN_BRANCH="main"
DEVELOP_BRANCH="develop"

# Arrays to track created branches and commits
CREATED_BRANCHES=()
COMMIT_MESSAGES=()
PROCESSED_MODULES=()

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_header() {
    echo -e "${PURPLE}==============================================================================${NC}"
    echo -e "${PURPLE}$1${NC}"
    echo -e "${PURPLE}==============================================================================${NC}"
}

# Function to check if command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Function to check if we're in a git repository
is_git_repo() {
    git rev-parse --git-dir > /dev/null 2>&1
}

# Function to initialize git repository
init_git_repo() {
    print_header "INITIALIZING GIT REPOSITORY"
    
    if ! command_exists git; then
        print_error "Git is not installed. Please install git first."
        exit 1
    fi
    
    if is_git_repo; then
        print_warning "Git repository already initialized"
    else
        print_status "Initializing git repository..."
        git init
        print_success "Git repository initialized"
    fi
    
    # Configure git user if not already set
    if [ -z "$(git config user.name)" ]; then
        git config user.name "$GITHUB_USERNAME"
        print_status "Set git user.name to: $GITHUB_USERNAME"
    fi
    
    if [ -z "$(git config user.email)" ]; then
        git config user.email "${GITHUB_USERNAME}@users.noreply.github.com"
        print_status "Set git user.email to: ${GITHUB_USERNAME}@users.noreply.github.com"
    fi
    
    # Add remote origin if not exists
    if ! git remote get-url origin > /dev/null 2>&1; then
        git remote add origin "$REPO_URL"
        print_status "Added remote origin: $REPO_URL"
    else
        print_status "Remote origin already configured"
    fi
}

# Function to create and switch to branch
create_branch() {
    local branch_name="$1"
    
    if git show-ref --verify --quiet "refs/heads/$branch_name"; then
        print_warning "Branch $branch_name already exists, switching to it"
        git checkout "$branch_name"
    else
        print_status "Creating branch: $branch_name"
        git checkout -b "$branch_name"
        print_success "Created and switched to branch: $branch_name"
    fi
    
    CREATED_BRANCHES+=("$branch_name")
}

# Function to commit files with conventional commit message
commit_files() {
    local commit_message="$1"
    local files_pattern="$2"
    local commit_type="$3"
    
    # Check if there are files to commit
    if git diff --cached --quiet && git diff --quiet; then
        print_warning "No changes to commit for: $commit_message"
        return 0
    fi
    
    # Add files
    if [ -n "$files_pattern" ]; then
        git add $files_pattern
    else
        git add .
    fi
    
    # Check if there are staged changes
    if git diff --cached --quiet; then
        print_warning "No staged changes for: $commit_message"
        return 0
    fi
    
    # Commit with message
    git commit -m "$commit_message"
    print_success "Committed: $commit_message"
    
    COMMIT_MESSAGES+=("$commit_message")
}

# Function to push branch to GitHub
push_branch() {
    local branch_name="$1"
    
    print_status "Pushing branch $branch_name to GitHub..."
    
    if git push -u origin "$branch_name" 2>/dev/null; then
        print_success "Successfully pushed branch: $branch_name"
        return 0
    else
        print_error "Failed to push branch: $branch_name"
        print_warning "You may need to authenticate with GitHub or check your permissions"
        return 1
    fi
}

# Function to process core project setup
process_core_setup() {
    print_header "PROCESSING CORE PROJECT SETUP"
    
    create_branch "feature/core-setup"
    
    # Core configuration files
    local core_files="package.json package-lock.json vite.config.ts tailwind.config.js postcss.config.js tsconfig.json tsconfig.app.json tsconfig.node.json env.d.ts .gitignore"
    
    # Check which files exist
    local existing_files=""
    for file in $core_files; do
        if [ -f "$file" ]; then
            existing_files="$existing_files $file"
        fi
    done
    
    if [ -n "$existing_files" ]; then
        commit_files "chore(project): setup project configuration and build tools" "$existing_files"
        push_branch "feature/core-setup"
    else
        print_warning "No core configuration files found"
    fi
    
    git checkout "$MAIN_BRANCH" 2>/dev/null || git checkout -b "$MAIN_BRANCH"
    PROCESSED_MODULES+=("core-setup")
}

# Function to process global styles and assets
process_global_styles() {
    print_header "PROCESSING GLOBAL STYLES AND ASSETS"
    
    create_branch "feature/global-styles"
    
    # Global styles and assets
    local style_files="src/assets/ src/styles/"
    
    # Check if directories exist and have content
    if [ -d "src/assets" ] && [ "$(ls -A src/assets 2>/dev/null)" ]; then
        commit_files "style(global): add base styles and assets" "src/assets/"
        push_branch "feature/global-styles"
    else
        print_warning "No global styles or assets found"
    fi
    
    git checkout "$MAIN_BRANCH" 2>/dev/null || git checkout -b "$MAIN_BRANCH"
    PROCESSED_MODULES+=("global-styles")
}

# Function to process layout and shared components
process_layouts() {
    print_header "PROCESSING LAYOUTS AND SHARED COMPONENTS"
    
    create_branch "feature/layouts"
    
    if [ -d "src/layouts" ] && [ "$(ls -A src/layouts 2>/dev/null)" ]; then
        commit_files "feat(ui): add global layouts and shared components" "src/layouts/"
        push_branch "feature/layouts"
    else
        print_warning "No layout components found"
    fi
    
    git checkout "$MAIN_BRANCH" 2>/dev/null || git checkout -b "$MAIN_BRANCH"
    PROCESSED_MODULES+=("layouts")
}

# Function to process routing
process_router() {
    print_header "PROCESSING ROUTING"
    
    create_branch "feature/router"
    
    if [ -d "src/router" ] && [ "$(ls -A src/router 2>/dev/null)" ]; then
        commit_files "feat(router): configure application routes" "src/router/"
        push_branch "feature/router"
    else
        print_warning "No router configuration found"
    fi
    
    git checkout "$MAIN_BRANCH" 2>/dev/null || git checkout -b "$MAIN_BRANCH"
    PROCESSED_MODULES+=("router")
}

# Function to process state management
process_stores() {
    print_header "PROCESSING STATE MANAGEMENT"
    
    create_branch "feature/stores"
    
    if [ -d "src/stores" ] && [ "$(ls -A src/stores 2>/dev/null)" ]; then
        commit_files "feat(state): add global store modules" "src/stores/"
        push_branch "feature/stores"
    else
        print_warning "No store modules found"
    fi
    
    git checkout "$MAIN_BRANCH" 2>/dev/null || git checkout -b "$MAIN_BRANCH"
    PROCESSED_MODULES+=("stores")
}

# Function to process services/API
process_services() {
    print_header "PROCESSING SERVICES AND API"
    
    create_branch "feature/services"
    
    if [ -d "src/services" ] && [ "$(ls -A src/services 2>/dev/null)" ]; then
        commit_files "feat(api): add API service layer" "src/services/"
        push_branch "feature/services"
    else
        print_warning "No service files found"
    fi
    
    git checkout "$MAIN_BRANCH" 2>/dev/null || git checkout -b "$MAIN_BRANCH"
    PROCESSED_MODULES+=("services")
}

# Function to process types
process_types() {
    print_header "PROCESSING TYPE DEFINITIONS"
    
    create_branch "feature/types"
    
    if [ -d "src/types" ] && [ "$(ls -A src/types 2>/dev/null)" ]; then
        commit_files "feat(types): add TypeScript type definitions" "src/types/"
        push_branch "feature/types"
    else
        print_warning "No type definitions found"
    fi
    
    git checkout "$MAIN_BRANCH" 2>/dev/null || git checkout -b "$MAIN_BRANCH"
    PROCESSED_MODULES+=("types")
}

# Function to process views by feature
process_views() {
    print_header "PROCESSING VIEWS AND PAGES"
    
    if [ -d "src/views" ]; then
        # Process auth views
        if [ -d "src/views/auth" ] && [ "$(ls -A src/views/auth 2>/dev/null)" ]; then
            create_branch "feature/auth-views"
            commit_files "feat(auth): implement authentication pages and flows" "src/views/auth/"
            push_branch "feature/auth-views"
            git checkout "$MAIN_BRANCH" 2>/dev/null || git checkout -b "$MAIN_BRANCH"
            PROCESSED_MODULES+=("auth-views")
        fi
        
        # Process admin views
        if [ -d "src/views/admin" ] && [ "$(ls -A src/views/admin 2>/dev/null)" ]; then
            create_branch "feature/admin-views"
            commit_files "feat(admin): implement admin dashboard and management pages" "src/views/admin/"
            push_branch "feature/admin-views"
            git checkout "$MAIN_BRANCH" 2>/dev/null || git checkout -b "$MAIN_BRANCH"
            PROCESSED_MODULES+=("admin-views")
        fi
        
        # Process other views
        local other_views=$(find src/views -maxdepth 1 -name "*.vue" 2>/dev/null || true)
        if [ -n "$other_views" ]; then
            create_branch "feature/general-views"
            commit_files "feat(views): implement general application pages" "src/views/*.vue"
            push_branch "feature/general-views"
            git checkout "$MAIN_BRANCH" 2>/dev/null || git checkout -b "$MAIN_BRANCH"
            PROCESSED_MODULES+=("general-views")
        fi
    else
        print_warning "No views directory found"
    fi
}

# Function to process main application files
process_main_app() {
    print_header "PROCESSING MAIN APPLICATION FILES"
    
    create_branch "feature/main-app"
    
    local main_files="src/main.ts src/App.vue index.html"
    local existing_files=""
    
    for file in $main_files; do
        if [ -f "$file" ]; then
            existing_files="$existing_files $file"
        fi
    done
    
    if [ -n "$existing_files" ]; then
        commit_files "feat(app): add main application entry point and root component" "$existing_files"
        push_branch "feature/main-app"
    else
        print_warning "No main application files found"
    fi
    
    git checkout "$MAIN_BRANCH" 2>/dev/null || git checkout -b "$MAIN_BRANCH"
    PROCESSED_MODULES+=("main-app")
}

# Function to process Docker and deployment files
process_deployment() {
    print_header "PROCESSING DEPLOYMENT FILES"
    
    create_branch "feature/deployment"
    
    local deployment_files="Dockerfile docker-compose.yml .dockerignore"
    local existing_files=""
    
    for file in $deployment_files; do
        if [ -f "$file" ]; then
            existing_files="$existing_files $file"
        fi
    done
    
    if [ -n "$existing_files" ]; then
        commit_files "feat(deployment): add containerization and deployment configuration" "$existing_files"
        push_branch "feature/deployment"
    else
        print_warning "No deployment files found"
    fi
    
    git checkout "$MAIN_BRANCH" 2>/dev/null || git checkout -b "$MAIN_BRANCH"
    PROCESSED_MODULES+=("deployment")
}

# Function to show final summary
show_summary() {
    print_header "SETUP COMPLETE - SUMMARY"
    
    echo -e "${CYAN}Repository Information:${NC}"
    echo "  • Repository URL: $REPO_URL"
    echo "  • GitHub Username: $GITHUB_USERNAME"
    echo "  • Main Branch: $MAIN_BRANCH"
    echo ""
    
    echo -e "${CYAN}Processed Modules (${#PROCESSED_MODULES[@]}):${NC}"
    for module in "${PROCESSED_MODULES[@]}"; do
        echo "  ✓ $module"
    done
    echo ""
    
    echo -e "${CYAN}Created Branches (${#CREATED_BRANCHES[@]}):${NC}"
    for branch in "${CREATED_BRANCHES[@]}"; do
        echo "  • feature/$branch"
    done
    echo ""
    
    echo -e "${CYAN}Commit Messages Used:${NC}"
    for commit in "${COMMIT_MESSAGES[@]}"; do
        echo "  • $commit"
    done
    echo ""
    
    echo -e "${CYAN}GitHub URLs:${NC}"
    for branch in "${CREATED_BRANCHES[@]}"; do
        echo "  • https://github.com/$GITHUB_USERNAME/event-management-system/tree/feature/$branch"
    done
    echo ""
    
    echo -e "${CYAN}Next Steps:${NC}"
    echo "  1. Review the created branches on GitHub"
    echo "  2. Create Pull Requests for each feature branch:"
    echo "     - Go to: https://github.com/$GITHUB_USERNAME/event-management-system/pulls"
    echo "     - Click 'New Pull Request'"
    echo "     - Select feature branches to merge into $MAIN_BRANCH"
    echo "  3. Review and merge PRs in logical order:"
    echo "     - Start with core-setup"
    echo "     - Then global-styles, layouts, router"
    echo "     - Follow with stores, services, types"
    echo "     - Finally views and main-app"
    echo ""
    
    echo -e "${GREEN}Setup completed successfully! 🎉${NC}"
}

# Function to process only specific module
process_single_module() {
    local module="$1"
    
    case "$module" in
        "core"|"core-setup")
            process_core_setup
            ;;
        "styles"|"global-styles")
            process_global_styles
            ;;
        "layouts")
            process_layouts
            ;;
        "router")
            process_router
            ;;
        "stores"|"state")
            process_stores
            ;;
        "services"|"api")
            process_services
            ;;
        "types")
            process_types
            ;;
        "views"|"pages")
            process_views
            ;;
        "main"|"main-app")
            process_main_app
            ;;
        "deployment")
            process_deployment
            ;;
        *)
            print_error "Unknown module: $module"
            print_status "Available modules: core, styles, layouts, router, stores, services, types, views, main, deployment"
            exit 1
            ;;
    esac
}

# Main function
main() {
    print_header "VUE.JS FRONTEND PROJECT SETUP SCRIPT"
    
    # Parse command line arguments
    if [ "$1" = "--only" ]; then
        if [ -z "$2" ]; then
            print_error "Please specify a module after --only"
            print_status "Available modules: core, styles, layouts, router, stores, services, types, views, main, deployment"
            exit 1
        fi
        
        print_status "Processing only module: $2"
        init_git_repo
        process_single_module "$2"
        show_summary
    else
        print_status "Processing all modules"
        init_git_repo
        
        # Process all modules in logical order
        process_core_setup
        process_global_styles
        process_layouts
        process_router
        process_stores
        process_services
        process_types
        process_views
        process_main_app
        process_deployment
        
        show_summary
    fi
}

# Run main function with all arguments
main "$@"
