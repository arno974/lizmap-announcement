CREATE TABLE IF NOT EXISTS announcement_details (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    announcement_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    repository VARCHAR,
    project VARCHAR,
    content TEXT,
    permanent BOOLEAN,
    display_type TEXT
);
CREATE INDEX IF NOT EXISTS idx_announcement_details_repository_project ON announcement_details(repository, project);

CREATE TABLE IF NOT EXISTS announcement_user_close (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    user TEXT,
    repository TEXT,
    project TEXT,
    date_close_msg TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX IF NOT EXISTS idx_announcement_user_close_user_repository ON announcement_user_close(user, repository, project);
