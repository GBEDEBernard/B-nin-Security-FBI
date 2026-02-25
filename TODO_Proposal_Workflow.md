# TODO - Proposal & Contract Workflow Implementation

## Plan Status

### ✅ Completed

- [x] Create TODO file
- [x]   1. Create public proposal form (devis.blade.php)
- [x]   2. Fix typo in controller (garde_renforee → garde_renforcee)
- [x]   3. Add public route for submission
- [x]   4. Update show view with workflow actions
- [x]   5. Update welcome page with link to proposal form

### 📋 Next Steps

- Test the workflow
- Verify the routes work correctly

## Implementation Details

### 1. Create Public Proposal Form ✅

- File: `resources/views/public/devis.blade.php`
- Purpose: Form for companies to submit proposals

### 2. Fix Typo in Controller ✅

- File: `app/Http/Controllers/SuperAdmin/PropositionContratController.php`
- Fix: Changed 'garde_renforee' to 'garde_renforcee' in validation

### 3. Add Public Route ✅

- File: `routes/web.php`
- Added routes:
    - GET /devis → public.devis view
    - POST /devis → soumettre proposal (public access)

### 4. Update Show View ✅

- File: `resources/views/admin/superadmin/propositions/show.blade.php`
- Added:
    - Step-by-step workflow buttons
    - Upload signed contract modal
    - Link to created enterprise

### 5. Update Welcome Page ✅

- File: `resources/views/welcome.blade.php`
- Added link to "Demande Devis" in Quick Actions

## Workflow Summary

1. **Company submits proposal** → Via public form at /devis
2. **Super Admin processes** → Views proposals at admin/superadmin/propositions
3. **Generate contract PDF** → Download PDF from proposal details
4. **Send to company** → Email or download
5. **Company signs** → Upload signed contract
6. **Create enterprise** → Convert signed proposal to active company
7. **Activate** → Enterprise is created with est_active=true
