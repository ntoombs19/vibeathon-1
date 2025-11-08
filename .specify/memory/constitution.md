<!--
SYNC IMPACT REPORT
==================
Version Change: INITIAL → 1.0.0
Constitution Type: New ratification

Principles Established:
  1. Code Quality & Maintainability - Type safety, formatting standards, review requirements
  2. Test-First Development (NON-NEGOTIABLE) - TDD mandatory for all features
  3. User Experience Consistency - Component library, accessibility, responsive design
  4. Performance Requirements - Response time, bundle size, database query standards
  5. Security Standards - Authentication, authorization, data protection, vulnerability management

Added Sections:
  - Technical Standards (technology stack requirements)
  - Development Workflow (PR process, quality gates)
  - Governance (amendment procedures, compliance verification)

Templates Requiring Updates:
  ✅ plan-template.md - Constitution Check section aligns with new principles
  ✅ spec-template.md - User stories format supports independent testing requirement
  ✅ tasks-template.md - Task categorization reflects test-first and UX consistency principles

Follow-up TODOs: None - all placeholders filled

Recommended Commit Message:
  docs: ratify constitution v1.0.0 (code quality, testing, UX, performance, security principles)
-->

# Vibeathon Constitution

## Core Principles

### I. Code Quality & Maintainability

**MUST Requirements:**
- All TypeScript/PHP code MUST pass static type checking without `any` types or `@ts-ignore` unless explicitly justified
- Code MUST be formatted with Prettier (frontend) and Laravel Pint (backend) before commit
- All functions/methods MUST have explicit return types
- Complex logic (>3 nested conditionals or >20 lines) MUST be extracted into named functions with clear purpose
- Magic numbers/strings MUST be replaced with named constants or enums

**Code Review Standards:**
- Every PR MUST be reviewed by at least one other developer
- Reviewers MUST verify type safety, test coverage, and adherence to constitution
- Code violating principles MUST NOT be merged without explicit justification documented in PR

**Rationale:** Type safety catches bugs at compile time. Consistent formatting eliminates bikeshedding. Named abstractions make code self-documenting and easier to maintain as the project scales.

### II. Test-First Development (NON-NEGOTIABLE)

**TDD Workflow - STRICTLY ENFORCED:**
1. Write test cases from acceptance criteria → Get user/stakeholder approval
2. Run tests → Verify they FAIL (red)
3. Implement minimum code to make tests pass (green)
4. Refactor while keeping tests green
5. No implementation code MUST be written before corresponding failing test exists

**Test Coverage Requirements:**
- Every feature MUST have Pest tests (PHP) and Vitest/React Testing Library tests (frontend)
- Business logic MUST have unit tests with >80% coverage
- User-facing features MUST have integration tests covering happy path + key error scenarios
- API endpoints MUST have contract tests verifying request/response structure
- Critical user journeys MUST have end-to-end tests

**Test Quality Standards:**
- Tests MUST follow Given-When-Then or Arrange-Act-Assert pattern
- Test names MUST clearly describe the scenario being tested
- Tests MUST be independent (no shared state between tests)
- Mock external dependencies (APIs, file system, time) for deterministic results

**Rationale:** Test-first development ensures features are designed for testability, catches regressions early, and serves as living documentation. Non-negotiable status prevents technical debt accumulation.

### III. User Experience Consistency

**Component Library Requirements:**
- All UI components MUST use shadcn/ui components or extend them
- Custom components MUST follow Tailwind design system conventions
- Reusable components MUST be documented with Storybook or TSDoc
- Component props MUST use TypeScript interfaces, never inline types

**Accessibility Standards (WCAG 2.1 Level AA):**
- All interactive elements MUST be keyboard accessible
- Color contrast MUST meet 4.5:1 ratio for normal text, 3:1 for large text
- Forms MUST have proper labels and error messages announced to screen readers
- Dynamic content changes MUST use ARIA live regions
- All images MUST have meaningful alt text

**Responsive Design:**
- All pages MUST be mobile-first designed and tested on: mobile (375px), tablet (768px), desktop (1440px)
- Touch targets MUST be minimum 44x44px
- Critical content/actions MUST be accessible without horizontal scrolling

**Design System Adherence:**
- Typography MUST use defined Tailwind scale (text-sm, text-base, text-lg, etc.)
- Spacing MUST use Tailwind spacing scale (p-4, m-6, gap-2, etc.)
- Colors MUST use defined palette from tailwind.config (no arbitrary hex values unless justified)

**Rationale:** Consistency reduces cognitive load, improves learnability, and enables faster development. Accessibility is a legal requirement and moral imperative. Design systems prevent visual drift.

### IV. Performance Requirements

**Response Time Standards:**
- Page loads MUST achieve <2s Time to Interactive (TTI) on 3G connection
- API responses MUST complete in <200ms p95 latency for CRUD operations
- Database queries MUST execute in <100ms p95 (use `explain` to verify)
- Real-time features MUST respond within <500ms for user feedback

**Bundle Size Limits:**
- Initial JavaScript bundle MUST be <200KB gzipped
- Individual route bundles MUST be <100KB gzipped
- Use dynamic imports for routes and large dependencies
- Images MUST be optimized (WebP/AVIF) and properly sized
- Fonts MUST use font-display: swap and subset to used characters

**Database Performance:**
- N+1 queries MUST be eliminated (use eager loading in Laravel)
- Database indexes MUST exist for all foreign keys and frequently queried columns
- Pagination MUST be used for any list exceeding 100 items
- Database queries in loops MUST be justified or refactored to batch operations

**Monitoring:**
- All API endpoints MUST be monitored for response time and error rate
- Frontend performance MUST be tracked with Core Web Vitals (LCP, FID, CLS)
- Performance regressions >20% MUST block PR merge

**Rationale:** Performance directly impacts user satisfaction and conversion rates. Setting hard limits prevents gradual degradation. Monitoring enables early detection of regressions.

### V. Security Standards

**Authentication & Authorization:**
- All routes MUST require authentication unless explicitly marked public
- Authorization MUST use Laravel's policy-based permissions (Spatie Permissions)
- Passwords MUST be hashed with bcrypt (Laravel default)
- Session tokens MUST be HTTP-only, Secure, and SameSite=Strict
- Password reset tokens MUST expire within 1 hour
- Multi-factor authentication MUST be supported for admin accounts

**Input Validation & Sanitization:**
- All user input MUST be validated using Laravel Form Requests
- Database queries MUST use parameterized queries (Eloquent ORM) - never raw SQL with interpolation
- XSS prevention: React escapes by default, but `dangerouslySetInnerHTML` MUST be justified and sanitized
- File uploads MUST validate type, size (<10MB), and scan for malware

**Data Protection:**
- Sensitive data (PII) MUST be encrypted at rest using Laravel encryption
- API keys and secrets MUST be stored in `.env`, never committed to git
- Error messages MUST NOT leak sensitive information (stack traces, DB structure)
- Audit logs MUST track access to sensitive resources

**Dependency Management:**
- Dependencies MUST be updated monthly and scanned for vulnerabilities (npm audit, composer audit)
- High/critical vulnerabilities MUST be patched within 7 days
- Deprecated dependencies MUST be replaced before EOL date

**HTTPS & Headers:**
- All production traffic MUST use HTTPS
- Security headers MUST be configured: CSP, HSTS, X-Frame-Options, X-Content-Type-Options
- CORS MUST be explicitly configured (no wildcard origins in production)

**Rationale:** Security breaches damage user trust and have legal consequences. Defense in depth prevents single point of failure. Regular audits catch vulnerabilities before exploitation.

## Technical Standards

**Technology Stack:**
- **Backend:** PHP 8.2+, Laravel 12.x, PostgreSQL 18
- **Frontend:** TypeScript 5.x, React 19, Inertia.js, Tailwind CSS, Vite
- **Testing:** Pest (PHP), Vitest (TypeScript), React Testing Library
- **Code Quality:** ESLint, Prettier, Laravel Pint, TypeScript strict mode
- **Infrastructure:** Docker (Laravel Sail), Redis (caching/queues)

**Stack Decisions:**
- New dependencies MUST be justified (solve real problem, actively maintained, <50KB impact)
- Framework updates MUST be applied within 3 months of stable release
- Breaking changes in dependencies MUST have migration plan before upgrade

## Development Workflow

**Branch Strategy:**
- Feature branches MUST follow pattern: `###-feature-name` where ### is spec number
- Branch MUST be created from latest `main`
- Commits MUST follow conventional commits format: `type(scope): description`

**Pull Request Process:**
1. Create PR with title matching feature spec
2. Ensure all CI checks pass (tests, linting, type checking, build)
3. Request review from at least one team member
4. Address all review comments or justify why not applicable
5. Squash and merge after approval

**Quality Gates (MUST pass before merge):**
- ✅ All tests pass (backend + frontend)
- ✅ No TypeScript errors
- ✅ No ESLint/Pint violations
- ✅ Test coverage >80% for new code
- ✅ Performance budget not exceeded
- ✅ Accessibility audit passes (axe-core)
- ✅ Constitution compliance verified

**Deployment:**
- `main` branch MUST always be deployable
- Deployments MUST go through staging environment first
- Database migrations MUST be backward compatible or have rollback plan

## Governance

**Constitutional Authority:**
- This constitution supersedes all other development practices and guidelines
- All PRs MUST be verified for constitutional compliance by reviewers
- Violations MUST be justified in "Complexity Tracking" section of implementation plan

**Amendment Process:**
- Constitution changes MUST be proposed via PR to `.specify/memory/constitution.md`
- Amendments MUST document: rationale, impact on existing code, migration plan
- Breaking amendments (MAJOR version) require team consensus
- Additive amendments (MINOR version) require majority approval
- Clarifications (PATCH version) can be merged with single approval

**Versioning Policy:**
- MAJOR: Backward incompatible changes (removing/redefining principles)
- MINOR: New principles, standards, or materially expanded guidance
- PATCH: Clarifications, wording improvements, typo fixes

**Compliance Review:**
- Constitution compliance MUST be checked during code review
- Quarterly audits MUST review codebase for drift from principles
- Non-compliant code MUST be refactored or have documented justification

**Version**: 1.0.0 | **Ratified**: 2025-11-08 | **Last Amended**: 2025-11-08
