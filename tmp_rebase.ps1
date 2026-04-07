$c1 = "14775317"
$c2 = "372f036d"
$c3 = "486e9a74"

git checkout -B US-RES-01 d20bb910
git checkout $c1 -- .
git add app/Models
git commit -m "feat(US-RES-01): AC5, AC6 - Models for reservations and status defaults"
git add app/Http/Controllers
git commit -m "feat(US-RES-01): AC2, AC3, AC4 - Controller validations for overlapping and schedules"
git add routes resources
git commit -m "feat(US-RES-01): AC1 - Expose store endpoint and register modal frontend"

git checkout -B US-RES-02 US-RES-01
git checkout $c2 -- .
git add resources/js/react/reservations
git commit -m "feat(US-RES-02): AC1, AC2, AC3 - Reservations frontend list component"
git add resources/js/react/main.jsx routes/api.php
git commit -m "feat(US-RES-02): AC4, AC5 - Private routes for my-reservations"

git checkout -B US-RES-03 US-RES-02
git checkout $c3 -- .
git add app/Http/Controllers routes/api.php
git commit -m "feat(US-RES-03): AC1, AC2, AC3, AC4 - Reservation cancellation endpoint and policies"
git add resources/js/react/reservations/page.jsx
git commit -m "feat(US-RES-03): AC5 - Frontend confirmation modal and UI refresh"

git checkout develop
git reset --hard US-RES-03
git push origin develop -f
git push origin US-RES-01 -f
git push origin US-RES-02 -f
git push origin US-RES-03 -f
