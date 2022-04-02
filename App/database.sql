create database twitter_clone character set utf8 collate utf9_unicode_ci;

create table usuarios(
    id int not null primary key auto_increment,
    nome varchar(100) not null,
    email varchar(100) not null,
    senha varchar(32) not null
);