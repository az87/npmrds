-- Database: NPMRDS

-- DROP DATABASE "NPMRDS";

CREATE DATABASE "NPMRDS"
    WITH 
    OWNER = postgres
    ENCODING = 'UTF8'
    LC_COLLATE = 'English_United States.1252'
    LC_CTYPE = 'English_United States.1252'
    TABLESPACE = pg_default
    CONNECTION LIMIT = -1;


-- SCHEMA: NPMRDS

-- DROP SCHEMA "NPMRDS" ;

CREATE SCHEMA "NPMRDS"
    AUTHORIZATION postgres;


	
CREATE SEQUENCE "NPMRDS".user_id_seq
    INCREMENT 1
    START 1
    MINVALUE 1
    MAXVALUE 9223372036854775807
    CACHE 1;

ALTER SEQUENCE "NPMRDS".user_id_seq
    OWNER TO postgres;


CREATE SEQUENCE "NPMRDS".user_temp_id_seq
    INCREMENT 1
    START 1
    MINVALUE 1
    MAXVALUE 9223372036854775807
    CACHE 1;

ALTER SEQUENCE "NPMRDS".user_temp_id_seq
    OWNER TO postgres;
	
CREATE SEQUENCE "NPMRDS".highway_id_seq
    INCREMENT 1
    START 1
    MINVALUE 1
    MAXVALUE 9223372036854775807
    CACHE 1;

ALTER SEQUENCE "NPMRDS".highway_id_seq
    OWNER TO postgres;

	
CREATE SEQUENCE "NPMRDS".segment_id_seq
    INCREMENT 1
    START 1
    MINVALUE 1
    MAXVALUE 9223372036854775807
    CACHE 1;

ALTER SEQUENCE "NPMRDS".segment_id_seq
    OWNER TO postgres;
	
CREATE SEQUENCE "NPMRDS".speed_id_seq
    INCREMENT 1
    START 1
    MINVALUE 1
    MAXVALUE 9223372036854775807
    CACHE 1;

ALTER SEQUENCE "NPMRDS".speed_id_seq
    OWNER TO postgres;

-- Table: "NPMRDS"."user"

-- DROP TABLE "NPMRDS"."user";

CREATE TABLE "NPMRDS"."user"
(
    id integer NOT NULL DEFAULT nextval('"NPMRDS".user_id_seq'::regclass),
    user_name text COLLATE pg_catalog."default" NOT NULL,
    password text COLLATE pg_catalog."default",
    role text COLLATE pg_catalog."default",
    full_name text COLLATE pg_catalog."default",
    email text COLLATE pg_catalog."default",
    date_update timestamp(1) with time zone,
    CONSTRAINT user_pkey PRIMARY KEY (id)
)
WITH (
    OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE "NPMRDS"."user"
    OWNER to postgres;
	
	
-- Table: "NPMRDS".user_temp

-- DROP TABLE "NPMRDS".user_temp;

CREATE TABLE "NPMRDS".user_temp
(
    id integer NOT NULL DEFAULT nextval('"NPMRDS".user_temp_id_seq'::regclass),
    user_name text COLLATE pg_catalog."default",
    password text COLLATE pg_catalog."default",
    email text COLLATE pg_catalog."default",
    full_name text COLLATE pg_catalog."default",
    confirm_key text COLLATE pg_catalog."default",
    CONSTRAINT user_temp_pkey PRIMARY KEY (id)
)
WITH (
    OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE "NPMRDS".user_temp
    OWNER to postgres;
	
-- Table: "NPMRDS".highway

-- DROP TABLE "NPMRDS".highway;

CREATE TABLE "NPMRDS".highway
(
    id integer NOT NULL DEFAULT nextval('"NPMRDS".highway_id_seq'::regclass),
    name text COLLATE pg_catalog."default" NOT NULL,
    CONSTRAINT highway_pkey PRIMARY KEY (id)
)
WITH (
    OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE "NPMRDS".highway
    OWNER to postgres;
	
-- Table: "NPMRDS".segment_order

-- DROP TABLE "NPMRDS".segment_order;

CREATE TABLE "NPMRDS".segment_order
(
    highway text COLLATE pg_catalog."default" NOT NULL,
    segment text COLLATE pg_catalog."default" NOT NULL,
    seg_ord bigint NOT NULL,
    seg_type text COLLATE pg_catalog."default" NOT NULL
)
WITH (
    OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE "NPMRDS".segment_order
    OWNER to postgres;
	
-- Table: "NPMRDS".segment

-- DROP TABLE "NPMRDS".segment;

CREATE TABLE "NPMRDS".segment
(
    id bigint NOT NULL DEFAULT nextval('"NPMRDS".segment_id_seq'::regclass),
	years integer NOT NULL,
	quarter integer NOT NULL,
    tmc text COLLATE pg_catalog."default" NOT NULL,
    admin_level_1 text COLLATE pg_catalog."default",
    admin_level_2 text COLLATE pg_catalog."default",
    admin_level_3 text COLLATE pg_catalog."default",
    distance double precision,
    road_number text COLLATE pg_catalog."default",
    road_name text COLLATE pg_catalog."default",
    latitude text COLLATE pg_catalog."default",
    longitude text COLLATE pg_catalog."default",
    road_direction text COLLATE pg_catalog."default",
    CONSTRAINT segment_pkey PRIMARY KEY (id),
    CONSTRAINT segment_uq UNIQUE (years, quarter, tmc)
)
WITH (
    OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE "NPMRDS".segment
    OWNER to postgres;
	

-- Table: "NPMRDS".speed

-- DROP TABLE "NPMRDS".speed;

CREATE TABLE "NPMRDS".speed
(
    id bigint NOT NULL DEFAULT nextval('"NPMRDS".speed_id_seq'::regclass),
    highway text COLLATE pg_catalog."default" NOT NULL,
    datee date NOT NULL,
    epoch integer NOT NULL,
    segment text COLLATE pg_catalog."default" NOT NULL,
    freight double precision,
    passenger double precision,
    total double precision,
    raw_freight double precision,
    raw_passenger double precision,
    raw_total double precision,
    CONSTRAINT speed_pkey PRIMARY KEY (id),
    CONSTRAINT speed_uq UNIQUE (highway, datee, epoch, segment)
)
WITH (
    OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE "NPMRDS".speed
    OWNER to postgres;
	
	
INSERT INTO "NPMRDS"."user"(user_name, password, role, full_name, email, date_update)
VALUES ('admin', '21232f297a57a5a743894a0e4a801fc3', 'A', 'Administrator', 'admin2@gmai.com', CURRENT_TIMESTAMP);

INSERT INTO "NPMRDS".highway(name)
VALUES ('I-35');
INSERT INTO "NPMRDS".highway(name)
VALUES ('I-40');
INSERT INTO "NPMRDS".highway(name)
VALUES ('I-44');
INSERT INTO "NPMRDS".highway(name)
VALUES ('OK-1');
INSERT INTO "NPMRDS".highway(name)
VALUES ('US-69');