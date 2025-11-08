````markdown
// filepath: /Users/nathantoombs/Sites/vibeathon/.specify/templates/plan-template.md
<!--
SYNC IMPACT REPORT
==================
Template Customization: Generic → Laravel + React + Inertia.js

Technical Context Changes:
  - Replaced generic placeholders with concrete tech stack
  - Added Laravel 12.x (PHP 8.2+), React 19, Inertia.js 2.x, PostgreSQL 18
  - Specified Pest (backend) and Vitest (frontend) for testing
  - Added architectural patterns: CRUDDY controllers, Actions/Services, custom hooks
  - Defined performance goals: <2s TTI, <200ms API p95, <200KB bundle

Constitution Check Updates:
  - Linked to 5 core principles from constitution.md
  - Added specific checklist items for each principle
  - Aligned with test-first, UX consistency, performance, and security standards

Project Structure Changes:
  - Removed generic single/web/mobile options
  - Replaced with concrete Laravel + React structure
  - Added app/, database/, resources/js/, tests/, routes/ directories
  - Specified naming conventions for controllers, actions, hooks, components
  - Documented frontend test location: resources/js/__tests__/

Architectural Guidelines:
  - Backend: CRUDDY controllers only handle CRUD, business logic in Actions/Services
  - Frontend: Business logic abstracted to custom hooks in resources/js/hooks/
  - Authorization: Laravel Policies + Spatie Permissions
  - Validation: Laravel Form Requests (backend) + Zod schemas (frontend)

Follow-up TODOs: None - template fully customized for project

Recommended Usage:
  - Use this template via /speckit.plan command
  - Customize Constraints and Scale/Scope per feature
  - Maintain CRUDDY controller pattern religiously
  - Keep business logic out of controllers and components
-->

# Implementation Plan: [FEATURE]

**Branch**: `[###-feature-name]` | **Date**: [DATE] | **Spec**: [link]
**Input**: Feature specification from `/specs/[###-feature-name]/spec.md`

**Note**: This template is filled in by the `/speckit.plan` command. See `.specify/templates/commands/plan.md` for the execution workflow.

## Summary

[Extract from feature spec: primary requirement + technical approach from research]

## Technical Context

**Language/Version**: PHP 8.2+, TypeScript 5.x  
**Backend Framework**: Laravel 12.x  
**Frontend Framework**: React 19 + Inertia.js 2.x  
**Styling**: Tailwind CSS + shadcn/ui components  
**Storage**: PostgreSQL 18 (via Laravel Eloquent ORM)  
**Testing**: Pest (PHP backend), Vitest + React Testing Library (frontend)  
**Build Tool**: Vite  
**Project Type**: Web application (Laravel backend + React frontend)  
**Performance Goals**: <2s TTI, <200ms API p95, <200KB initial bundle gzipped  
**Constraints**: [domain-specific, e.g., <200ms p95, WCAG 2.1 AA compliance or NEEDS CLARIFICATION]  
**Scale/Scope**: [domain-specific, e.g., 10k users, 50 pages/screens or NEEDS CLARIFICATION]

**Architectural Patterns:**
- **Backend**: CRUDDY controllers (thin, standard CRUD operations only)
- **Backend Business Logic**: Actions (single-purpose, invokable classes in `app/Actions/`) or Services (multi-method classes in `app/Services/`)
- **Frontend Business Logic**: Custom hooks (in `resources/js/hooks/`)
- **Authorization**: Laravel Policies + Spatie Permissions
- **Form Validation**: Laravel Form Requests (backend) + Zod schemas (frontend)

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

Review feature against `.specify/memory/constitution.md` principles:

- [ ] **Code Quality & Maintainability**: TypeScript strict mode enabled? Complex logic identified for extraction? Return types explicit?
- [ ] **Test-First Development**: Acceptance criteria converted to test cases? TDD workflow documented in tasks? Coverage target >80%?
- [ ] **UX Consistency**: Uses shadcn/ui components? Accessibility requirements identified? Responsive breakpoints planned (375px/768px/1440px)?
- [ ] **Performance Requirements**: Response time targets defined? Bundle size budget allocated? Database query strategy planned (indexes, eager loading)?
- [ ] **Security Standards**: Authentication/authorization approach specified? Input validation strategy defined? Sensitive data handling planned?

**Violations requiring justification**: [List any principle violations with rationale in Complexity Tracking section]

## Project Structure

### Documentation (this feature)

```text
specs/[###-feature]/
├── plan.md              # This file (/speckit.plan command output)
├── research.md          # Phase 0 output (/speckit.plan command)
├── data-model.md        # Phase 1 output (/speckit.plan command)
├── quickstart.md        # Phase 1 output (/speckit.plan command)
├── contracts/           # Phase 1 output (/speckit.plan command)
└── tasks.md             # Phase 2 output (/speckit.tasks command - NOT created by /speckit.plan)
```

### Source Code (repository root)

```text
# Laravel + React + Inertia.js Web Application

app/
├── Http/
│   ├── Controllers/        # CRUDDY controllers (thin, standard CRUD only)
│   │   └── [Feature]Controller.php
│   ├── Requests/          # Form validation classes
│   │   └── [Feature]Request.php
│   └── Middleware/        # Custom middleware if needed
├── Models/                # Eloquent models
│   └── [Entity].php
├── Actions/               # Single-purpose invokable business logic
│   └── [Feature]/
│       └── [Action].php
├── Services/              # Multi-method business logic classes (use sparingly)
│   └── [Feature]Service.php
├── Policies/              # Authorization policies
│   └── [Entity]Policy.php
└── Enums/                 # Type-safe enumerations (Spatie Enum)
    └── [Type].php

database/
├── migrations/            # Database schema migrations
│   └── yyyy_mm_dd_hhmmss_[description].php
├── factories/             # Model factories for testing
│   └── [Entity]Factory.php
└── seeders/               # Database seeders
    └── [Feature]Seeder.php

resources/
├── js/
│   ├── pages/            # Inertia page components
│   │   └── [Feature]/
│   │       ├── Index.tsx
│   │       ├── Show.tsx
│   │       ├── Create.tsx
│   │       └── Edit.tsx
│   ├── components/       # Reusable React components (use shadcn/ui)
│   │   └── [Feature]/
│   │       └── [Component].tsx
│   ├── hooks/            # Frontend business logic (custom React hooks)
│   │   └── use[Feature].ts
│   ├── types/            # TypeScript type definitions
│   │   └── [feature].ts
│   └── layouts/          # Page layout components
│       └── [Layout].tsx
└── css/
    └── app.css           # Tailwind CSS entry point

routes/
├── web.php               # Inertia routes (most features)
└── api.php               # API routes (if needed for SPA features)

tests/
├── Feature/              # Laravel feature tests (API, integration)
│   └── [Feature]Test.php
└── Unit/                 # Laravel unit tests (models, actions, services)
    └── [Feature]Test.php

resources/js/__tests__/   # Frontend tests (Vitest + React Testing Library)
├── components/
│   └── [Component].test.tsx
├── hooks/
│   └── use[Feature].test.ts
└── pages/
    └── [Feature]/
        └── [Page].test.tsx
```

**Structure Decision**: [Document the selected structure and reference the real
directories captured above]

## Complexity Tracking

> **Fill ONLY if Constitution Check has violations that must be justified**

| Violation | Why Needed | Simpler Alternative Rejected Because |
|-----------|------------|-------------------------------------|
| [e.g., 4th project] | [current need] | [why 3 projects insufficient] |
| [e.g., Repository pattern] | [specific problem] | [why direct DB access insufficient] |
