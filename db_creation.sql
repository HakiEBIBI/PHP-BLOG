DROP TABLE IF EXISTS blog_posts;
DROP TABLE IF EXISTS user;

create table user
(
    id       integer primary key autoincrement unique,
    name     varchar(50),
    email    varchar(50) unique not null,
    password varchar not null,
    is_admin integer
);


create table blog_posts
(
    id       integer primary key autoincrement unique,
    user_id    integer not null ,
    title      varchar not null,
    image      text,
    content    varchar not null,
    created_at text not null,
    FOREIGN KEY (user_id) REFERENCES user (id)
);

INSERT INTO user (name, email, password)
VALUES ('John Doe', 'JohnDoe@gmail.com', '$2y$10$fAiGjjnVTUBq4cIxBBC.rOJhVuAE74tTrjethCfoqtSUuBR6TKehu');

INSERT INTO user (name, email, password)
VALUES ('haki', 'haki@gmail.com', '$2y$10$6mz2LQda741vqD2kDOXmfu9KVBeHMyFsgNjqihjPtZPmRLsh0dQIG');

INSERT INTO user (name, email, password)
VALUES ('link', 'link@gmail.com', '$2y$10$4YxdUQ/.Z7CLJlq2r79liOCgtYRwHrvKCHTIGSDSD4nCtq.yVTLvC');

INSERT INTO blog_posts (user_id, title, image, content, created_at)
VALUES ('3','hyrule','image/67fcdbea78379_Screenshot From 2025-02-04 11-31-34.jpg','j&#39;adore hyrule','2025-04-11 08:46:48');

INSERT INTO blog_posts (user_id, title, image, content, created_at)
VALUES ('3','test','image/67f8d714537fe_Screenshot From 2025-02-04 11-41-12.png','salut','2025-04-11 08:47:16');