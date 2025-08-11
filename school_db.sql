
USE school_db;


-- Table:Students
CREATE TABLE tblstudentsdb (
    id INT AUTO_INCREMENT PRIMARY KEY,
    Firstname VARCHAR(500),
    Lastname VARCHAR(500),
    ID_Card VARCHAR(500),
    gender VARCHAR(10),
    Address VARCHAR(500),
    class INT,
    student_rank INT
);

-- Sample Data
INSERT INTO tblstudentsdb (ID_Card, Firstname, Lastname, gender, Address, class, student_rank) VALUES
('Su002','Dara', 'Po', 'Male', 'Kandal', 12, 1),
('Su003','Kong', 'Vanna', 'Male', 'Phnom Penh', 12, 2),
('Su004','Ky', 'Piseth', 'Male', 'Kandal', 12, 3),
('Su005','Sun', 'Vanndy', 'Male', 'Kandal', 12, 4),
('Su006','So', 'Phana', 'Male', 'Kandal', 12, 5),
('Su007','Var', 'Puthnith', 'Male', 'Kandal', 12, 6),
('Su008','Hok', 'Sohour', 'Male', 'Kandal', 12, 2),
('Su009','Sean', 'Visal', 'Male', 'Kandal', 12, 5);


SELECT * FROM tblstudentsdb;
