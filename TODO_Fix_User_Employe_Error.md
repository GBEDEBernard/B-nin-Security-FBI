# TODO - Fix User-Employe Relationship Error

## Task: Fix "Column user_id unknown" error in agent.blade.php and client.blade.php

### Problem:

The code incorrectly tries to query `employes.user_id` which doesn't exist.
The relationship is: users.employe_id -> employes.id

### Fix Required:

Replace all occurrences of:

```php
whereHas('employe', function($q) { $q->where('user_id', auth()->id()); })
```

With:

```php
where('employe_id', auth()->user()->employe_id)
```

With null check for safety:

```php
@php $employeId = auth()->user()->employe_id; @endphp
$employeId ? \Model::where('employe_id', $employeId)->... : 0
```

### Files Edited:

1. resources/views/dashboard/agent.blade.php (7 occurrences)
2. resources/views/dashboard/client.blade.php (2 occurrences)

### Steps:

- [x]   1. Analyze the error and understand the relationship
- [x]   2. Search for all occurrences of the incorrect pattern
- [x]   3. Fix the agent.blade.php file with proper null checks
- [x]   4. Fix the client.blade.php file (affectation -> site)
- [x]   5. Verify the fix works for all user types (superadmin, admin, agent, client)

## Summary:

Fixed 9 occurrences total:

### agent.blade.php (7 fixes):

1. Affectation count (Mes Missions)
2. Pointage count (Pointages Aujourd'hui)
3. Conge count (Congés en Attente)
4. Incident count (Incidents Aujourd'hui)
5. Affectation table (Mes Missions en Cours)
6. Pointage table (Historique de Pointage)
7. Modal site selection dropdown

### client.blade.php (2 fixes):

1. Incident count (Incidents Aujourd'hui)
2. Incident history (Historique des Incidents)

All queries now use the correct relationships based on the model structure.
