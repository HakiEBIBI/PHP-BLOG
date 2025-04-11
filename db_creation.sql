DROP TABLE IF EXISTS blog_posts;
DROP TABLE IF EXISTS user;

create table user
(
    id       integer primary key autoincrement unique,
    name     varchar(50),
    email    varchar(50) unique not null,
    password varchar not null
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

