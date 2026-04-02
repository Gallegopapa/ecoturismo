# HU001 Tourist Registration

| HU001 | Tourist Registration | Owner |
|-------|----------------------|--------|
| Element | Description | |
| User | As a tourist I want to register to access personalized features. | |
| Acceptance Criteria | - Mandatory validation <br> - Email confirmation <br> - Role assignment <br> - Error messages | |
| Prototype | Registration form. | |
| Technical Notes | Laravel Auth, validations, SMTP. | |
| Independent | Independent module. |
| Negotiable | Fields adjustable. |
| Valuable | Personalized access. |
| Estimable | Estimable in sprints. |
| Small | One sprint. |
| Testable | Registration tests. |

---

# HU002 Login

| HU002 | Login | Owner |
|-------|--------|--------|
| Element | Description | |
| User | As a user I want to log in. | |
| Acceptance Criteria | - Email/password login <br> - Error handling <br> - Password recovery | |
| Prototype | Login form. | |
| Technical Notes | Laravel sessions. | |
| Independent | Independent. |
| Negotiable | Flow adjustable. |
| Valuable | Access to system. |
| Estimable | Estimable. |
| Small | One sprint. |
| Testable | Login tests. |

---

# HU003 Guest Access

| HU003 | Guest Access | Owner |
|-------|--------------|--------|
| Element | Description | |
| User | As a user I want to browse as guest. | |
| Acceptance Criteria | - Guest option <br> - Restricted features | |
| Prototype | Guest button. | |
| Technical Notes | Middleware roles. | |
| Independent | Independent. |
| Negotiable | Restrictions flexible. |
| Valuable | Quick access. |
| Estimable | Estimable. |
| Small | One sprint. |
| Testable | Guest tests. |

---

# HU004 Sites Listing

| HU004 | Sites Listing | Owner |
|-------|--------------|--------|
| Element | Description | |
| User | As a user I want to see sites. | |
| Acceptance Criteria | - Image, name, description <br> - DB data <br> - Responsive | |
| Prototype | Cards. | |
| Technical Notes | Queries, pagination. | |
| Independent | Independent. |
| Negotiable | UI flexible. |
| Valuable | Explore sites. |
| Estimable | Estimable. |
| Small | One sprint. |
| Testable | Display tests. |

---

# HU005 Filter

| HU005 | Filter | Owner |
|-------|--------|--------|
| Element | Description | |
| User | As a user I want to filter sites. | |
| Acceptance Criteria | - Category filter <br> - Dynamic results <br> - Multiple filters | |
| Prototype | Dropdown/buttons. | |
| Technical Notes | Query filters. | |
| Independent | Independent. |
| Negotiable | Categories editable. |
| Valuable | Faster search. |
| Estimable | Estimable. |
| Small | One sprint. |
| Testable | Filter tests. |

---

# HU006 Site Detail

| HU006 | Site Detail | Owner |
|-------|-------------|--------|
| Element | Description | |
| User | As a user I want detailed info. | |
| Acceptance Criteria | - Full info <br> - Location <br> - Gallery <br> - Rates | |
| Prototype | Detail page. | |
| Technical Notes | Query by ID. | |
| Independent | Depends on listing. |
| Negotiable | Info expandable. |
| Valuable | Better planning. |
| Estimable | Estimable. |
| Small | One sprint. |
| Testable | Load tests. |

---

# HU007 Ratings

| HU007 | Ratings | Owner |
|-------|---------|--------|
| Element | Description | |
| User | As a user I want to rate sites. | |
| Acceptance Criteria | - Stars <br> - Comments <br> - Auth required | |
| Prototype | Comments section. | |
| Technical Notes | User relations. | |
| Independent | Needs auth. |
| Negotiable | Limit comments. |
| Valuable | Trust. |
| Estimable | Estimable. |
| Small | One sprint. |
| Testable | Comment tests. |

---

# HU008 Services

| HU008 | Services | Owner |
|-------|----------|--------|
| Element | Description | |
| User | As a user I want nearby services. | |
| Acceptance Criteria | - Listing <br> - Rates <br> - Location | |
| Prototype | Cards. | |
| Technical Notes | Relational DB. | |
| Independent | Independent. |
| Negotiable | Expandable. |
| Valuable | More info. |
| Estimable | Estimable. |
| Small | One sprint. |
| Testable | Display tests. |

---

# HU009 Favorites

| HU009 | Favorites | Owner |
|-------|-----------|--------|
| Element | Description | |
| User | As a user I want favorites. | |
| Acceptance Criteria | - Save button <br> - List <br> - Persistence | |
| Prototype | Heart icon. | |
| Technical Notes | Pivot table. | |
| Independent | Needs auth. |
| Negotiable | Removable. |
| Valuable | Personalization. |
| Estimable | Estimable. |
| Small | One sprint. |
| Testable | Save tests. |

---

# HU010 Share

| HU010 | Share | Owner |
|-------|--------|--------|
| Element | Description | |
| User | As a user I want to share sites. | |
| Acceptance Criteria | - Buttons <br> - Redirect | |
| Prototype | Icons. | |
| Technical Notes | Social APIs. | |
| Independent | Independent. |
| Negotiable | More networks. |
| Valuable | Visibility. |
| Estimable | Estimable. |
| Small | One sprint. |
| Testable | Share tests. |

---

# HU011 Contact

| HU011 | Contact | Owner |
|-------|----------|--------|
| Element | Description | |
| User | As a user I want to contact admins. | |
| Acceptance Criteria | - Form <br> - Validation <br> - Email sent | |
| Prototype | Form. | |
| Technical Notes | SMTP. | |
| Independent | Independent. |
| Negotiable | Channels. |
| Valuable | Communication. |
| Estimable | Estimable. |
| Small | One sprint. |
| Testable | Send tests. |

---

# HU012 Notifications

| HU012 | Notifications | Owner |
|-------|----------------|--------|
| Element | Description | |
| User | As a user I want notifications. | |
| Acceptance Criteria | - In-app <br> - Email | |
| Prototype | Bell icon. | |
| Technical Notes | Laravel notifications. | |
| Independent | Needs auth. |
| Negotiable | Frequency. |
| Valuable | Engagement. |
| Estimable | Estimable. |
| Small | One sprint. |
| Testable | Notification tests. |

---

# HU013 Multiplatform

| HU013 | Multiplatform | Owner |
|-------|----------------|--------|
| Element | Description | |
| User | As a user I want web and mobile access. | |
| Acceptance Criteria | - Responsive <br> - Multi-device | |
| Prototype | Responsive UI. | |
| Technical Notes | API. | |
| Independent | Progressive. |
| Negotiable | Priority adjustable. |
| Valuable | Accessibility. |
| Estimable | Estimable. |
| Small | Dividable. |
| Testable | Device tests. |

---

# HU014 Accessibility

| HU014 | Accessibility | Owner |
|-------|----------------|--------|
| Element | Description | |
| User | As a user I want accessibility. | |
| Acceptance Criteria | - Screen reader <br> - ARIA <br> - Contrast | |
| Prototype | Accessible UI. | |
| Technical Notes | WCAG. | |
| Independent | Progressive. |
| Negotiable | Expandable. |
| Valuable | Inclusion. |
| Estimable | Estimable. |
| Small | Incremental. |
| Testable | Accessibility tests. |

---

# HU015 Admin Panel

| HU015 | Admin Panel | Owner |
|-------|--------------|--------|
| Element | Description | |
| User | As admin I want to manage sites. | |
| Acceptance Criteria | - Create <br> - Edit <br> - Delete | |
| Prototype | Dashboard. | |
| Technical Notes | Laravel CRUD. | |
| Independent | Needs auth. |
| Negotiable | Expandable. |
| Valuable | Management. |
| Estimable | Estimable. |
| Small | Modular. |
| Testable | CRUD tests. |

---

# HU016 Reservations

| HU016 | Reservations | Owner |
|-------|---------------|--------|
| Element | Description | |
| User | As a user I want to book visits. | |
| Acceptance Criteria | - Booking form <br> - Confirmation <br> - Database record | |
| Prototype | Reservation form. | |
| Technical Notes | Database + email. | |
| Independent | Depends on detail. |
| Negotiable | Payment future. |
| Valuable | Planning. |
| Estimable | Estimable. |
| Small | One sprint. |
| Testable | Booking test. |