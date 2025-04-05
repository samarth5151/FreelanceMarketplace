BEGIN TRANSACTION;
CREATE TABLE IF NOT EXISTS PostedJob (
    id INTEGER PRIMARY KEY AUTOINCREMENT, -- Unique identifier for each job
    title TEXT NOT NULL,                  -- Job title
    category TEXT NOT NULL,               -- Category of the job
    description TEXT NOT NULL,            -- Detailed job description
    attachment TEXT,                      -- File path or link for any attachment
    primary_skill TEXT NOT NULL,          -- Primary skill required for the job
    additional_skill TEXT,                -- Additional skills (optional)
    experience INTEGER NOT NULL,          -- Experience required (in years)
    budget REAL NOT NULL,                 -- Budget for the job (currency format)
    deadline DATE NOT NULL,               -- Deadline for job completion
    additional_questions TEXT             -- Additional questions from the client
);
CREATE TABLE IF NOT EXISTS freelancer_notifications (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    freelancer_id INTEGER NOT NULL,
    job_id INTEGER NOT NULL,
    client_id INTEGER NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT 0,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (freelancer_id) REFERENCES freelancers(id),
    FOREIGN KEY (job_id) REFERENCES jobs(id),
    FOREIGN KEY (client_id) REFERENCES users(id)
);
CREATE TABLE IF NOT EXISTS "freelancers" (
	"id"	INTEGER,
	"name"	TEXT NOT NULL,
	"username"	TEXT NOT NULL,
	"password"	TEXT NOT NULL,
	"email"	TEXT NOT NULL,
	"contact"	TEXT NOT NULL,
	"gender"	TEXT NOT NULL,
	"dob"	TEXT NOT NULL,
	"skills"	TEXT NOT NULL,
	"tools"	TEXT NOT NULL,
	"tagline"	TEXT NOT NULL,
	"about_me"	TEXT NOT NULL,
	"experience"	INTEGER NOT NULL,
	"languages"	TEXT NOT NULL,
	"availability"	TEXT NOT NULL,
	"degree"	TEXT NOT NULL,
	"institute"	TEXT NOT NULL,
	"graduation_year"	INTEGER NOT NULL,
	"profile_picture"	TEXT,
	"resume"	TEXT,
	"usertype"	TEXT NOT NULL,
	PRIMARY KEY("id" AUTOINCREMENT)
);
CREATE TABLE IF NOT EXISTS "jobs" (
	"id"	INTEGER,
	"username"	TEXT NOT NULL,
	"job_title"	TEXT NOT NULL,
	"job_category"	NUMERIC NOT NULL,
	"job_description"	NUMERIC NOT NULL,
	"primary_skill"	TEXT NOT NULL,
	"additional_skills"	NUMERIC,
	"experience_level"	TEXT NOT NULL,
	"budget"	REAL NOT NULL,
	"deadline"	DATE NOT NULL,
	"attachments"	TEXT,
	"additional_questions"	TEXT,
	"status"	TEXT DEFAULT 'Open',
	"NoOfbidsReceived"	INTEGER DEFAULT 0,
	"posted_date"	DATETIME DEFAULT CURRENT_TIMESTAMP, user_id INTEGER,
	PRIMARY KEY("id" AUTOINCREMENT)
);
CREATE TABLE IF NOT EXISTS messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    sender_id INTEGER NOT NULL, 
    receiver_id INTEGER NOT NULL, 
    message TEXT NOT NULL, 
    attachment_path TEXT, 
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id),
    FOREIGN KEY (receiver_id) REFERENCES freelancers(id)
);
CREATE TABLE IF NOT EXISTS notifications (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    job_id INTEGER NOT NULL,
    freelancer_id INTEGER NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT 0,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (job_id) REFERENCES jobs(id),
    FOREIGN KEY (freelancer_id) REFERENCES freelancers(id)
);
CREATE TABLE IF NOT EXISTS orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    job_id INTEGER NOT NULL,
    freelancer_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    proposal_id INTEGER NOT NULL,
    amount REAL NOT NULL,
    status TEXT DEFAULT 'In Progress',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP, submission_date TEXT, submission_notes TEXT, submission_status TEXT DEFAULT 'Not Submitted',
    FOREIGN KEY(job_id) REFERENCES jobs(id),
    FOREIGN KEY(freelancer_id) REFERENCES freelancers(id),
    FOREIGN KEY(user_id) REFERENCES users(id),
    FOREIGN KEY(proposal_id) REFERENCES proposals(id)
);
CREATE TABLE IF NOT EXISTS "proposals" (
	"id"	INTEGER,
	"job_id"	INTEGER NOT NULL,
	"freelancer_id"	INTEGER NOT NULL,
	"bid_amount"	REAL NOT NULL,
	"proposal_text"	TEXT,
	"submitted_date"	DATETIME DEFAULT CURRENT_TIMESTAMP,
	"status"	TEXT,
	PRIMARY KEY("id" AUTOINCREMENT),
	FOREIGN KEY("freelancer_id") REFERENCES "freelancers"("id")
);
CREATE TABLE IF NOT EXISTS "users" (
	"users_id"	INTEGER,
	"users_name"	VARCHAR(255),
	"username"	VARCHAR(255),
	"users_password"	VARCHAR(255) UNIQUE,
	"users_email"	VARCHAR(255),
	"users_contact"	INTEGER,
	"users_gender"	VARCHAR(255),
	"users_dob"	DATE,
	"users_profile_img"	VARCHAR(255),
	PRIMARY KEY("users_id" AUTOINCREMENT)
);
CREATE TABLE IF NOT EXISTS work_submissions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER NOT NULL,
    job_id INTEGER NOT NULL,
    freelancer_id INTEGER NOT NULL,
    description TEXT NOT NULL,
    files_path TEXT NOT NULL,
    submission_date TEXT NOT NULL,
    status TEXT NOT NULL DEFAULT 'Submitted',
    feedback TEXT,
    completion_date TEXT,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (job_id) REFERENCES jobs(id),
    FOREIGN KEY (freelancer_id) REFERENCES freelancers(id)
);
COMMIT;
