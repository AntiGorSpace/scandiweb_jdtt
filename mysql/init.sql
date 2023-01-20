create table if not exists categories(
    id serial not null primary key,
    name varchar(64) unique
);

create table if not exists category_properties(
    id serial not null primary key,
    category_id integer references categories,
    code varchar(64) unique,
    label varchar(64)
);

ALTER TABLE category_properties ADD UNIQUE code_category(category_id, code);

create table if not exists skus(
    id serial not null primary key,
    category_id integer references categories,
    sku varchar(64) unique,
    name varchar(64),
    price float
);

create table if not exists sku_params(
    id serial not null primary key,
    sku_id integer references skus ON DELETE CASCADE,
    category_property_id integer references category_properties,
    value float
);

insert into categories
    (name) 
values 
    ('DVD'),
    ('Furniture'),
    ('Book');

insert into category_properties 
    (category_id, code, label)
values
    ((select id from categories where name = 'DVD'), 'size', 'Size (MB)'),
    ((select id from categories where name = 'Furniture'), 'height', 'Height (CM)'),
    ((select id from categories where name = 'Furniture'), 'width', 'Width (CM)'),
    ((select id from categories where name = 'Furniture'), 'length', 'Length (CM)'),
    ((select id from categories where name = 'Book'), 'weight', 'Weight (KG)')