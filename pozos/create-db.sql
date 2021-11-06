/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/SQLTemplate.sql to edit this template
 */
/**
 * Author:  davido
 * Created: Nov 4, 2021
 */

CREATE TABLE well_users (
    id INT(11) UNSIGNED AUTO_INCREMENT,
    username VARCHAR(45) UNIQUE,
    email VARCHAR(120) UNIQUE,
    password VARCHAR(255),
    PRIMARY KEY (id)

);

CREATE TABLE oil_wells (
    id INT(11) UNSIGNED AUTO_INCREMENT,
    name VARCHAR(120) UNIQUE,
    depth DECIMAL(22, 4) UNSIGNED COMMENT 'Oil well depth in meters',
    estimated_reserves DECIMAL(22, 4) UNSIGNED COMMENT 'Estimated well reserves in barrels',
    PRIMARY KEY (id)
);

CREATE TABLE measurements (
    id INT(11) UNSIGNED AUTO_INCREMENT,
    value DECIMAL(12, 4) UNSIGNED COMMENT 'Oil well pressure measurement in psi',
    time DATETIME,
    user_id INT(11) UNSIGNED,
    oil_well_id INT(11) UNSIGNED,
    PRIMARY KEY (id),
    CONSTRAINT fk_measurement_oil_well FOREIGN KEY (oil_well_id) REFERENCES oil_wells(id) ON DELETE CASCADE,
    CONSTRAINT fk_measurement_user_id FOREIGN KEY (user_id) REFERENCES well_users(id) ON DELETE SET NULL
);

CREATE UNIQUE INDEX time_measurement_idx ON measurements(oil_well_id, time);



