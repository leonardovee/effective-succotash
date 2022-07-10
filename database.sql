CREATE TABLE
  public.deposits (
    id character varying(255) NOT NULL,
    "user" character varying(255) NULL,
    value character varying(255) NULL,
    created_at timestamp without time zone NOT NULL DEFAULT now()
  );

ALTER TABLE
  public.deposits
ADD
  CONSTRAINT deposits_pkey PRIMARY KEY (id)

CREATE TABLE
  public.withdraws (
    id character varying(255) NOT NULL,
    "user" character varying(255) NULL,
    value character varying(255) NULL,
    created_at timestamp without time zone NOT NULL DEFAULT now()
  );

ALTER TABLE
  public.withdraws
ADD
  CONSTRAINT withdraws_pkey PRIMARY KEY (id)

CREATE TABLE
  public.transactions (
    id character varying(255) NOT NULL,
    deposit character varying(255) NULL,
    withdraw character varying(255) NULL,
    created_at timestamp without time zone NOT NULL DEFAULT now()
  );

ALTER TABLE
  public.transactions
ADD
  CONSTRAINT transactions_pkey PRIMARY KEY (id)