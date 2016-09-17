FROM mysql:5.6

COPY sql/schema.sql /docker-entrypoint-initdb.d/
COPY my.cnf /etc/mysql
