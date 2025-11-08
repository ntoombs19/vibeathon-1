# Feature Specification: BillHarmony Specialist Referral Management MVP

**Feature Branch**: `001-referral-management-mvp`  
**Created**: 2025-11-08  
**Status**: Draft  
**Input**: User description: "BillHarmony Specialist Referral Management MVP: Generate detailed user stories with acceptance criteria for the BillHarmony MVP covering referral intake, eligibility checking, cost estimation, patient communication, dashboard analytics, and security compliance"

---

## About BillHarmony

**BillHarmony** is an AI-powered specialist referral and billing transparency platform that brings clarity to healthcare costs through intelligent automation and patient-centered navigation.

**Tagline**: "Bringing Clarity to Care Costs"

**Mission**: Simplify and unify specialist referral workflows and financial transparency through AI-driven automation, empowering patients with cost understanding and helping healthcare providers deliver more efficient, compassionate care.

**Problem Statement**:
- Referrals and billing are fragmented and opaque, creating frustration for patients, specialists, and coordinators
- Providers waste time chasing referral statuses and clarifying billing
- Patients are often unaware of their eligibility, costs, or next steps

**Target Personas**:
- **Referral Coordinator**: Needs an organized dashboard, less manual entry, and automatic updates
- **Specialist/Provider**: Wants timely, accurate referrals with full patient context
- **Patient**: Wants to know the cost, eligibility, and follow-up without confusion
- **Health System Admin**: Wants metrics, compliance, and reduced leakage

**User Value**:
- Faster referral completion and fewer lost referrals
- Transparent, AI-driven cost estimates and eligibility checks
- Improved patient satisfaction and provider efficiency
- HIPAA-compliant data integrity and interoperability (HL7/FHIR)

**Business Objectives**:
- Achieve 85% referral completion rate within 12 months
- 60% reduction in manual handling time
- ARR target = $10M within 3 years per traction roadmap

**Brand Traits**: Trustworthy, Innovative, Clear, Supportive, Transparent

**Brand Voice**: Professional yet approachable; empathetic and data-driven

**Visual Identity**: Blue/green palette (#2176AE / #43B881), harmony-wave logo, clean modular layout

---

## User Scenarios & Testing *(mandatory)*

### Epic 1: Referral Intake & Routing

#### User Story 1.1 - Coordinator Creates Referral with Minimal Manual Entry (Priority: P1)

As a referral coordinator, I want to create a new specialist referral with minimal manual data entry so that I can process referrals quickly and reduce errors from retyping patient information.

**Why this priority**: Referral creation is the entry point for the entire workflow. Without efficient intake, the system cannot deliver value. This is the most critical user action.

**Independent Test**: Can be fully tested by logging in as coordinator, initiating referral creation, verifying EHR data auto-populates, selecting specialist and reason, and confirming successful submission. Delivers immediate value by reducing coordinator data entry time.

**Acceptance Scenarios**:

1. **Given** coordinator is logged into BillHarmony with active EHR integration, **When** they select "Create New Referral" and enter patient MRN or name, **Then** patient demographics (name, DOB, address, insurance) auto-populate from EHR within 3 seconds
2. **Given** coordinator has patient information loaded, **When** they search for specialist by name or specialty, **Then** search returns matching specialists with availability status, insurance network compatibility, and average wait time within 2 seconds
3. **Given** coordinator selects a specialist, **When** they enter diagnosis code (ICD-10) or free-text reason for referral, **Then** system validates diagnosis code against insurance authorization requirements and displays any pre-authorization needed flag
4. **Given** all required fields are completed (patient, specialist, reason, urgency), **When** coordinator clicks "Submit Referral", **Then** referral is created with unique ID, status set to "Pending", and confirmation message displays: "Referral #12345 created successfully. Patient will be notified within 15 minutes."
5. **Given** referral submission succeeds, **When** referral is saved, **Then** referral data is sent to EHR via HL7/FHIR within 30 seconds for bi-directional sync and audit trail is logged with coordinator ID, timestamp, and action
6. **Given** coordinator submits referral with missing required field, **When** validation fails, **Then** error message displays clearly indicating missing field (e.g., "Please select a specialist before submitting") and referral is not created

---

#### User Story 1.2 - Specialist Receives and Accepts Incoming Referral (Priority: P1)

As a specialist, I want to receive incoming referrals with complete patient context so that I can quickly assess appropriateness and accept or reject referrals without unnecessary back-and-forth.

**Why this priority**: Specialist engagement is critical for referral completion. Without timely specialist acceptance, referrals stall and patients wait indefinitely.

**Independent Test**: Can be tested by logging in as specialist, viewing referral queue, accessing referral details with full patient history, and accepting/rejecting with reason. Delivers value by streamlining specialist workflow.

**Acceptance Scenarios**:

1. **Given** specialist logs into BillHarmony, **When** they access their referral queue, **Then** queue displays pending referrals sorted by urgency (Urgent, Routine) and submission date, showing patient name, referring provider, diagnosis, and days since referral
2. **Given** specialist selects a referral, **When** they view referral details, **Then** details page displays: Patient demographics, Insurance coverage and authorization status, Diagnosis and reason for referral, Relevant clinical notes from EHR, Attached lab results or imaging (if available), Referring provider contact info
3. **Given** specialist reviews referral and determines it's appropriate, **When** they click "Accept Referral", **Then** referral status updates to "Accepted", coordinator and patient are notified automatically, and specialist can optionally add notes (e.g., "Patient should bring recent MRI to appointment")
4. **Given** specialist determines referral is not appropriate, **When** they click "Decline Referral" and select reason (e.g., "Out of scope for specialty", "Prior authorization required"), **Then** referral status updates to "Declined", coordinator is notified with reason, and system suggests alternative specialists if applicable
5. **Given** specialist accepts referral, **When** they want to schedule appointment, **Then** system provides scheduling integration link or displays patient contact info for office to call
6. **Given** specialist has not acted on referral within 48 hours (configurable), **When** deadline passes, **Then** automated reminder email is sent to specialist with subject "Pending referral requires action: [Patient Name]"

---

### Epic 2: Eligibility & Cost Estimation

#### User Story 2.1 - System Performs Automated Insurance Eligibility Check (Priority: P1)

As a referral coordinator, I want the system to automatically check patient insurance eligibility so that I can verify coverage before the specialist appointment and avoid denials or surprise bills.

**Why this priority**: Eligibility verification prevents downstream billing issues and patient dissatisfaction. This is core to BillHarmony's value proposition of financial transparency.

**Independent Test**: Can be tested by creating referral for patient with insurance, triggering automated eligibility check via 270/271 EDI transaction or payer API, and viewing coverage results. Delivers value by preventing billing surprises.

**Acceptance Scenarios**:

1. **Given** coordinator creates referral with patient insurance information, **When** referral is submitted, **Then** system automatically initiates real-time eligibility check with patient's insurance payer within 5 seconds
2. **Given** eligibility check is in progress, **When** system queries payer, **Then** loading indicator displays "Checking insurance coverage..." and result returns within 10 seconds for 95% of requests
3. **Given** eligibility check succeeds, **When** response is received, **Then** system displays coverage summary: Active coverage (Yes/No), Specialist visit copay amount, Deductible remaining, Out-of-pocket max remaining, Authorization required (Yes/No), Effective dates of coverage
4. **Given** eligibility check returns "Authorization Required", **When** result displays, **Then** system flags referral with "⚠️ Prior Authorization Needed" and provides coordinator with payer contact info and auth request form
5. **Given** eligibility check fails (payer timeout, invalid member ID), **When** error occurs, **Then** system displays user-friendly message: "Unable to verify insurance at this time. Please check member ID or try again later." and logs error for follow-up
6. **Given** patient has no insurance (self-pay), **When** coordinator indicates self-pay status, **Then** system skips eligibility check and proceeds to cost estimation based on self-pay rates

---

#### User Story 2.2 - AI-Powered Cost Estimator Provides Transparent Pricing (Priority: P1)

As a patient, I want to see an upfront cost estimate for my specialist visit so that I can understand my financial responsibility and plan accordingly.

**Why this priority**: Cost transparency is BillHarmony's core differentiator. Without accurate estimates, patients face billing anxiety and may delay care.

**Independent Test**: Can be tested by viewing referral as patient, seeing AI-generated cost estimate based on specialist fee schedule and insurance coverage, and understanding breakdown. Delivers value by providing financial clarity.

**Acceptance Scenarios**:

1. **Given** patient receives referral notification, **When** they click "View Cost Estimate" link in email or portal, **Then** estimate page displays within 3 seconds showing: Estimated specialist visit cost (facility + professional fees), Insurance coverage amount, Patient responsibility (copay + coinsurance + deductible), Total estimated out-of-pocket
2. **Given** cost estimate is displayed, **When** patient views breakdown, **Then** estimate includes: Plain-language explanation (e.g., "Your insurance covers $200. You'll pay $50 copay plus $100 toward your deductible."), Confidence level of estimate (High/Medium/Low based on data availability), Disclaimer noting actual costs may vary, Link to financial assistance programs if patient responsibility exceeds threshold (e.g., $500)
3. **Given** AI cost model generates estimate, **When** calculation is performed, **Then** system uses: Specialist's contracted rate with patient's insurance (if available), Historical claims data for similar visits, Chargemaster pricing as fallback, Patient's current deductible and out-of-pocket status from eligibility check
4. **Given** patient has high estimated out-of-pocket ($500+), **When** estimate displays, **Then** system highlights financial assistance options: Charity care eligibility screening link, Payment plan information, Sliding scale discount inquiry, and message: "Need help with costs? We can check if you qualify for assistance."
5. **Given** cost estimate accuracy is tracked, **When** actual claim is processed, **Then** system compares estimate to actual patient responsibility and calculates accuracy rate (target ≥ 95% within ±20% margin)
6. **Given** patient wants to compare costs, **When** multiple specialists are available, **Then** system displays cost estimates for each specialist side-by-side with other factors (wait time, ratings, distance)

---

### Epic 3: Patient Communication Hub

#### User Story 3.1 - Patient Receives Proactive Referral Status Updates (Priority: P2)

As a patient, I want to receive timely updates about my referral status via my preferred communication channel so that I stay informed and can take action when needed.

**Why this priority**: Proactive communication reduces patient anxiety and no-shows. Important for patient satisfaction but not blocking for core referral workflow.

**Independent Test**: Can be tested by creating referral, patient opting into notifications, and verifying emails/SMS sent at key milestones (referral created, specialist accepted, appointment scheduled). Delivers value by keeping patients informed.

**Acceptance Scenarios**:

1. **Given** patient is added to referral, **When** referral is created, **Then** patient receives notification via preferred channel (email and/or SMS) within 15 minutes with message: "Your referral to [Specialist Name] has been submitted. We'll notify you when it's been reviewed. View details: [link]"
2. **Given** specialist accepts referral, **When** status changes to "Accepted", **Then** patient receives notification: "Good news! [Specialist Name] has accepted your referral. Their office will contact you to schedule an appointment. Questions? [support link]"
3. **Given** patient has appointment scheduled, **When** appointment is confirmed, **Then** patient receives confirmation with: Specialist name and address, Appointment date and time, What to bring (insurance card, ID, medical records), Estimated cost reminder, Directions and parking info
4. **Given** appointment is 48 hours away, **When** reminder time is reached, **Then** patient receives reminder: "Your appointment with [Specialist] is in 2 days on [Date] at [Time]. Need to reschedule? [link]"
5. **Given** patient's notification preferences are set, **When** they access patient portal settings, **Then** they can toggle notifications on/off for: Referral created, Specialist response, Appointment scheduled, Appointment reminders, Billing updates
6. **Given** notification delivery fails (invalid email, SMS opt-out), **When** delivery error occurs, **Then** system logs failed delivery, attempts alternative contact method if available, and flags for coordinator follow-up

---

### Epic 4: Dashboard & Analytics

#### User Story 4.1 - Coordinator Views Real-Time Referral Dashboard (Priority: P2)

As a referral coordinator, I want to view a real-time dashboard of all my referrals with status and aging so that I can proactively manage my workflow and follow up on stalled referrals.

**Why this priority**: Dashboard provides visibility and enables proactive management. Important for coordinator efficiency but basic referral CRUD is more critical.

**Independent Test**: Can be tested by accessing coordinator dashboard, viewing referrals grouped by status, filtering by age/urgency, and drilling into details. Delivers value by surfacing actionable insights.

**Acceptance Scenarios**:

1. **Given** coordinator accesses dashboard, **When** page loads, **Then** dashboard displays within 2 seconds showing: Total referrals count, Referrals by status (Pending, Accepted, Scheduled, Completed, Declined) with counts, Referrals requiring action (pending >48hrs, missing info) highlighted, Quick filters (All, My Referrals, Urgent, This Week)
2. **Given** coordinator views referral list, **When** they apply filter, **Then** list updates instantly showing: Patient name, Specialist, Status, Days since creation, Urgency indicator (🔴 Urgent, 🟡 Routine), Action needed (if any)
3. **Given** coordinator selects a referral, **When** they drill into details, **Then** referral detail view displays: Complete referral information, Status history timeline (created, sent, accepted, etc.), Communications log (emails/SMS sent to patient), Eligibility and cost estimate results, Notes and attachments
4. **Given** coordinator identifies stalled referral (pending >7 days), **When** they review details, **Then** system suggests actions: "Re-send to specialist", "Contact patient", "Update urgency", "Cancel referral"
5. **Given** coordinator wants to export data, **When** they click "Export Report", **Then** CSV download includes: All visible referrals, Key fields (Patient, Specialist, Status, Date Created, Days Pending), Custom date range filter option
6. **Given** dashboard displays metrics, **When** coordinator views summary cards, **Then** cards show: Average time to acceptance (target <48hrs), Completion rate (target 85%), Coordinator productivity (referrals processed per day)

---

#### User Story 4.2 - Health System Admin Analyzes Referral Leakage and Trends (Priority: P3)

As a health system administrator, I want to view analytics on referral leakage, completion rates, and network utilization so that I can identify improvement opportunities and demonstrate ROI.

**Why this priority**: Analytics drive strategic decisions and demonstrate platform value. Valuable for business case but not required for MVP launch.

**Independent Test**: Can be tested by accessing admin analytics dashboard, viewing completion funnel, leakage analysis by specialty, and trend charts over time. Delivers value by quantifying impact.

**Acceptance Scenarios**:

1. **Given** admin accesses analytics dashboard, **When** they view overview, **Then** dashboard displays key metrics: Total referrals (current month, quarter, year), Completion rate % (referrals resulting in completed visit / total referrals), Average days to specialist acceptance, Average days to appointment completion, Leakage rate % (referrals not completed within 90 days)
2. **Given** admin reviews completion funnel, **When** they view funnel visualization, **Then** funnel shows conversion at each stage: Referrals Created → Specialist Accepted (% drop-off) → Appointment Scheduled (% drop-off) → Appointment Completed (% drop-off), with ability to filter by specialty, referring provider, or time period
3. **Given** admin wants to identify leakage causes, **When** they drill into incomplete referrals, **Then** system displays top reasons: Specialist declined (with decline reasons), Patient no-show, Patient cancelled, Authorization denied, Lost to follow-up (no response from patient), with counts and percentages
4. **Given** admin reviews network utilization, **When** they view specialist performance, **Then** report shows: Referrals received per specialist, Average acceptance time, Acceptance rate %, Patient satisfaction scores (if collected), highlighting high and low performers
5. **Given** admin wants to demonstrate ROI, **When** they access ROI dashboard, **Then** metrics include: Time saved per referral (before/after BillHarmony), Cost savings from reduced administrative labor, Revenue captured from improved completion rate, Patient satisfaction improvement (NPS or survey results)
6. **Given** admin wants to ensure compliance, **When** they view audit reports, **Then** system provides: HIPAA access logs (who viewed which patient records), Referral volume by insurance type (compliance reporting), Average time to care (quality metrics), Data export for regulatory reporting

---

### Epic 5: Security & Compliance

#### User Story 5.1 - System Maintains HIPAA-Compliant Data Security (Priority: P1)

As a health system compliance officer, I want BillHarmony to maintain HIPAA-compliant security controls so that patient data is protected and we meet regulatory requirements.

**Why this priority**: HIPAA compliance is non-negotiable for healthcare platforms. Security breach would be catastrophic. Must be built-in from day one.

**Independent Test**: Can be tested through security audit, penetration testing, and verification of encryption, access controls, and audit logging against HIPAA Security Rule requirements. Delivers value by protecting patient privacy and ensuring regulatory compliance.

**Acceptance Scenarios**:

1. **Given** user accesses BillHarmony, **When** they authenticate, **Then** all data transmission uses TLS 1.2+ encryption, session tokens are HTTP-only and secure, and multi-factor authentication is available for sensitive roles (admin, compliance)
2. **Given** patient data is stored, **When** data is written to database, **Then** all PHI fields (name, DOB, SSN, MRN, address, insurance ID) are encrypted at rest using AES-256 encryption with secure key management (AWS KMS or equivalent)
3. **Given** user accesses patient record, **When** PHI is viewed, **Then** access is logged to audit trail capturing: User ID, Timestamp, Patient accessed, Action performed (view, edit, export), IP address, Session ID
4. **Given** user lacks proper permission, **When** they attempt to access restricted data, **Then** access is denied with message "You don't have permission to view this patient's record" and security event is logged for compliance review
5. **Given** system integrates with EHR, **When** HL7/FHIR data is exchanged, **Then** all API communications use OAuth 2.0 authentication, TLS encryption, and rate limiting to prevent abuse
6. **Given** regulatory audit is conducted, **When** audit logs are requested, **Then** system provides complete tamper-evident audit trail for specified date range showing all PHI access with user attribution, exportable in standard format (CSV, PDF)

---

#### User Story 5.2 - System Supports HL7/FHIR Interoperability (Priority: P1)

As a health system IT administrator, I want BillHarmony to integrate with our EHR using HL7/FHIR standards so that we can exchange referral and patient data without custom development.

**Why this priority**: Interoperability is essential for product viability. Without EHR integration, manual data entry negates efficiency gains.

**Independent Test**: Can be tested by configuring FHIR endpoints, sending test referral from EHR, verifying BillHarmony receives and processes ServiceRequest resource, and confirming bi-directional status updates. Delivers value by enabling automated data exchange.

**Acceptance Scenarios**:

1. **Given** health system has FHIR R4-compliant EHR, **When** BillHarmony FHIR endpoint is configured, **Then** system supports standard FHIR resources: Patient (demographics), Practitioner (specialist info), ServiceRequest (referral), Coverage (insurance), DocumentReference (attachments)
2. **Given** referral is created in EHR, **When** EHR sends FHIR ServiceRequest to BillHarmony endpoint, **Then** BillHarmony receives request, validates against FHIR schema, creates internal referral record, and returns 201 Created status with referral ID
3. **Given** specialist accepts referral in BillHarmony, **When** status changes, **Then** system sends FHIR ServiceRequest update to EHR with status "active" and specialist practitioner reference, completing bi-directional sync
4. **Given** HL7 v2 integration is required (legacy EHR), **When** system receives HL7 REF message, **Then** BillHarmony parses message, maps to internal data model, creates referral, and sends ACK acknowledgment
5. **Given** FHIR transaction fails (network timeout, schema validation error), **When** error occurs, **Then** system retries with exponential backoff (max 3 attempts), logs detailed error message, and alerts IT admin if all retries fail
6. **Given** integration testing is required, **When** health system runs FHIR conformance check, **Then** BillHarmony provides CapabilityStatement documenting supported resources, operations, and search parameters

---

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST auto-populate patient demographics from EHR within 3 seconds of MRN entry using HL7/FHIR integration
- **FR-002**: System MUST validate specialist selection by checking insurance network compatibility and displaying average wait time within 2 seconds
- **FR-003**: System MUST create referral with unique ID, set status to "Pending", and sync to EHR within 30 seconds
- **FR-004**: System MUST display specialist referral queue sorted by urgency and submission date with pending referrals at top
- **FR-005**: System MUST automatically perform real-time insurance eligibility check via 270/271 EDI or payer API within 10 seconds for 95% of requests
- **FR-006**: System MUST display eligibility results showing active coverage, copay, deductible remaining, out-of-pocket max, and authorization requirements
- **FR-007**: System MUST generate AI-powered cost estimates using specialist contracted rates, historical claims data, and patient deductible status with ≥95% accuracy within ±20% margin
- **FR-008**: System MUST display cost breakdown to patients with plain-language explanation and confidence level (High/Medium/Low)
- **FR-009**: System MUST send patient notifications via email and/or SMS within 15 minutes of referral creation and status changes
- **FR-010**: System MUST provide coordinator dashboard displaying referrals by status, days since creation, and action needed flags within 2 seconds page load
- **FR-011**: System MUST send specialist reminder if no action taken on referral within 48 hours (configurable threshold)
- **FR-012**: System MUST log all PHI access to tamper-evident audit trail capturing user, timestamp, patient, action, and IP address
- **FR-013**: System MUST encrypt all PHI at rest using AES-256 and in transit using TLS 1.2+
- **FR-014**: System MUST support FHIR R4 integration with resources: Patient, Practitioner, ServiceRequest, Coverage, DocumentReference
- **FR-015**: System MUST handle FHIR integration failures with retry logic (exponential backoff, max 3 attempts) and admin alerting
- **FR-016**: System MUST calculate and display completion rate % (completed visits / total referrals) on admin analytics dashboard
- **FR-017**: System MUST identify referral leakage causes (declined, no-show, cancelled, authorization denied, lost to follow-up) with counts
- **FR-018**: System MUST support multi-factor authentication for admin and compliance roles
- **FR-019**: System MUST enforce role-based access control (RBAC) with roles: Coordinator, Specialist, Patient, Admin, Compliance
- **FR-020**: All user-facing messaging MUST follow BillHarmony voice guidelines: clear, empathetic, supportive, avoiding medical jargon

### Key Entities

- **Referral**: Specialist visit request; attributes include patient reference, referring provider, specialist, diagnosis (ICD-10), reason for referral, urgency (Urgent/Routine), status (Pending/Accepted/Scheduled/Completed/Declined), creation date, acceptance date, appointment date
- **Patient**: Individual receiving care; attributes include MRN, name, DOB, address, contact (email, phone), insurance coverage, deductible status, communication preferences
- **Specialist**: Healthcare provider receiving referrals; attributes include NPI, name, specialty, office locations, insurance networks accepted, average wait time, acceptance rate
- **Eligibility Check**: Insurance verification result; attributes include patient reference, payer, check date, coverage active (Yes/No), copay amount, deductible remaining, out-of-pocket max, authorization required flag
- **Cost Estimate**: AI-generated pricing prediction; attributes include referral reference, estimated total cost, insurance coverage, patient responsibility, confidence level (High/Medium/Low), calculation method, estimate date
- **Communication**: Notification sent to patient; attributes include referral reference, recipient (email/phone), channel (email/SMS), message content, send time, delivery status, trigger event
- **Audit Log**: HIPAA compliance record; attributes include user ID, timestamp, patient accessed, action (view/edit/export), IP address, session ID, result (success/denied)

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Referral completion rate reaches 85% within 12 months (referrals resulting in completed specialist visit / total referrals created)
- **SC-002**: Coordinator time per referral reduces by 60% compared to pre-BillHarmony manual process (target: 5 minutes vs. 12.5 minutes baseline)
- **SC-003**: Specialist referral acceptance occurs within 48 hours for 80% of routine referrals and 24 hours for 90% of urgent referrals
- **SC-004**: Patient satisfaction with referral process averages 4.5/5 or higher based on post-referral surveys (measuring clarity, communication, ease of scheduling)
- **SC-005**: Cost estimate accuracy achieves ≥95% of estimates within ±20% of actual patient responsibility (measured after claim processing)
- **SC-006**: Insurance eligibility checks return results within 10 seconds for 95% of requests (p95 latency target)
- **SC-007**: System achieves 99.5% uptime measured over rolling 30-day periods (approximately 3.6 hours downtime per month allowed)
- **SC-008**: Zero critical security vulnerabilities or HIPAA violations result from platform security controls (verified through quarterly penetration testing)
- **SC-009**: EHR integration synchronizes referral data within 30 seconds for 95% of transactions (bi-directional sync from BillHarmony to EHR and vice versa)
- **SC-010**: Referral leakage rate decreases by 40% compared to baseline (target: leakage <15% of total referrals created)
- **SC-011**: Patient financial assistance identification increases by 50% through automated eligibility screening (patients connected to charity care, payment plans, sliding scale)
- **SC-012**: Annual Recurring Revenue (ARR) reaches $10M within 3 years through customer acquisition and retention (business objective validation)

## Assumptions

### Business Model & Market
- BillHarmony is sold as SaaS subscription to community and regional health systems (20-500 bed hospitals)
- Pricing model is per-referral or per-user per-month (specific pricing TBD in go-to-market strategy)
- Target market is US-based health systems with high referral volumes (500+ referrals/month minimum)
- Initial customers are early adopter pilot partners willing to co-create product
- Product competes with manual referral processes and legacy referral management systems

### User Workflows
- Referral coordinators process 20-50 referrals per day on average
- Specialists review referral queues daily and respond within 48 hours for routine referrals
- Patients prefer SMS for urgent notifications and email for detailed information
- Health system admins review analytics monthly for strategic planning
- Average referral lifecycle from creation to completed visit is 30-45 days

### Technical Infrastructure
- Health systems have EHR systems with HL7 v2 or FHIR R4 capabilities (Epic, Cerner, Athena, Allscripts, etc.)
- Insurance eligibility checks use industry-standard 270/271 EDI transactions or payer APIs
- Cost estimates leverage contracted rate data from clearinghouses or historical claims databases
- AI cost model uses machine learning trained on >1M referral-claim pairs for accuracy
- BillHarmony is cloud-hosted (AWS, Azure, or GCP) with multi-region deployment for high availability

### Data & Privacy
- All patient data handling complies with HIPAA Privacy Rule and Security Rule
- Audit logs retained for minimum 7 years per HIPAA recordkeeping requirements
- Data residency requirements met (US-based data centers for US customers)
- Business Associate Agreements (BAAs) executed with all subprocessors (email, SMS, hosting providers)
- Patients consent to communications via terms of service accepted by health system

### Integration & Interoperability
- EHR integration uses FHIR R4 standard where available, HL7 v2 for legacy systems
- FHIR resources supported: Patient, Practitioner, ServiceRequest, Coverage, DocumentReference
- Integration setup requires 2-4 weeks per health system for testing and validation
- Real-time sync is preferred but async batch sync acceptable for non-critical updates
- API rate limiting protects against abuse (100 requests/minute per health system)

### Financial & Eligibility
- Insurance copay, deductible, and out-of-pocket max data available from eligibility responses
- Contracted rates for specialists vary by insurance plan and are not always available (fallback to chargemaster pricing)
- Prior authorization requirements vary by specialty and insurance (cardiology, orthopedics often require auth)
- Self-pay rates are typically 30-50% higher than negotiated insurance rates
- Financial assistance eligibility based on Federal Poverty Level (FPL) thresholds (e.g., <200% FPL)

### Scope & MVP Limitations
- MVP focuses on outpatient specialist referrals (not inpatient, emergency, or surgical referrals)
- MVP supports primary care to specialist referrals (not specialist-to-specialist)
- Appointment scheduling is integrated via third-party tools (not building full scheduler in MVP)
- Billing and claims processing are out of scope (BillHarmony provides estimates, not claims submission)
- Patient portal is basic for MVP (view referral status, cost estimate, notifications)
- Multi-language support not included in MVP (English only initially)

