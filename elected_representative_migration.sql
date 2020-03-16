-- Etape 1: remplissage de la table `elected_representative`
-- création d'un champ temporaire `canonical` et sa clé unique
ALTER TABLE elected_representative ADD canonical VARCHAR(1024) DEFAULT NULL
;
CREATE UNIQUE INDEX elected_representative_canonical ON elected_representative(canonical)
;

-- reprise des données vers la table `elected_representative`
INSERT IGNORE INTO elected_representative(last_name, first_name, gender, birth_date, official_id, canonical)
SELECT
    nom, prenom, genre, date_naissance, identification_elu, CONCAT_WS('-',prenom, nom, date_naissance)
FROM
    elected_representatives_register
;

-- supprimer le champ temporaire
ALTER TABLE elected_representative DROP canonical
;

-- Etape 2: remplissage de la colonne `elected_representative.adherent_id`
-- creation d'une table temporaire listant tous les adhérents
CREATE TABLE temp_adherents_canonical(
                                         adherent_id INT(10) DEFAULT NULL,
                                         canonical VARCHAR(255) DEFAULT NULL
)
;
-- on insert tous les adherents avec leur canonical dans la table temporaire
INSERT INTO temp_adherents_canonical (
    adherent_id,
    canonical
)
SELECT
    a.id,
    CONCAT_WS(
            '-',
            a.first_name,
            a.last_name,
            a.birthdate
        )
FROM adherents a
;
-- on index le canonical
CREATE INDEX temp_adherents_canonical_canonical ON temp_adherents_canonical(canonical)
;
-- On met à jour le `adherent_id` des élus
UPDATE elected_representative AS elu
    INNER JOIN temp_adherents_canonical AS tmp
    ON tmp.canonical = CONCAT_WS(
            '-',
            elu.first_name,
            elu.last_name,
            elu.birth_date
        )
SET elu.adherent_id = tmp.adherent_id AND elu.is_adherent = NULL
WHERE elu.adherent_id IS NULL
;

-- on supprime la table de travail
DROP TABLE temp_adherents_canonical
;

-- @todo : completer les scripts et étapes
-- Etape 3: remplissage de la table `elected_representative_mandate`
-- ajouter les champs supplémantaires
ALTER TABLE elected_representative_mandate
    ADD dpt_nom VARCHAR(255) DEFAULT NULL, epci_nom VARCHAR(255) DEFAULT NULL,
    commune_nom VARCHAR(255) DEFAULT NULL, commune_code INT(20) DEFAULT NULL,
    region_nom VARCHAR(255) DEFAULT NULL,
    circo_legis_nom VARCHAR(255) DEFAULT NULL, circo_legis_code INT(20) DEFAULT NULL
;
ALTER TABLE elected_representative_mandate ADD INDEX ;
-- remplissage de la table `elected_representative_mandate`
INSERT INTO elected_representative_mandate (
    elected_representative_id,
    type,
    is_elected,
    begin_at,
    political_affiliation,
    on_going,
    number,
    dpt_nom,
    epci_nom,
    commune_nom,
    commune_code,
    region_nom,
    circo_legis_nom,
    circo_legis_code
)
SELECT er.id, type_elu, 1, date_debut_mandat, nuance_politique, 1, 1, dpt_nom, epci_nom, commune_nom, commune_code, region_nom, circo_legis_nom, circo_legis_code FROM elected_representatives_register err
                                                                                                                                                                           LEFT JOIN elected_representative er ON er.canonical = CONCAT_WS('-',err.prenom, err.nom, err.date_naissance);
-- ajouter les zones

-- supprimer les champs supplémantaires
ALTER TABLE elected_representative_mandate
    DROP dpt_nom, epci_nom, commune_nom, commune_code, region_nom, circo_legis_nom, circo_legis_code;
-- fixer les noms des nuances politiques
UPDATE elected_representative_mandate SET political_affiliation = 'DVG' WHERE political_affiliation = 'LDVG';
UPDATE elected_representative_mandate SET political_affiliation = 'DIV' WHERE political_affiliation = 'LDIV';
UPDATE elected_representative_mandate SET political_affiliation = 'DVD' WHERE political_affiliation = 'LDVD';
UPDATE elected_representative_mandate SET political_affiliation = 'DIV' WHERE political_affiliation = 'AUT';
UPDATE elected_representative_mandate SET political_affiliation = 'UMP' WHERE political_affiliation = 'LUMP';
UPDATE elected_representative_mandate SET political_affiliation = 'UG' WHERE political_affiliation = 'LUG';
UPDATE elected_representative_mandate SET political_affiliation = 'FG' WHERE political_affiliation = 'LFG';
UPDATE elected_representative_mandate SET political_affiliation = 'UC' WHERE political_affiliation = 'LUC';
UPDATE elected_representative_mandate SET political_affiliation = 'FN' WHERE political_affiliation = 'LFN';
UPDATE elected_representative_mandate SET political_affiliation = 'SOC' WHERE political_affiliation = 'LSOC';
UPDATE elected_representative_mandate SET political_affiliation = 'EXG' WHERE political_affiliation = 'LEXG';
UPDATE elected_representative_mandate SET political_affiliation = 'UDI' WHERE political_affiliation = 'LUDI';
UPDATE elected_representative_mandate SET political_affiliation = 'NC' WHERE political_affiliation = 'M-NC';
UPDATE elected_representative_mandate SET political_affiliation = 'UDF' WHERE political_affiliation = 'UDFD';
UPDATE elected_representative_mandate SET political_affiliation = 'UD' WHERE political_affiliation = 'LUD';
UPDATE elected_representative_mandate SET political_affiliation = 'VEC' WHERE political_affiliation = 'LVEC';
UPDATE elected_representative_mandate SET political_affiliation = 'NC' WHERE political_affiliation = 'NCE';
UPDATE elected_representative_mandate SET political_affiliation = 'UC' WHERE political_affiliation = 'CEN';
UPDATE elected_representative_mandate SET political_affiliation = 'DVD' WHERE political_affiliation = 'PREP';
UPDATE elected_representative_mandate SET political_affiliation = 'MDM' WHERE political_affiliation = 'LMDM';
UPDATE elected_representative_mandate SET political_affiliation = 'COM' WHERE political_affiliation = 'LCOM';
UPDATE elected_representative_mandate SET political_affiliation = 'PG' WHERE political_affiliation = 'LPG';
UPDATE elected_representative_mandate SET political_affiliation = 'EXD' WHERE political_affiliation = 'LEXD';
UPDATE elected_representative_mandate SET political_affiliation = 'MD' WHERE political_affiliation = 'MODM';
UPDATE elected_representative_mandate SET political_affiliation = 'PRG' WHERE political_affiliation = 'RDG';

-- Etape 4: remplissage de la table `elected_representative_political_function`
INSERT INTO elected_representative_political_function (
    elected_representative_id,
    name,
    clarification,
    on_going,
    begin_at,
    mandate_id
)
SELECT er.id, nom_fonction, CLARIFICATION, 1, date_debut_fonction, MANDATE_ID FROM elected_representatives_register
                                                                                       LEFT JOIN elected_representative er ON er.canonical = CONCAT_WS('-',err.prenom, err.nom, err.date_naissance);
