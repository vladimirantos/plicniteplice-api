create table companies(
    id char(36) primary key,
    name text not null,
    description text not null
);

create table recipes(
    id char(36) primary key,
    company char(36) null,
    user text not null,
    status enum('created', 'closed') default 'created',
    created datetime default current_timestamp,
    foreign key (company) references companies(id) on delete cascade
);

create table items(
    id char(36) primary key,
    recipe char(36) not null,
    name varchar (255) not null,
    foreign key (recipe) references recipes(id) on delete cascade
);

create table admin(
    id char(36) primary key,
    company char(36) null,
    name varchar (120) not null,
    email varchar (255) not null,
    password varchar (60) not null,
    foreign key(company) references companies(id) on delete cascade
);

create index recipes_status on recipes (status);
create unique index admin_email on admin(email);