# Vue.js Frontend Setup Script

This professional shell script (`setup.sh`) organizes and commits your Vue.js frontend project files into GitHub in a clean, structured way using feature branches and conventional commits.

## Features

- ✅ **Repository Setup**: Initializes git, configures environment variables, and sets up GitHub remote
- ✅ **Feature Grouping**: Creates dedicated feature branches for each module
- ✅ **Conventional Commits**: Uses proper commit message formatting
- ✅ **Branch Workflow**: Implements GitHub Flow branching strategy
- ✅ **Comprehensive Logging**: Provides detailed output for each step
- ✅ **Selective Processing**: Support for `--only` option to process specific modules
- ✅ **Final Summary**: Shows created branches, commits, and next steps

## Usage

### Process All Modules
```bash
./setup.sh
```

### Process Only Specific Module
```bash
./setup.sh --only <module>
```

Available modules:
- `core` - Core project configuration
- `styles` - Global styles and assets
- `layouts` - Layout and shared components
- `router` - Application routing
- `stores` - State management
- `services` - API services
- `types` - TypeScript type definitions
- `views` - Views and pages
- `main` - Main application files
- `deployment` - Docker and deployment files

## Module Organization

The script organizes your Vue.js project into the following modules:

### 1. Core Setup (`feature/core-setup`)
- `package.json`, `package-lock.json`
- `vite.config.ts`, `tailwind.config.js`
- `postcss.config.js`, TypeScript configs
- `.gitignore`, `env.d.ts`
- **Commit**: `chore(project): setup project configuration and build tools`

### 2. Global Styles (`feature/global-styles`)
- `/src/assets/` directory
- **Commit**: `style(global): add base styles and assets`

### 3. Layouts (`feature/layouts`)
- `/src/layouts/` directory
- **Commit**: `feat(ui): add global layouts and shared components`

### 4. Router (`feature/router`)
- `/src/router/` directory
- **Commit**: `feat(router): configure application routes`

### 5. State Management (`feature/stores`)
- `/src/stores/` directory
- **Commit**: `feat(state): add global store modules`

### 6. Services/API (`feature/services`)
- `/src/services/` directory
- **Commit**: `feat(api): add API service layer`

### 7. Type Definitions (`feature/types`)
- `/src/types/` directory
- **Commit**: `feat(types): add TypeScript type definitions`

### 8. Views (`feature/auth-views`, `feature/admin-views`, `feature/general-views`)
- `/src/views/auth/` - Authentication pages
- `/src/views/admin/` - Admin dashboard pages
- Other view files
- **Commits**: 
  - `feat(auth): implement authentication pages and flows`
  - `feat(admin): implement admin dashboard and management pages`
  - `feat(views): implement general application pages`

### 9. Main App (`feature/main-app`)
- `src/main.ts`, `src/App.vue`, `index.html`
- **Commit**: `feat(app): add main application entry point and root component`

### 10. Deployment (`feature/deployment`)
- `Dockerfile`, `docker-compose.yml`, `.dockerignore`
- **Commit**: `feat(deployment): add containerization and deployment configuration`

## Configuration

The script is pre-configured with:
- **Repository URL**: `https://github.com/dalal-abdulghani/event-management-system.git`
- **GitHub Username**: `dalal-abdulghani`
- **Main Branch**: `main`

To modify these settings, edit the configuration section in the script:
```bash
# Configuration
REPO_URL="https://github.com/your-username/your-repo.git"
GITHUB_USERNAME="your-username"
MAIN_BRANCH="main"
DEVELOP_BRANCH="develop"
```

## Workflow

1. **Initialize Git Repository**
   - Checks if git is initialized, initializes if needed
   - Configures git user name and email
   - Adds GitHub remote origin

2. **Process Each Module**
   - Creates feature branch for the module
   - Commits relevant files with conventional commit message
   - Pushes branch to GitHub
   - Returns to main branch

3. **Provide Summary**
   - Lists all created branches
   - Shows commit messages used
   - Provides GitHub URLs
   - Suggests next steps for creating PRs

## Conventional Commits

The script follows [Conventional Commits](https://www.conventionalcommits.org/) specification:

- `feat(scope): description` - New features
- `chore(scope): description` - Project setup and maintenance
- `style(scope): description` - Code style changes
- `refactor(scope): description` - Code refactoring
- `fix(scope): description` - Bug fixes

## Example Output

```
==============================================================================
PROCESSING CORE PROJECT SETUP
==============================================================================
[INFO] Creating branch: feature/core-setup
[SUCCESS] Created and switched to branch: feature/core-setup
[SUCCESS] Committed: chore(project): setup project configuration and build tools
[INFO] Pushing branch feature/core-setup to GitHub...
[SUCCESS] Successfully pushed branch: feature/core-setup
```

## Prerequisites

- Git installed and configured
- GitHub repository created
- Proper permissions to push to the repository
- Bash shell (works on Linux, macOS, and Windows with Git Bash)

## Troubleshooting

### Authentication Issues
If you encounter authentication errors when pushing:
1. Ensure you have proper GitHub credentials configured
2. Use GitHub CLI (`gh auth login`) or SSH keys
3. Check repository permissions

### Permission Denied
If you get permission denied errors:
1. Verify you have write access to the repository
2. Check if the repository exists and is accessible
3. Ensure the repository URL is correct

### No Changes to Commit
If you see "No changes to commit" warnings:
- This is normal for modules that don't exist or are empty
- The script will skip these modules gracefully

## Next Steps After Running

1. **Review Branches**: Check the created branches on GitHub
2. **Create Pull Requests**: Go to GitHub and create PRs for each feature branch
3. **Merge Order**: Merge PRs in logical order:
   - Start with `core-setup`
   - Then `global-styles`, `layouts`, `router`
   - Follow with `stores`, `services`, `types`
   - Finally `views` and `main-app`
4. **Review Code**: Review each PR before merging
5. **Delete Branches**: Clean up feature branches after merging

## GitHub URLs

After running the script, you can find your branches at:
- Main repository: `https://github.com/dalal-abdulghani/event-management-system`
- Pull requests: `https://github.com/dalal-abdulghani/event-management-system/pulls`
- Each branch: `https://github.com/dalal-abdulghani/event-management-system/tree/feature/{branch-name}`

## Support

This script is designed to work with standard Vue.js project structures. If you have a custom structure, you may need to modify the file patterns in the script to match your project layout.
