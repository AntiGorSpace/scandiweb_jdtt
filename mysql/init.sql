create table if not exists categories(
    id serial not null primary key,
    property_template_string varchar(128),
    name varchar(64) unique
);

create table if not exists category_properties(
    id serial not null primary key,
    category_id integer references categories,
    code varchar(64),
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
    (name, property_template_string) 
values 
    ('DVD', 'Size: {{size}} MB'),
    ('Furniture', 'Dimension: {{height}}x{{width}}x{{length}} CM'),
    ('Book', 'Weight: {{weight}} KG');

insert into category_properties 
    (category_id, code, label)
values
    ((select id from categories where name = 'DVD'), 'size', 'Size (MB)'),
    ((select id from categories where name = 'Furniture'), 'height', 'Height (CM)'),
    ((select id from categories where name = 'Furniture'), 'width', 'Width (CM)'),
    ((select id from categories where name = 'Furniture'), 'length', 'Length (CM)'),
    ((select id from categories where name = 'Book'), 'weight', 'Weight (KG)')
