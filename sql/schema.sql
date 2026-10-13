CREATE TABLE IF NOT EXISTS submissions (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  full_name TEXT NOT NULL,
  email TEXT NOT NULL,
  phone TEXT NOT NULL DEFAULT '',
  reason TEXT NOT NULL CHECK (reason IN ('General Inquiry', 'Join', 'Sponsorship')),
  message TEXT,
  created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_submissions_email ON submissions (email);
CREATE INDEX IF NOT EXISTS idx_submissions_created_at ON submissions (created_at);