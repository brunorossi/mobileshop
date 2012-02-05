--
-- PostgreSQL database dump
--

-- Started on 2012-02-05 22:23:47 CET

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 141 (class 1259 OID 47199)
-- Dependencies: 3
-- Name: shops; Type: TABLE; Schema: public; Owner: zeo; Tablespace: 
--

CREATE TABLE shops (
    shop_id integer NOT NULL,
    name character varying(500) NOT NULL,
    address character varying(500) NOT NULL,
    zip_code character(5) NOT NULL
);


ALTER TABLE public.shops OWNER TO zeo;

--
-- TOC entry 142 (class 1259 OID 47202)
-- Dependencies: 141 3
-- Name: shops_shop_id_seq; Type: SEQUENCE; Schema: public; Owner: zeo
--

CREATE SEQUENCE shops_shop_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.shops_shop_id_seq OWNER TO zeo;

--
-- TOC entry 1809 (class 0 OID 0)
-- Dependencies: 142
-- Name: shops_shop_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: zeo
--

ALTER SEQUENCE shops_shop_id_seq OWNED BY shops.shop_id;


--
-- TOC entry 1810 (class 0 OID 0)
-- Dependencies: 142
-- Name: shops_shop_id_seq; Type: SEQUENCE SET; Schema: public; Owner: zeo
--

SELECT pg_catalog.setval('shops_shop_id_seq', 1, false);


--
-- TOC entry 144 (class 1259 OID 47217)
-- Dependencies: 3
-- Name: shops_tags; Type: TABLE; Schema: public; Owner: zeo; Tablespace: 
--

CREATE TABLE shops_tags (
    shop_id integer NOT NULL,
    tag_id integer NOT NULL
);


ALTER TABLE public.shops_tags OWNER TO zeo;

--
-- TOC entry 145 (class 1259 OID 47269)
-- Dependencies: 3
-- Name: tag_locales; Type: TABLE; Schema: public; Owner: zeo; Tablespace: 
--

CREATE TABLE tag_locales (
    tag_id integer NOT NULL,
    name character varying(200) NOT NULL,
    locale character varying(12) NOT NULL
);


ALTER TABLE public.tag_locales OWNER TO zeo;

--
-- TOC entry 140 (class 1259 OID 47196)
-- Dependencies: 3
-- Name: tags; Type: TABLE; Schema: public; Owner: zeo; Tablespace: 
--

CREATE TABLE tags (
    tag_id integer NOT NULL
);


ALTER TABLE public.tags OWNER TO zeo;

--
-- TOC entry 143 (class 1259 OID 47211)
-- Dependencies: 3 140
-- Name: tags_tag_id_seq; Type: SEQUENCE; Schema: public; Owner: zeo
--

CREATE SEQUENCE tags_tag_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.tags_tag_id_seq OWNER TO zeo;

--
-- TOC entry 1811 (class 0 OID 0)
-- Dependencies: 143
-- Name: tags_tag_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: zeo
--

ALTER SEQUENCE tags_tag_id_seq OWNED BY tags.tag_id;


--
-- TOC entry 1812 (class 0 OID 0)
-- Dependencies: 143
-- Name: tags_tag_id_seq; Type: SEQUENCE SET; Schema: public; Owner: zeo
--

SELECT pg_catalog.setval('tags_tag_id_seq', 1, false);


--
-- TOC entry 1787 (class 2604 OID 47204)
-- Dependencies: 142 141
-- Name: shop_id; Type: DEFAULT; Schema: public; Owner: zeo
--

ALTER TABLE shops ALTER COLUMN shop_id SET DEFAULT nextval('shops_shop_id_seq'::regclass);


--
-- TOC entry 1786 (class 2604 OID 47213)
-- Dependencies: 143 140
-- Name: tag_id; Type: DEFAULT; Schema: public; Owner: zeo
--

ALTER TABLE tags ALTER COLUMN tag_id SET DEFAULT nextval('tags_tag_id_seq'::regclass);


--
-- TOC entry 1801 (class 0 OID 47199)
-- Dependencies: 141
-- Data for Name: shops; Type: TABLE DATA; Schema: public; Owner: zeo
--

COPY shops (shop_id, name, address, zip_code) FROM stdin;
\.


--
-- TOC entry 1802 (class 0 OID 47217)
-- Dependencies: 144
-- Data for Name: shops_tags; Type: TABLE DATA; Schema: public; Owner: zeo
--

COPY shops_tags (shop_id, tag_id) FROM stdin;
\.


--
-- TOC entry 1803 (class 0 OID 47269)
-- Dependencies: 145
-- Data for Name: tag_locales; Type: TABLE DATA; Schema: public; Owner: zeo
--

COPY tag_locales (tag_id, name, locale) FROM stdin;
\.


--
-- TOC entry 1800 (class 0 OID 47196)
-- Dependencies: 140
-- Data for Name: tags; Type: TABLE DATA; Schema: public; Owner: zeo
--

COPY tags (tag_id) FROM stdin;
\.


--
-- TOC entry 1791 (class 2606 OID 47233)
-- Dependencies: 141 141
-- Name: pk_shop_id; Type: CONSTRAINT; Schema: public; Owner: zeo; Tablespace: 
--

ALTER TABLE ONLY shops
    ADD CONSTRAINT pk_shop_id PRIMARY KEY (shop_id);


--
-- TOC entry 1794 (class 2606 OID 47257)
-- Dependencies: 144 144 144
-- Name: pk_shop_id_tag_id; Type: CONSTRAINT; Schema: public; Owner: zeo; Tablespace: 
--

ALTER TABLE ONLY shops_tags
    ADD CONSTRAINT pk_shop_id_tag_id PRIMARY KEY (shop_id, tag_id);


--
-- TOC entry 1789 (class 2606 OID 47235)
-- Dependencies: 140 140
-- Name: pk_tag_id; Type: CONSTRAINT; Schema: public; Owner: zeo; Tablespace: 
--

ALTER TABLE ONLY tags
    ADD CONSTRAINT pk_tag_id PRIMARY KEY (tag_id);


--
-- TOC entry 1796 (class 2606 OID 47273)
-- Dependencies: 145 145 145 145
-- Name: pk_tag_id_name_locale; Type: CONSTRAINT; Schema: public; Owner: zeo; Tablespace: 
--

ALTER TABLE ONLY tag_locales
    ADD CONSTRAINT pk_tag_id_name_locale PRIMARY KEY (tag_id, name, locale);


--
-- TOC entry 1792 (class 1259 OID 47268)
-- Dependencies: 144
-- Name: fki_tag_id; Type: INDEX; Schema: public; Owner: zeo; Tablespace: 
--

CREATE INDEX fki_tag_id ON shops_tags USING btree (tag_id);


--
-- TOC entry 1797 (class 2606 OID 47258)
-- Dependencies: 141 144 1790
-- Name: fk_shop_id; Type: FK CONSTRAINT; Schema: public; Owner: zeo
--

ALTER TABLE ONLY shops_tags
    ADD CONSTRAINT fk_shop_id FOREIGN KEY (shop_id) REFERENCES shops(shop_id);


--
-- TOC entry 1798 (class 2606 OID 47263)
-- Dependencies: 144 140 1788
-- Name: fk_tag_id; Type: FK CONSTRAINT; Schema: public; Owner: zeo
--

ALTER TABLE ONLY shops_tags
    ADD CONSTRAINT fk_tag_id FOREIGN KEY (tag_id) REFERENCES tags(tag_id);


--
-- TOC entry 1799 (class 2606 OID 47274)
-- Dependencies: 1788 140 145
-- Name: tag_id; Type: FK CONSTRAINT; Schema: public; Owner: zeo
--

ALTER TABLE ONLY tag_locales
    ADD CONSTRAINT tag_id FOREIGN KEY (tag_id) REFERENCES tags(tag_id);


--
-- TOC entry 1808 (class 0 OID 0)
-- Dependencies: 3
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2012-02-05 22:23:48 CET

--
-- PostgreSQL database dump complete
--

