CREATE TABLE advisor (cod_advisor CHAR(5) NOT NULL, utente_id INT UNSIGNED DEFAULT 0 NOT NULL, deleted_at DATETIME, INDEX Utente_id_idx (utente_id), PRIMARY KEY(cod_advisor)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB;
CREATE TABLE advisor_microarea (id INT UNSIGNED NOT NULL AUTO_INCREMENT, cod_advisor CHAR(5) NOT NULL, cod_microarea CHAR(5) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME, INDEX Cod_advisor_idx (cod_advisor), INDEX Cod_microarea_idx (cod_microarea), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB;
CREATE TABLE agente (cod_agente CHAR(5) NOT NULL, utente_id INT UNSIGNED DEFAULT 0 NOT NULL, deleted_at DATETIME, INDEX Utente_id_idx (utente_id), PRIMARY KEY(cod_agente)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB;
CREATE TABLE agente_area (id INT UNSIGNED NOT NULL AUTO_INCREMENT, cod_agente CHAR(5) NOT NULL, cod_area CHAR(5) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME, INDEX Cod_agente_idx (cod_agente), INDEX Cod_area_idx (cod_area), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB;
CREATE TABLE area (cod_area CHAR(5) NOT NULL, descrizione VARCHAR(30) DEFAULT '' NOT NULL, PRIMARY KEY(cod_area)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB;
CREATE TABLE farmacia (cod_farmacia CHAR(20) NOT NULL, denominazione VARCHAR(100) DEFAULT '' NOT NULL, indirizzo VARCHAR(100) DEFAULT '' NOT NULL, cap CHAR(5) DEFAULT '' NOT NULL, localita VARCHAR(50) DEFAULT '' NOT NULL, partita_iva VARCHAR(20) DEFAULT '' NOT NULL, numtel VARCHAR(15) DEFAULT '' NOT NULL, numfax VARCHAR(15) DEFAULT '' NOT NULL, email VARCHAR(15) DEFAULT '' NOT NULL, contatto VARCHAR(50) DEFAULT '' NOT NULL, numtel_contatto VARCHAR(15) DEFAULT '' NOT NULL, email_contatto VARCHAR(15) DEFAULT '' NOT NULL, stato VARCHAR(255) DEFAULT 'attivabile' NOT NULL, cod_linea CHAR(10) NOT NULL, cod_microarea CHAR(5) NOT NULL, deleted_at DATETIME, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX Cod_linea_idx (cod_linea), INDEX Cod_microarea_idx (cod_microarea), PRIMARY KEY(cod_farmacia)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB;
CREATE TABLE gruppo (id INT UNSIGNED NOT NULL AUTO_INCREMENT, nome CHAR(10) NOT NULL, deleted_at DATETIME, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB;
CREATE TABLE linea (cod_linea CHAR(10) NOT NULL, descrizione VARCHAR(20) DEFAULT '' NOT NULL, PRIMARY KEY(cod_linea)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB;
CREATE TABLE microarea (cod_microarea CHAR(5) NOT NULL, descrizione VARCHAR(20) DEFAULT '' NOT NULL, cod_area CHAR(5) DEFAULT '' NOT NULL, provincia_id CHAR(2) DEFAULT '' NOT NULL, INDEX cod_area_idx (cod_area), INDEX provincia_id_idx (provincia_id), PRIMARY KEY(cod_microarea)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB;
CREATE TABLE provincia (id CHAR(2) NOT NULL, nome VARCHAR(32) DEFAULT '' NOT NULL, regione_id INT UNSIGNED DEFAULT '0' NOT NULL, INDEX Regione_id_idx (regione_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB;
CREATE TABLE regione (id INT UNSIGNED NOT NULL AUTO_INCREMENT, ripartizione_geografica VARCHAR(255), nome VARCHAR(32) DEFAULT '' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB;
CREATE TABLE utente (id INT UNSIGNED NOT NULL AUTO_INCREMENT, login VARCHAR(15) DEFAULT '' NOT NULL, nome_completo VARCHAR(50) DEFAULT '' NOT NULL, email VARCHAR(50) DEFAULT '' NOT NULL, password VARCHAR(50) DEFAULT '' NOT NULL, numtel VARCHAR(15) DEFAULT '' NOT NULL, numcel VARCHAR(15) DEFAULT '' NOT NULL, note VARCHAR(255) DEFAULT '' NOT NULL, deleted_at DATETIME, INDEX Login_idx (login), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB;
CREATE TABLE utente_gruppo (id INT UNSIGNED NOT NULL AUTO_INCREMENT, utente_id INT UNSIGNED DEFAULT 0, gruppo_id INT UNSIGNED DEFAULT 0, INDEX Utente_id_idx (utente_id), INDEX Gruppo_id_idx (gruppo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB;
ALTER TABLE advisor ADD CONSTRAINT advisor_utente_id_utente_id FOREIGN KEY (utente_id) REFERENCES utente(id) ON UPDATE CASCADE;
ALTER TABLE advisor_microarea ADD CONSTRAINT advisor_microarea_cod_microarea_microarea_cod_microarea FOREIGN KEY (cod_microarea) REFERENCES microarea(cod_microarea) ON UPDATE CASCADE;
ALTER TABLE advisor_microarea ADD CONSTRAINT advisor_microarea_cod_advisor_advisor_cod_advisor FOREIGN KEY (cod_advisor) REFERENCES advisor(cod_advisor) ON UPDATE CASCADE;
ALTER TABLE agente ADD CONSTRAINT agente_utente_id_utente_id FOREIGN KEY (utente_id) REFERENCES utente(id) ON UPDATE CASCADE;
ALTER TABLE agente_area ADD CONSTRAINT agente_area_cod_area_area_cod_area FOREIGN KEY (cod_area) REFERENCES area(cod_area) ON UPDATE CASCADE;
ALTER TABLE agente_area ADD CONSTRAINT agente_area_cod_agente_agente_cod_agente FOREIGN KEY (cod_agente) REFERENCES agente(cod_agente) ON UPDATE CASCADE;
ALTER TABLE farmacia ADD CONSTRAINT farmacia_cod_microarea_microarea_cod_microarea FOREIGN KEY (cod_microarea) REFERENCES microarea(cod_microarea) ON UPDATE CASCADE;
ALTER TABLE farmacia ADD CONSTRAINT farmacia_cod_linea_linea_cod_linea FOREIGN KEY (cod_linea) REFERENCES linea(cod_linea) ON UPDATE CASCADE;
ALTER TABLE microarea ADD CONSTRAINT microarea_provincia_id_provincia_id FOREIGN KEY (provincia_id) REFERENCES provincia(id);
ALTER TABLE microarea ADD CONSTRAINT microarea_cod_area_area_cod_area FOREIGN KEY (cod_area) REFERENCES area(cod_area) ON UPDATE CASCADE;
ALTER TABLE provincia ADD CONSTRAINT provincia_regione_id_regione_id FOREIGN KEY (regione_id) REFERENCES regione(id);
ALTER TABLE utente_gruppo ADD CONSTRAINT utente_gruppo_utente_id_utente_id FOREIGN KEY (utente_id) REFERENCES utente(id) ON UPDATE CASCADE ON DELETE SET NULL;
ALTER TABLE utente_gruppo ADD CONSTRAINT utente_gruppo_gruppo_id_gruppo_id FOREIGN KEY (gruppo_id) REFERENCES gruppo(id) ON UPDATE CASCADE ON DELETE SET NULL;