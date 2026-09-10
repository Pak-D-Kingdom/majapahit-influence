# Graph Report - majapahit-influence-test  (2026-09-10)

## Corpus Check
- cluster-only mode — file stats not available

## Summary
- 1233 nodes · 2882 edges · 200 communities (34 shown, 71 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 8 edges (avg confidence: 0.84)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `77c9b333`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- KOL Profile Management
- Commission Administration
- Database Migrations
- KOL Email Notifications
- Composer Configuration
- Product Verification
- Audit and Brand Testing
- Campaign and Endorsement Controllers
- Brand and Product Reviews
- Admin Campaign Management
- Registration Review System
- Core Data Models
- Database Seeders and Relations
- Content and Auth Controllers
- User Roles and Policies
- Authentication Feature Tests
- Audit and Commission Services
- Frontend Build Configuration
- Model Relationship Definitions
- Endorsement Management
- Superadmin Campaign Assignment
- KOL Profile Requests
- Status Enums and Calculations
- Password Management
- Notification System
- Campaign Form Requests
- Brand Identity Assets
- Demo Data Seeding
- Campaign Business Logic
- Superadmin Endorsement Review
- Middleware and App Bootstrapping
- System Configuration and Utilities
- App Service Providers
- Login Rate Limiting
- Model Factories
- Role Authorization Tests
- User Profile Relations
- KOL Endorsement Tests
- Console Commands
- KOL Assignment Validation
- KOL Profile Permissions
- Audit Logging Utility
- Public Catalog Routes
- KOL Dashboard Tests
- KOL Status Enums
- File Storage Helper
- KOL Status Updates
- Content Proof Validation
- Forgot Password Validation
- Reset Password Validation
- Set Password Validation
- Disbursement Request Validation
- Content Proof Submission
- Content Proof Model
- Audit Log Service
- Notification Service
- Logging Configuration
- Public Registration Pages
- Approve Registration Validation
- Reject Registration Validation
- Update Campaign Validation
- Store Brand Validation
- Store Product Validation
- Update Brand Validation
- Brand Registration Validation
- Content Proof Request
- Login Validation
- Registration Validation
- Brand Request Validation
- Registration Review Validation
- Artisan Console Tasks
- Content Proof Files
- Brand Layout Templates
- KOL Layout Templates
- Project Metadata
- Superadmin Layout Templates
- System Logs
- Integration Contracts
- Onboarding Flowcharts
- Elita Brand Assets
- Tulia Product Assets
- Orelia Branding Assets
- Nour Luxe Assets
- Flowex Ad Assets
- Orniva Collection Assets
- Brew & Oak Assets
- La Doce Vida Assets
- BoldPro Mockups
- Creator Portrait 02
- Creator Portrait 03
- Creator Portrait 04
- Creator Portrait 05
- Creator Portrait 06
- Creator Portrait 07
- Creator Portrait 08
- Creator Portrait 09
- Creator Portrait 10
- Development Roadmap
- Product Catalog Critique
- Dev 1 Implementation
- Dev 4 Implementation
- Brand Assets
- Logo Assets
- Brand Database Table
- SEO Configuration

## God Nodes (most connected - your core abstractions)
1. `User` - 150 edges
2. `KolProfile` - 102 edges
3. `Endorsement` - 86 edges
4. `Brand` - 83 edges
5. `Campaign` - 75 edges
6. `Controller` - 69 edges
7. `AuditLog` - 56 edges
8. `Product` - 51 edges
9. `Commission` - 50 edges
10. `TestCase` - 40 edges

## Surprising Connections (you probably didn't know these)
- `Motion Graphic Index` --semantically_similar_to--> `Motion UI Index`  [INFERRED] [semantically similar]
  motion-graphic/index.html → motion-ui/index.html
- `Motion UI Index` --references--> `Sepatu Logo`  [INFERRED]
  motion-ui/index.html → public/assets/landing/images/brand/brand-01.jpg
- `Motion UI Index` --references--> `Creator Portrait 01`  [INFERRED]
  motion-ui/index.html → public/assets/landing/images/creator/creator-01.jpg
- `Dev3EndorsementTest` --references--> `KolProfile`  [EXTRACTED]
  tests/Feature/Dev3EndorsementTest.php → app/Models/KolProfile.php
- `Dev3EndorsementTest` --references--> `Tier`  [EXTRACTED]
  tests/Feature/Dev3EndorsementTest.php → app/Models/Tier.php

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Brand Onboarding and Product Lifecycle** — public_flowcharts_01_master_ecosystem_flowchart_brand_registration, public_flowcharts_01_master_ecosystem_flowchart_maklon_service, public_flowcharts_05_katalog_bank_konten_flowchart_product_create [EXTRACTED 0.90]
- **KOL Engagement and Promotion Paths** — public_flowcharts_01_master_ecosystem_flowchart_kol_registration, public_flowcharts_04_dua_jalur_promosi_flowchart_jalur_a, public_flowcharts_04_dua_jalur_promosi_flowchart_jalur_b [EXTRACTED 0.90]
- **Majapahit Influence Brand Identity** — public_motion_index, public_motion_ui_index, majapahit_influence_ecosystem [EXTRACTED 0.90]
- **Majapahit Influence Ecosystem Assets** — asset_brand_01, asset_brand_02, asset_brand_03, asset_brand_04, asset_brand_05, asset_brand_06, asset_brand_07, asset_brand_08, asset_brand_09, asset_brand_10, asset_creator_01, asset_creator_02, asset_creator_03, asset_creator_04, asset_creator_05, asset_creator_06, asset_creator_07, asset_creator_08, asset_creator_09, asset_creator_10 [EXTRACTED 1.00]
- **KOL Lifecycle and Compensation** — public_flowcharts_06_onboarding_kol_flowchart_svg, public_flowcharts_07_komisi_pencairan_flowchart_svg, locked_commission_40 [INFERRED 0.85]
- **Motion Animation Logic** — motion_graphic_index, motion_ui_index [INFERRED 0.85]

## Communities (200 total, 71 thin omitted)

### Community 0 - "KOL Profile Management"
Cohesion: 0.05
Nodes (22): KolManagementController, KolController, KolProfileRequest, KolProfile, KolRateCard, KolSocialMedia, Niche, Role (+14 more)

### Community 1 - "Commission Administration"
Cohesion: 0.05
Nodes (15): CommissionStatus, CommissionController, ReportController, CommissionController, DashboardController, ApproveDisbursementRequest, ProcessDisbursementRequest, Commission (+7 more)

### Community 2 - "Database Migrations"
Cohesion: 0.06
Nodes (3): Illuminate\Database\Migrations\Migration, Illuminate\Database\Schema\Blueprint, Illuminate\Support\Facades\Schema

### Community 3 - "KOL Email Notifications"
Cohesion: 0.07
Nodes (20): KolApprovedMail, Content, Envelope, KolRegistrationConfirmedMail, Content, Envelope, KolWelcomeMail, self (+12 more)

### Community 4 - "Composer Configuration"
Cohesion: 0.04
Nodes (48): pestphp/pest-plugin, php-http/discovery, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins (+40 more)

### Community 5 - "Product Verification"
Cohesion: 0.07
Nodes (8): ProductVerificationController, ProductController, VerifyProductRequest, ContentBank, Product, ProductService, ProductCatalogSeeder, SuperadminProductAndContentBankTest

### Community 6 - "Audit and Brand Testing"
Cohesion: 0.07
Nodes (11): Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Foundation\Testing\TestCase, AuditLogServiceTest, BrandPortalTest, ExploreDirectoriesTest, KolFilterTest, KolProfileManagementTest, KolRegistrationTest (+3 more)

### Community 7 - "Campaign and Endorsement Controllers"
Cohesion: 0.11
Nodes (10): CampaignController, DashboardController, EndorsementController, Controller, ContentProofController, DashboardController, AuditTrailController, BrandController (+2 more)

### Community 8 - "Brand and Product Reviews"
Cohesion: 0.12
Nodes (7): BrandRegistrationReviewController, ProductManagementController, BrandRegistrationController, CatalogController, BrandRegistration, ProductCategory, Symfony\Component\HttpFoundation\Response

### Community 9 - "Admin Campaign Management"
Cohesion: 0.14
Nodes (7): CampaignController, EndorsementController, ProfileController, NotificationController, RegistrationController, Illuminate\Http\JsonResponse, Illuminate\Http\Request

### Community 10 - "Registration Review System"
Cohesion: 0.12
Nodes (5): RegistrationReviewController, PublicRegistrationController, RegistrationController, KolRegistration, KolRegistrationService

### Community 11 - "Core Data Models"
Cohesion: 0.17
Nodes (5): RegistrationFile, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Database\Eloquent\Model, Illuminate\Database\Eloquent\Relations\BelongsTo, Illuminate\Database\Eloquent\SoftDeletes

### Community 12 - "Database Seeders and Relations"
Cohesion: 0.14
Nodes (6): Brand, Campaign, CommissionSeeder, Illuminate\Database\Eloquent\Relations\HasManyThrough, Dev3EndorsementTest, KolCommissionTest

### Community 13 - "Content and Auth Controllers"
Cohesion: 0.14
Nodes (6): ContentBankController, EndorsementController, AuthController, LoginController, Illuminate\Http\RedirectResponse, Illuminate\Support\Facades\Auth

### Community 14 - "User Roles and Policies"
Cohesion: 0.13
Nodes (4): User, CampaignPolicy, EndorsementPolicy, Illuminate\Foundation\Auth\User

### Community 16 - "Audit and Commission Services"
Cohesion: 0.13
Nodes (4): AuditLog, CommissionApproval, EndorsementService, Illuminate\Http\UploadedFile

### Community 17 - "Frontend Build Configuration"
Cohesion: 0.10
Nodes (20): devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, optionalDependencies, @laravel/multiplex (+12 more)

### Community 19 - "Endorsement Management"
Cohesion: 0.20
Nodes (3): EndorsementController, Endorsement, KolContentProofTest

### Community 20 - "Superadmin Campaign Assignment"
Cohesion: 0.17
Nodes (3): CampaignController, AssignmentRequest, CampaignRequest

### Community 21 - "KOL Profile Requests"
Cohesion: 0.13
Nodes (4): StoreKolManualRequest, UpdateProfileRequest, StoreKolRegistrationRequest, Illuminate\Contracts\Validation\ValidationRule

### Community 22 - "Status Enums and Calculations"
Cohesion: 0.14
Nodes (3): EndorsementStatus, RegistrationStatus, CommissionCalculationTest

### Community 23 - "Password Management"
Cohesion: 0.16
Nodes (4): ForgotPasswordController, ResetPasswordController, SetPasswordController, Illuminate\Support\Facades\Password

### Community 24 - "Notification System"
Cohesion: 0.24
Nodes (4): Notification, NotificationPolicy, Illuminate\Database\Eloquent\Concerns\HasUuids, NotificationTest

### Community 25 - "Campaign Form Requests"
Cohesion: 0.21
Nodes (4): StoreCampaignRequest, DisbursementRequest, Illuminate\Foundation\Http\FormRequest, Illuminate\Validation\Rule

### Community 26 - "Brand Identity Assets"
Cohesion: 0.18
Nodes (12): Sepatu Logo, Creator Portrait 01, 40% Locked Commission, Majapahit Influence Design System, Majapahit Influence Ecosystem, Motion Graphic Index, Motion UI Index, Pak De Group (+4 more)

### Community 30 - "Middleware and App Bootstrapping"
Cohesion: 0.25
Nodes (5): RoleMiddleware, Closure, Illuminate\Foundation\Application, Illuminate\Foundation\Configuration\Exceptions, Illuminate\Foundation\Configuration\Middleware

### Community 31 - "System Configuration and Utilities"
Cohesion: 0.22
Nodes (4): Illuminate\Support\Facades\RateLimiter, Illuminate\Support\Str, Illuminate\Validation\ValidationException, Pdo\Mysql

### Community 32 - "App Service Providers"
Cohesion: 0.29
Nodes (3): AppServiceProvider, Illuminate\Support\Facades\Gate, Illuminate\Support\ServiceProvider

### Community 34 - "Model Factories"
Cohesion: 0.32
Nodes (3): UserFactory, Illuminate\Database\Eloquent\Factories\Factory, static

### Community 38 - "Console Commands"
Cohesion: 0.40
Nodes (4): SendDeadlineReminders, Illuminate\Console\Attributes\Description, Illuminate\Console\Attributes\Signature, Illuminate\Console\Command

### Community 42 - "Public Catalog Routes"
Cohesion: 0.33
Nodes (6): Bank Konten (/katalog/slug/bank-konten), Public Catalog (/katalog), Maklon Service (Pabrik Pak De Group), Jalur A: Direct Selection, Jalur B: Marketplace Otomatis, Product Creation (/superadmin/products/create)

### Community 56 - "Logging Configuration"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 57 - "Public Registration Pages"
Cohesion: 0.40
Nodes (5): Brand Registration (/daftar-brand), KOL Registration (/daftar-kol), Landing Page (/), Visitor, Table: brand_registrations

### Community 70 - "Artisan Console Tasks"
Cohesion: 0.50
Nodes (3): Illuminate\Foundation\Inspiring, Illuminate\Support\Facades\Artisan, Illuminate\Support\Facades\Schedule

## Knowledge Gaps
- **100 isolated node(s):** `concurrently`, `laravel-vite-plugin`, `tailwindcss`, `@tailwindcss/vite`, `vite` (+95 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 440 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **71 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User Roles and Policies` to `KOL Profile Management`, `Commission Administration`, `KOL Email Notifications`, `Product Verification`, `Audit and Brand Testing`, `Campaign and Endorsement Controllers`, `Brand and Product Reviews`, `Registration Review System`, `Core Data Models`, `Database Seeders and Relations`, `Content and Auth Controllers`, `Authentication Feature Tests`, `Audit and Commission Services`, `Model Relationship Definitions`, `Endorsement Management`, `Status Enums and Calculations`, `Password Management`, `Notification System`, `Demo Data Seeding`, `Campaign Business Logic`, `App Service Providers`, `Model Factories`, `Role Authorization Tests`, `User Profile Relations`, `KOL Endorsement Tests`, `KOL Profile Permissions`, `Audit Logging Utility`, `KOL Dashboard Tests`, `Audit Log Service`, `Notification Service`, `System Logs`?**
  _High betweenness centrality (0.169) - this node is a cross-community bridge._
- **Why does `KolProfile` connect `KOL Profile Management` to `Commission Administration`, `Audit and Brand Testing`, `Admin Campaign Management`, `Registration Review System`, `Core Data Models`, `Database Seeders and Relations`, `Authentication Feature Tests`, `Model Relationship Definitions`, `Endorsement Management`, `Superadmin Campaign Assignment`, `Status Enums and Calculations`, `Campaign Form Requests`, `Demo Data Seeding`, `Campaign Business Logic`, `KOL Endorsement Tests`, `KOL Assignment Validation`, `KOL Profile Permissions`, `KOL Dashboard Tests`, `KOL Status Enums`, `KOL Status Updates`?**
  _High betweenness centrality (0.066) - this node is a cross-community bridge._
- **Why does `Controller` connect `Campaign and Endorsement Controllers` to `KOL Profile Management`, `Commission Administration`, `App Service Providers`, `Product Verification`, `Brand and Product Reviews`, `Admin Campaign Management`, `Registration Review System`, `Content and Auth Controllers`, `Endorsement Management`, `Superadmin Campaign Assignment`, `Password Management`, `Superadmin Endorsement Review`?**
  _High betweenness centrality (0.060) - this node is a cross-community bridge._
- **What connects `concurrently`, `laravel-vite-plugin`, `tailwindcss` to the rest of the system?**
  _100 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `KOL Profile Management` be split into smaller, more focused modules?**
  _Cohesion score 0.052795031055900624 - nodes in this community are weakly interconnected._
- **Should `Commission Administration` be split into smaller, more focused modules?**
  _Cohesion score 0.05222734254992319 - nodes in this community are weakly interconnected._
- **Should `Database Migrations` be split into smaller, more focused modules?**
  _Cohesion score 0.0593990216631726 - nodes in this community are weakly interconnected._