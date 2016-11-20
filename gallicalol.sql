create table memes (
 id integer not null primary key,
 gallica_url text not null,
 top_text text,
 bottom_text text,
 scale real not null,
 image blob not null,
 clicked integer not null default 0
);
