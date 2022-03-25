CREATE TABLE IF NOT EXISTS 'announcement_details' (
    'id' INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE, 
    'announcement_timestamp' timestamp DEFAULT CURRENT_TIMESTAMP,
    'repository' VARCHAR,
    'project' VARCHAR,
    'content' TEXT,
    'permanent' BOOLEAN,
    'display_type' TEXT
);
CREATE INDEX 'idx_announcement_details_permanent' ON announcement_details(permanent);
CREATE INDEX 'idx_announcement_details_repository' ON announcement_details(project);
CREATE INDEX 'idx_announcement_details_project' ON announcement_details(project);
CREATE INDEX 'idx_announcement_details_display_type' ON announcement_details(display_type);

CREATE TABLE  IF NOT EXISTS 'announcement_user_close'(
    'id' INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE,
    'user' text,
    'repository' text,
    'project' text,
    'date_close_msg' timestamp DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX 'idx_announcement_user_close_user' ON announcement_user_close(user);
CREATE INDEX 'idx_announcement_user_close_repository' ON announcement_user_close(repository);
CREATE INDEX 'idx_announcement_user_close_project' ON announcement_user_close(project);
CREATE INDEX 'idx_announcement_user_close_date_close_msg' ON announcement_user_close(date_close_msg);
