# CSS Variables Quick Reference Card

## 🎨 Most Commonly Used Variables

### Colors

```css
/* Text */
--text                    /* Primary text color */
--text-dark               /* Dark text for headings */
--muted                   /* Muted/secondary text */
--white                   /* Pure white */

/* Backgrounds */
--card                    /* Card backgrounds */
--bg-color                /* Page background */
--bg-gray                 /* Light gray background */
--bg-gray-2               /* Slightly darker gray */

/* Primary */
--primary                 /* Main brand color */
--primary-2               /* Secondary brand color */

/* Status */
--success                 /* Green - success states */
--danger                  /* Red - errors/warnings */
--warning                 /* Orange - warnings */
--info                    /* Blue - information */
```

### Effects

```css
/* Shadows */
--shadow-sm               /* Small shadow */
--shadow-md               /* Medium shadow */
--shadow-lg               /* Large shadow */

/* Borders */
--border-radius           /* Default border radius (12px) */
--border                  /* Border color */
```

### Section-Specific

```css
/* Header */
--header-gradient-start
--header-gradient-end

/* Navigation */
--nav-gradient-start
--nav-gradient-end

/* Management Sections */
--mgmt-gradient-start
--mgmt-gradient-end
```

## 📝 Usage Examples

### Button

```css
.btn-primary {
  background: var(--primary);
  color: var(--white);
  border-radius: var(--border-radius);
  box-shadow: var(--shadow-md);
}
```

### Card

```css
.card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--border-radius);
  box-shadow: var(--shadow-sm);
}
```

### Status Badge

```css
.badge-success {
  background: var(--success);
  color: var(--white);
}
```

### Header

```css
.header {
  background: linear-gradient(
    135deg,
    var(--header-gradient-start),
    var(--header-gradient-end)
  );
}
```

## 🔍 Find More

See **CSS-VARIABLES-COMPLETE-SUMMARY.md** for:

- Complete list of all 67 variables
- Detailed color specifications
- Migration guide
- Best practices

---

**Quick tip:** Use your IDE's autocomplete with `var(--` to see all available variables!
