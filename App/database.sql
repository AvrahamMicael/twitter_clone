create database twitter_clone character set utf8 collate utf9_unicode_ci;

create table usuarios(
    id int not null primary key auto_increment,
    nome varchar(100) not null,
    email varchar(100) not null,
    senha varchar(32) not null
);

create table tweets(
    id int not null primary key auto_increment,
    id_usuario int not null,
    foreign key(id_usuario) references usuarios(id),
    tweet varchar(140) not null,
    data datetime default current_timestamp
);

create table usuarios_seguidores(
    -- id int not null primary key auto_increment,
    id int not null,
    foreign key(id) references usuarios(id),
    id_usuario_seguindo int not null,
    foreign key(id_usuario_seguindo) references usuarios(id)
);