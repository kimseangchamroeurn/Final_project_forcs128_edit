USE student_db;

CREATE TABLE tblstudents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    gender VARCHAR(10),
    class INT,
    student_rank INT
);

INSERT INTO tblstudents (name, gender, class, student_rank) VALUES
('Hok Sohour', 'Male', 12, 1);

SELECT * FROM tblstudents;