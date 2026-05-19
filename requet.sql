
CREATE DATABASE peersync;
USE peersync;


CREATE TABLE apprenants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    id_user INT
);


CREATE TABLE demander_aides (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    technologie VARCHAR(100),

    id_apprenant INT NOT NULL,
    id_tuteur INT NULL,

    CONSTRAINT fk_apprenant
        FOREIGN KEY (id_apprenant)
        REFERENCES apprenants(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_tuteur
        FOREIGN KEY (id_tuteur)
        REFERENCES apprenants(id)
        ON DELETE SET NULL
);

CREATE TABLE avis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    note INT NOT NULL,
    commentaire TEXT,
    date DATE,

    id_aide INT,

    CONSTRAINT fk_aide
        FOREIGN KEY (id_aide)
        REFERENCES demander_aides(id)
        ON DELETE CASCADE
);




INSERT INTO apprenants (nom, prenom, password, id_user)
VALUES
('Alaoui', 'Yassine', 'pass123', 1),
('Bennani', 'Sara', 'pass456', 2),
('Tazi', 'Hamza', 'pass789', 3),
('Karimi', 'Lina', 'pass321', 4);


INSERT INTO demander_aides
(titre, description, technologie, id_apprenant, id_tuteur)
VALUES
(
    'Problème Laravel',
    'Je n’arrive pas à faire la connexion avec Laravel',
    'Laravel',
    1,
    2
),
(
    'Erreur JavaScript',
    'Mon code JS ne fonctionne pas dans le navigateur',
    'JavaScript',
    3,
    4
),
(
    'Base de données MySQL',
    'Besoin d’aide pour les jointures SQL',
    'MySQL',
    2,
    1
);


INSERT INTO avis
(note, commentaire, date, id_aide)
VALUES
(
    5,
    'Très bonne aide',
    '2026-05-19',
    1
),
(
    4,
    'Explication claire',
    '2026-05-18',
    2
),
(
    3,
    'Correct mais peut mieux faire',
    '2026-05-17',
    3
);

