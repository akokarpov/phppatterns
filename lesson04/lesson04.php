<?php

// Abstract factory for MySQLFactory, PostgreSQLFactory, OracleFactory
abstract class AbstractFactory {
    abstract public function createConnection();
    abstract public function record();
    abstract public function queryBuilder();
}

// Interfaces templates
interface Connection {
    public function connect();
}

interface Record {
    public function record();
}

interface QueryBuilder {
    public function build();
}

// MySQL interfaces
class SQLConnection implements Connection {
    public function connect() {
        return "db connection for MySQL";
    }
}

class SQLRecord implements Record {
    public function record() {
        return "db record for MySQL";
    }
}

class SQLQuery implements queryBuilder {
    public function build() {
        return "db build for MySQL";
    }
}

// PostgreSQL interfaces
class PGConnection implements Connection {
    public function connect() {
        return "db connection for Postgre";
    }
}

class PGRecord implements Record {
    public function record() {
        return "db record for Postgre";
    }
}

class PGQuery implements queryBuilder {
    public function build() {
        return "db build for Postgre";
    }
}

// Oracle interfaces
class OracleConnection implements Connection {
    public function connect() {
        return "db connection for Oracle";
    }
}

class OracleRecord implements Record {
    public function record() {
        return "db record for Oracle";
    }
}

class OracleQuery implements queryBuilder {
    public function build() {
        return "db build for Oracle";
    }
}

// Factories built based on AbstractFactory
class MySQLFactory extends AbstractFactory {
    public function createConnection(){
        return new SQLConnection();
    }
    public function record(){
        return new SQLRecord();
    }
    public function queryBuilder(){
        return new SQLQuery();
    }
}

class PostgreSQLFactory extends AbstractFactory {
    public function createConnection(){
        return new PGConnection();
    }
    public function record(){
        return new PGRecord();
    }
    public function queryBuilder(){
        return new PGQuery();
    }
}

class OracleFactory extends AbstractFactory {
    public function createConnection(){
        return new OracleConnection();
    }
    public function record(){
        return new OracleRecord();
    }
    public function queryBuilder(){
        return new OracleQuery();
    }
}

?>